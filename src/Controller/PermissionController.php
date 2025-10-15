<?php

namespace App\Controller;

use App\Entity\Permission;
use Doctrine\ORM\EntityManagerInterface;
use PhpRbacBundle\Core\Manager\PermissionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/permission')]
#[IsGranted('ROLE_ADMIN')]
final class PermissionController extends AbstractController
{
    #[Route('/', name: 'app_permission')]
    public function index(EntityManagerInterface $entityManager): Response
    {
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

    #[Route('/save', name: 'app_permission_save', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager, PermissionManager $permissionManager)
    {
        if (!$this->isCsrfTokenValid('add_edit', $request->request->get('_token'))) {
            $this->addFlash('error', 'Token tidak valid');
            return $this->redirectToRoute('app_permission');
        }

        $data = $request->request->all();
        if ($data['method'] == 'tambah') {
            $permissionManager->add($data['code'], $data['desc'], $permissionManager::ROOT_ID);
            $permissionManager->addPath("/".$data['code'].'/read', [
                $data['code'] => $data['desc'],
                'read' => 'Read Access'
            ]);
            $permissionManager->addPath("/".$data['code'].'/add', [
                $data['code'] => $data['desc'],
                'add' => 'Add Access'
            ]);
            $permissionManager->addPath("/".$data['code'].'/update', [
                $data['code'] => $data['desc'],
                'update' => 'Update Access'
            ]);
            $permissionManager->addPath("/".$data['code'].'/delete', [
                $data['code'] => $data['desc'],
                'delete' => 'delete Access'
            ]);

            $this->addFlash('success', 'Permission berhasil ditambahkan');
            return $this->redirectToRoute('app_permission');
        } else if ($data['method'] == 'edit') {
            $permission = $entityManager->getRepository(Permission::class)->find($data['id']);
            if (!$permission) {
                $this->addFlash('error', 'Permission tidak ditemukan');
                return $this->redirectToRoute('app_permission');
            }
            $permission->setCode($data['code']);
            $permission->setDescription($data['desc']);
            $entityManager->persist($permission);
            $entityManager->flush();
            $this->addFlash('success', 'Permission berhasil diupdate');
            return $this->redirectToRoute('app_permission');
        } else if($data['method'] == 'tambah submenu') {
            $parent = $entityManager->getRepository(Permission::class)->find($data['id']);
            if (!$parent) {
                $this->addFlash('error', 'Permission parent tidak ditemukan');
                return $this->redirectToRoute('app_permission');
            }
            $permissionManager->addPath("/".$parent->getCode().'/'.$data['code'], [
                $data['code'] => $data['desc'],
            ]);

            $this->addFlash('success', 'Sub Menu berhasil ditambahkan');
            return $this->redirectToRoute('app_permission');
        } else {
            $this->addFlash('error', 'Method tidak valid');
        }
    }

    #[Route('/delete', name: 'app_permission_delete', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, PermissionManager $permission)
    {
        if (!$this->isCsrfTokenValid('hapus', $request->request->get('_token'))) {
            $this->addFlash('error', 'Token tidak valid');
            return $this->redirectToRoute('app_permission');
        }

        $data = $request->request->all();
        $perm = $entityManager->getRepository(Permission::class)->find($data['id']);
        if (!$perm) {
            return $this->json(['status' => 'error', 'message' => 'Permission tidak ditemukan'], 404);
        }
        try {
            $permission->remove($perm);
            return $this->json(['status' => 'success', 'message' => 'Permission berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Permission gagal dihapus. '.$e->getMessage()], 500);
        }
    }
}
