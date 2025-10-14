<?php

namespace App\Controller;

use App\Entity\Permission;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/permission')]
#[IsGranted('ROLE_ADMIN')]
final class PermissionController extends AbstractController
{
    #[Route('/', name: 'app_permission')]
    public function index(Permission $permission, EntityManagerInterface $entityManager): Response
    {
        $data = [];
        $query = $entityManager->getRepository(Permission::class)->findAll();
        foreach ($query as $key => $value) {
            $array = [
                'id' => $value->getId(),
                'code' => $value->getCode(),
                'description' => $value->getDescription(),
            ];
            $queryChild = $entityManager->getRepository(Permission::class)->findBy(['parent' => $value->getId()]);
            $child = [];
            foreach ($queryChild as $k => $v) {
                $child[] = [
                    'id' => $v->getId(),
                    'code' => $v->getCode(),
                    'description' => $v->getDescription(),
                ];
            }
            $array['child'] = $child;
            array_push($data, $array);
        }
        return $this->render('permission/index.html.twig', [
            'controller_name' => 'PermissionController',
            'permissions' => $data,
        ]);
    }
}
