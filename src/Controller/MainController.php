<?php

namespace App\Controller;

use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpRbacBundle\Core\Manager\PermissionManager;
use PhpRbacBundle\Core\Manager\RoleManager;
use PhpRbacBundle\Attribute\AccessControl as RBAC;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    private PermissionManager $permissionManager;
    private RoleManager $roleManager;

    public function __construct(PermissionManager $permissionManager, RoleManager $roleManager)
    {
        $this->permissionManager = $permissionManager;
        $this->roleManager = $roleManager;
    }
    
    #[Route('/dashboard', name: 'app_dashboard', methods:['GET'])]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/coba', name: 'app_coba', methods:['GET'])]
    public function coba(): Response
    {
        // Membuat permission
        $permManager = $this->permissionManager;
        $perm = $permManager->add("notepad", "Notepad", $this->permissionManager::ROOT_ID);

        $permManager->addPath("/notepad/artikle/read", [
            'notepad' => 'Notepad',
            'artikle' => 'artikle',
            'read' => 'Read Access'
        ]);

        $permManager->addPath("/notepad/artikle/write", [
            'notepad' => 'Notepad',
            'artikle' => 'Artikle',
            'write' => 'Write Access'
        ]);

        // Membuat role
        $roleManager = $this->roleManager;
        $roleManager->addPath("/editor/reviewer", [
            'editor' => 'Editor',
            'reviewer' => 'Reviewer'
        ]);

        // Mengaitkan permission ke role
        $editorId = $roleManager->getPathId("/editor");
        $reviewerId = $roleManager->getPathId("/editor/reviewer");

        $roleManager->assignPermission($roleManager->getNode($editorId), "/notepad");
        $roleManager->assignPermission($roleManager->getNode($reviewerId), "/notepad/artikle/read");
        $roleManager->assignPermission($roleManager->getNode($reviewerId), "/notepad/artikle/write");

        return new Response('Coba route is working!');
    }

    #[Route('/role_ke_user', name: 'app_role_to_user', methods:['GET'])]
    public function roleToUser(EntityManagerInterface $entityManagerInterface): Response
    {
        $roleManager = $this->roleManager;

        // Misalkan kita punya user dengan ID 1
        $userId = 3;

        // Mendapatkan ID role berdasarkan path
        // $editorRoleId = $roleManager->getPathId("/editor");
        $reviewerRoleId = $roleManager->getPathId("/editor/reviewer");
        $roleNode = $roleManager->getNode($reviewerRoleId);

        // Mengaitkan role ke user
        // $roleManager->assignUser($roleManager->getNode($editorRoleId), $userId);
        // $roleManager->assignUser($roleManager->getNode($reviewerRoleId), $userId);

        $user = $entityManagerInterface->getRepository(User::class)->find($userId);
        $user->addRbacRole($roleNode);
        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();

        return new Response('Roles assigned to user successfully!');
    }

    #[Rbac\IsGranted("/notepad/todolist")]
    #[Route('/check_access', name: 'app_check_access', methods: ['GET'])]
    public function checkAccess(): Response
    {
        // $user = 3;
        $user = $this->getUser();
        // $rbacCtrl = $rbac->hasPermission('/notepad/todolist/read', $user);
        // if ($rbacCtrl) {
        //     return new Response('User has access to /notepad/todolist/read');
        // } else {
        //     return new Response('User does NOT have access to /notepad/todolist/read');
        // }
        return new Response('User '.$user->getUsername().' has access to /notepad/todolist');
    }
}
