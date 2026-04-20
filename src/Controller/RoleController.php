<?php

namespace App\Controller;

use App\Entity\Permission;
use App\Entity\Role;
use App\Form\RoleType;
use Doctrine\ORM\EntityManagerInterface;
use PhpRbacBundle\Core\Manager\PermissionManager;
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
            return $this->redirectToRoute('app_role_index');
        } elseif ($data['method'] == 'edit') {
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
            return $this->redirectToRoute('app_role_index');
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

        $roleManager->remove($role);
        return $this->json(['status' => 'success', 'message' => 'Role berhasil dihapus'], 200);
    }

    #[Route('/permission/{id}', name: 'app_role_permission', methods: ['GET','POST'])]
    public function permission($id, RoleManager $roleManager, EntityManagerInterface $entityManager, PermissionManager $permissionManager)
    {
        $role = $entityManager->getRepository(Role::class)->find($id);
        if (!$role) {
            $this->addFlash('error', 'Role tidak ditemukan');
            return $this->redirectToRoute('app_role_index');
        }

        $data = [];
        $query = $entityManager->getRepository(Permission::class)->findBy(['parent' => 1]);
        foreach ($query as $key => $value) {
            $array = [
                'id' => $value->getId(),
                'code' => $value->getCode(),
                'description' => $value->getDescription(),
            ];
            $queryChild = $entityManager->getRepository(Permission::class)->findBy(['parent' => $value->getId()]);
            $child = [];
            foreach ($queryChild as $k => $v) {
                $perm = $permissionManager->getNode($v->getId());
                if ($roleManager->hasPermission($id, $v->getId())) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $child[] = [
                    'id' => $v->getId(),
                    'code' => $v->getCode(),
                    'description' => $v->getDescription(),
                    'checked' => $checked
                ];
            }
            $array['child'] = $child;
            array_push($data, $array);
        }

        return $this->render('role/permission.html.twig', [
            'controller_name' => 'PermissionController',
            'role' => $role,
            'permissions' => $data,
        ]);
    }

    #[Route('/add_permission/{id}', name: 'app_role_add_permission', methods: ['POST'])]
    public function addPermission($id, Request $request, RoleManager $roleManager, PermissionManager $permissionManager)
    {
        if (!$this->isCsrfTokenValid('add_permission', $request->request->get('_token'))) {
            return $this->json(['status' => 'error', 'message' => 'Token tidak valid'], 403);
        }

        $data = $request->request->all();
        $roleId = $roleManager->getNode($data['role_id']);
        if (!$roleId) {
            return $this->json(['status' => 'error', 'message' => 'Role tidak ditemukan'], 404);
        }

        $roleManager->assignPermission($roleId, $permissionManager->getPath($data['permission_id']));

        return $this->json(['status' => 'success', 'message' => 'Permission berhasil ditambahkan'], 200);
    }

    #[Route('/remove_permission/{id}', name: 'app_role_remove_permission', methods: ['POST'])]
    public function removePermission($id, Request $request, RoleManager $roleManager, PermissionManager $permissionManager)
    {
        if (!$this->isCsrfTokenValid('remove_permission', $request->request->get('_token'))) {
            return $this->json(['status' => 'error', 'message' => 'Token tidak valid'], 403);
        }

        $data = $request->request->all();
        $roleId = $roleManager->getNode($data['role_id']);
        if (!$roleId) {
            return $this->json(['status' => 'error', 'message' => 'Role tidak ditemukan'], 404);
        }

        $roleManager->unassignPermission($roleId, $permissionManager->getPath($data['permission_id']));

        return $this->json(['status' => 'success', 'message' => 'Permission berhasil dihapus'], 200);
    }
}
