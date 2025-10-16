<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTableFactory;
use PhpRbacBundle\Attribute\AccessControl as RBAC;
use Omines\DataTablesBundle\Column\TextColumn;

#[Route('/product')]
final class ProductController extends AbstractController
{
    #[RBAC\IsGranted("/product/read")]
    #[Route(name: 'app_product_index', methods: ['GET', 'POST'])]
    public function index(DataTableFactory $dataTableFactory, Request $request): Response
    {
        $table = $dataTableFactory->create()
            ->add('no', TextColumn::class, [
                'label' => 'No', 
                'className' => 'text-center', 
                'orderable' => false, 
                'searchable' => false,
                'render' => function() use (&$counter, $request) {
                    return ++$counter;
                }
            ])
            ->add('nama', TextColumn::class, ['label' => 'Nama'])
			->add('harga', TextColumn::class, ['label' => 'Harga'])
            ->add('link', TwigColumn::class, [
                'template' => 'product/button.html.twig',
                'label' => 'Aksi'
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Product::class,
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('product/index.html.twig', [
            'datatable' => $table,
        ]);
    }

    #[RBAC\IsGranted("/product/add")]
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Tambah Data Berhasil');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[RBAC\IsGranted("/product/read")]
    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[RBAC\IsGranted("/product/update")]
    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Update Data Berhasil');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[RBAC\IsGranted("/product/delete")]
    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('success', 'Hapus Data Berhasil');
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
