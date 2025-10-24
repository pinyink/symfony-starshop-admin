<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PhpRbacBundle\Attribute\AccessControl as RBAC;
use App\Services\Datatable;
use App\Services\FileUploader;

#[Route('/product')]
final class ProductController extends AbstractController
{
    #[RBAC\IsGranted("/product/read")]
    #[Route(name: 'app_product_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
        ]);
    }

    #[RBAC\IsGranted("/product/read")]
    #[Route('/ajax', name: 'app_product_ajax', methods: ['GET', 'POST'])]
    public function ajax(Request $request, EntityManagerInterface $entityManager): Response
    {
        $datatable = new Datatable($request);
        $datatable->setColumns([null, "u.nama", "u.harga", "u.tanggal", "u.tahun", "u.category"])
                  ->setOrderColumn('u.id')
                  ->setQueryBuilder(
                      $entityManager->createQueryBuilder()
                          ->select("u.id, u.nama, u.harga, DATE_FORMAT(u.tanggal, '%d-%m-%Y') as tanggal, u.tahun, categories.name as categories_name, u.image")
                          ->from('App\Entity\Product', 'u')
                          ->leftJoin('App\Entity\Categories', 'categories', 'WITH', 'categories.id = u.category')
                  )
                  ->setCounterBy('u.id');
        $table = $datatable->create();
        
        $no = $table['start'] + 1;
        $data = [];
        foreach ($table['results'] as $row) {
            $data[] = [
                'no' => $no++,
                'id' => $row['id'],
                'nama' => $row['nama'],
				'harga' => 'Rp ' . number_format($row['harga'], 2, ',', '.'),
				'tanggal' => $row['tanggal'],
				'tahun' => $row['tahun'],
				'categories_name' => $row['categories_name'],
				'image' => $row['image'],
                'actions' => $this->renderView('product/button.html.twig', ['id' => $row['id']]),
            ];
        }

        return $this->json([
            'draw' => $table['draw'],
            'recordsTotal' => $table['recordsTotal'],
            'recordsFiltered' => $table['recordsFiltered'],
            'data' => $data,
        ]);
    }

    #[RBAC\IsGranted("/product/add")]
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
			if ($image) {
                $dir = $this->getParameter('image_directory');
                $fileUploader->setDir('');
                $fileUploader->setTargetDirectory($dir);
                $imageFileName = $fileUploader->upload($image);
                $product->setImage($imageFileName);
            }
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
    public function edit(Request $request, EntityManagerInterface $entityManager, Product $product, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
			if ($image) {
                $dir = $this->getParameter('image_directory');
                $fileUploader->setDir('');
                $fileUploader->setTargetDirectory($dir);
                $imageFileName = $fileUploader->upload($image);
                $product->setImage($imageFileName);
            }
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
