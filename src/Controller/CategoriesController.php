<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PhpRbacBundle\Attribute\AccessControl as RBAC;
use App\Services\Datatable;


#[Route('/categories')]
final class CategoriesController extends AbstractController
{
    #[RBAC\IsGranted("/categories/read")]
    #[Route(name: 'app_categories_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('categories/index.html.twig', [
        ]);
    }

    #[RBAC\IsGranted("/categories/read")]
    #[Route('/ajax', name: 'app_categories_ajax', methods: ['GET', 'POST'])]
    public function ajax(Request $request, EntityManagerInterface $entityManager): Response
    {
        $datatable = new Datatable($request);
        $datatable->setColumns([null, "u.name"])
                  ->setOrderColumn('u.id')
                  ->setQueryBuilder(
                      $entityManager->createQueryBuilder()
                          ->select("u.id, u.name")
                          ->from('App\Entity\Categories', 'u')
                          
                  )
                  ->setCounterBy('u.id');
        $table = $datatable->create();
        
        $no = $table['start'] + 1;
        $data = [];
        foreach ($table['results'] as $row) {
            $data[] = [
                'no' => $no++,
                'id' => $row['id'],
                'name' => $row['name'],
                'actions' => $this->renderView('categories/button.html.twig', ['id' => $row['id']]),
            ];
        }

        return $this->json([
            'draw' => $table['draw'],
            'recordsTotal' => $table['recordsTotal'],
            'recordsFiltered' => $table['recordsFiltered'],
            'data' => $data,
        ]);
    }

    #[RBAC\IsGranted("/categories/add")]
    #[Route('/new', name: 'app_categories_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categories = new Categories();
        $form = $this->createForm(CategoriesType::class, $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($categories);
            $entityManager->flush();

            $this->addFlash('success', 'Tambah Data Berhasil');
            return $this->redirectToRoute('app_categories_show', ['id' => $categories->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categories/new.html.twig', [
            'categories' => $categories,
            'form' => $form,
        ]);
    }

    #[RBAC\IsGranted("/categories/read")]
    #[Route('/{id}', name: 'app_categories_show', methods: ['GET'])]
    public function show(Categories $categories): Response
    {
        return $this->render('categories/show.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[RBAC\IsGranted("/categories/update")]
    #[Route('/{id}/edit', name: 'app_categories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categories $categories, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoriesType::class, $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->flush();

            $this->addFlash('success', 'Update Data Berhasil');
            return $this->redirectToRoute('app_categories_show', ['id' => $categories->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categories/edit.html.twig', [
            'categories' => $categories,
            'form' => $form,
        ]);
    }

    #[RBAC\IsGranted("/categories/delete")]
    #[Route('/{id}', name: 'app_categories_delete', methods: ['POST'])]
    public function delete(Request $request, Categories $categories, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categories->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categories);
            $entityManager->flush();
            $this->addFlash('success', 'Hapus Data Berhasil');
        }

        return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
    }
}
