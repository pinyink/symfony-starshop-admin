<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use Doctrine\ORM\EntityManagerInterface;
use PhpRbacBundle\Core\Manager\RoleManager;
use PhpRbacBundle\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/role')]
#[IsGranted('ROLE_ADMIN')]
final class RoleController extends AbstractController
{
    #[Route(name: 'app_role_index', methods: ['GET'])]
    public function index(RoleRepository $roleRepository): Response
    {
        return $this->render('role/index.html.twig', [
            'roles' => $roleRepository->findBy(['parent' => '1']),
        ]);
    }

    #[Route('/save', name: 'app_role_save', methods: ['POST'])]
    public function save(Request $request, RoleManager $roleManager, EntityManagerInterface $entity)
    {
        if (!$this->isCsrfTokenValid('add_edit', $request->request->get('_token'))) {
            $this->addFlash('error', 'Token tidak valid');
            return $this->redirectToRoute('app_role_index');
        }

        $data = $request->request->all();
        if ($data['method'] == 'tambah') {
            // $roleManager->add($data['code'], $data['description'], $data['parent']);
            $roleManager->addPath("/" . $data['code'], [
                $data['code'] => $data['desc']
            ]);
            $this->addFlash('success', 'Role berhasil ditambahkan');
            $this->redirectToRoute('app_role_index');
        } elseif ($data['method'] == 'edit') {
            // $role = $roleManager->getNode($data['id']);
            // if (!$role) {
            //     $this->addFlash('error', 'Role tidak ditemukan');
            //     return $this->redirectToRoute('app_role_index');
            // }
            // $roleManager->edit($role, $data['code'], $data['description']);
            // $this->addFlash('success', 'Role berhasil diupdate');

            $role = $entity->getRepository(Role::class)->find($data['id']);
            if (!$role) {
                $this->addFlash('error', 'Role tidak ditemukan');
                return $this->redirectToRoute('app_role_index');
            }
            $role->setCode($data['code']);
            $role->setDescription($data['desc']);
            $entity->persist($role);
            $entity->flush();
            $this->addFlash('success', 'Role berhasil diupdate');
            $this->redirectToRoute('app_role_index');
        } else {
            $this->addFlash('error', 'Method tidak valid');
        }

        return $this->redirectToRoute('app_role_index');
    }

    #[Route('/delete', name: 'app_role_delete', methods: ['DELETE'])]
    public function delete(Request $request, RoleManager $roleManager, EntityManagerInterface $entity)
    {
        if (!$this->isCsrfTokenValid('hapus', $request->request->get('_token'))) {
            $this->addFlash('error', 'Token tidak valid');
            return $this->redirectToRoute('app_role_index');
        }

        $data = $request->request->all();
        $role = $roleManager->getNode($data['id']);
        if (!$role) {
            return $this->json(['status' => 'error', 'message' => 'Role tidak ditemukan'], 404);
        }

        // Cek apakah ada user yang menggunakan role ini
        // $count = $entity->getRepository(Role::class)->count(['roleId' => $data['id']]);
        // if ($count > 0) {
        //     return $this->json(['status' => 'error', 'message' => 'Role ini sedang digunakan oleh user'], 400);
        // }

        $roleManager->remove($role);
        return $this->json(['status' => 'success', 'message' => 'Role berhasil dihapus'], 200);
    }
}
