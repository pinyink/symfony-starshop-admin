<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigStringColumn;
use Omines\DataTablesBundle\DataTableFactory;
use PhpRbacBundle\Core\Manager\RoleManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use PhpRbacBundle\Attribute\AccessControl as RBAC;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[RBAC\IsGranted("/users/read")]
    #[Route('/', name: 'app_user', methods:['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $table = $dataTableFactory->create()
        ->add('link', TwigStringColumn::class, [
            'template' => '<a href="{{ path(\'app_user_show\', {id : row.id}) }}" class="btn btn-sm btn-primary text-white">Detail</a> <a href="{{ path(\'app_user_edit\', {id : row.id}) }}" class="btn btn-sm btn-info text-white">Edit</a>',
            'label' => 'Aksi'
        ])
        ->add('username', TextColumn::class, ['label' => 'Username'])
        ->add('fullname', TextColumn::class, ['label' => 'Nama'])
        ->add('email', TextColumn::class, ['label' => 'Email'])
        ->createAdapter(ORMAdapter::class, [
            'entity' => User::class
        ])
        ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'datatable' => $table,
        ]);
    }

    #[RBAC\IsGranted("/users/add")]
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, FileUploader $fileUploader, RoleManager $roleManager)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->all();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $data['user']['password']['first']
            );
            $user->setPassword($hashedPassword);
            // handle upload foto
            $foto = $form->get('foto')->getData();
            if ($foto) {
                $dir = $this->getParameter('foto_profil_directory');
                $fileUploader->setDir('');
                $fileUploader->setTargetDirectory($dir);
                $fotoFileName = $fileUploader->upload($foto);
                $user->setFoto($fotoFileName);
            }
            $user->setEmail($data['user']['email']);
            $user->setFullname($data['user']['fullname']);
            
            $role = $roleManager->getNode($data['user']['roles']);
            $user->addRbacRole($role);

            // Jika role code atau description mengandung 'admin', set Symfony security role ke ROLE_ADMIN
            if ($role && (stripos($role->getCode(), 'admin') !== false || stripos($role->getDescription(), 'admin') !== false)) {
                $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Tambah Data Berhasil');
            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[RBAC\IsGranted("/users/edit")]
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        FileUploader $fileUploader,
        RoleManager $roleManager
    ): Response {
        // 1. Ambil RbacRole pertama dari user
        $currentRole = $user->getRbacRoles()->first() ?: null;

        $form = $this->createForm(UserType::class, $user);
        if ($currentRole) {
            $form->get('roles')->setData($currentRole);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if (!empty($plainPassword)) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $foto = $form->get('foto')->getData();
            if ($foto) {
                $dir = $this->getParameter('foto_profil_directory');
                $fileUploader->setDir('');
                $fileUploader->setTargetDirectory($dir);
                $fotoFileName = $fileUploader->upload($foto);
                $user->setFoto($fotoFileName);
            }

            $selectedRoleEntity = $form->get('roles')->getData(); // Mengembalikan object Role
            if ($selectedRoleEntity) {
                $user->getRbacRoles()->clear();
                // Jika roleManager butuh ID atau object, sesuaikan kebutuhan method getNode / addRbacRole
                $roleNode = $roleManager->getNode($selectedRoleEntity->getId());
                $user->addRbacRole($roleNode);

                // Update Symfony security role berdasarkan RBAC role
                if ($roleNode && (stripos($roleNode->getCode(), 'admin') !== false || stripos($roleNode->getDescription(), 'admin') !== false)) {
                    $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
                } else {
                    $user->setRoles(['ROLE_USER']);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Edit Data Berhasil');
            return $this->redirectToRoute('app_user_edit', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[RBAC\IsGranted("/users/read")]
    #[Route('/{id}/show', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[RBAC\IsGranted("/users/delete")]
    #[Route('/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }
}
