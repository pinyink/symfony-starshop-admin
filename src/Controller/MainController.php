<?php

namespace App\Controller;

use App\Entity\Permission;
use PhpRbacBundle\Core\Manager\PermissionManager;
use PhpRbacBundle\Core\Manager\RoleManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    private PermissionManager $permissionManager;

    public function __construct(PermissionManager $permissionManager)
    {
        $this->permissionManager = $permissionManager;
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
        $permManager = $this->container->get(PermissionManager::class);
        $perm = $permManager->add("notepad", "Notepad", PermissionManager::ROOT_ID);

        // Membuat role
        $roleManager = $this->container->get(RoleManager::class);
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
