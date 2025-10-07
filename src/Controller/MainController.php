<?php

namespace App\Controller;

use App\Entity\Permission;
use App\Entity\Role;
use PhpRbacBundle\Core\Manager\PermissionManager;
use PhpRbacBundle\Core\Manager\RoleManager;
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

        $permManager->addPath("/notepad/todolist/read", [
            'notepad' => 'Notepad',
            'todolist' => 'Todo list',
            'read' => 'Read Access'
        ]);

        $permManager->addPath("/notepad/todolist/write", [
            'notepad' => 'Notepad',
            'todolist' => 'Todo list',
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
        $roleManager->assignPermission($roleManager->getNode($reviewerId), "/notepad/todolist/read");
        $roleManager->assignPermission($roleManager->getNode($reviewerId), "/notepad/todolist/write");

        return new Response('Coba route is working!');
    }
}
