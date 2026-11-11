<?php

namespace App\Controller;

use App\Entity\Banner;
use App\Form\BannerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PhpRbacBundle\Attribute\AccessControl as RBAC;
use App\Services\Datatable;
use App\Services\FileUploader;

#[Route('/banners')]
final class BannerController extends AbstractController
{
    #[RBAC\IsGranted("/banners/read")]
    #[Route(name: 'app_banner_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('banner/index.html.twig');
    }

    #[RBAC\IsGranted("/banners/read")]
    #[Route('/ajax', name: 'app_banner_ajax', methods: ['GET', 'POST'])]
    public function ajax(Request $request, EntityManagerInterface $entityManager): Response
    {
        $datatable = new Datatable($request);
        $datatable->setColumns([null, "b.title", "b.sortOrder", "b.isActive", "b.image"])
                  ->setOrderColumn('b.id')
                  ->setQueryBuilder(
                      $entityManager->createQueryBuilder()
                          ->select("b.id, b.title, b.sortOrder, b.isActive, b.image")
                          ->from(Banner::class, 'b')
                  )
                  ->setCounterBy('b.id');
        $table = $datatable->create();
        
        $no = $table['start'] + 1;
        $data = [];
        foreach ($table['results'] as $row) {
            $statusBadge = $row['isActive'] 
                ? '<span class="badge bg-success">Aktif</span>' 
                : '<span class="badge bg-secondary">Nonaktif</span>';

            $data[] = [
                'no' => $no++,
                'id' => $row['id'],
                'title' => $row['title'],
                'sortOrder' => $row['sortOrder'] ?? 0,
                'isActive' => $statusBadge,
                'image' => $row['image'],
                'actions' => $this->renderView('banner/button.html.twig', ['id' => $row['id']]),
            ];
        }

        return $this->json([
            'draw' => $table['draw'],
            'recordsTotal' => $table['recordsTotal'],
            'recordsFiltered' => $table['recordsFiltered'],
            'data' => $data,
        ]);
    }

    #[RBAC\IsGranted("/banners/add")]
    #[Route('/new', name: 'app_banner_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $banner = new Banner();
        $form = $this->createForm(BannerType::class, $banner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $dir = $this->getParameter('image_directory');
                $fileUploader->setDir('');
                $fileUploader->setTargetDirectory($dir);
                $imageFileName = $fileUploader->upload($image);
                $banner->setImage($imageFileName);
            }

            $entityManager->persist($banner);
            $entityManager->flush();

            $this->addFlash('success', 'Banner berhasil ditambahkan.');
            return $this->redirectToRoute('app_banner_show', ['id' => $banner->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('banner/new.html.twig', [
            'banner' => $banner,
            'form' => $form,
        ]);
    }

    #[RBAC\IsGranted("/banners/read")]
    #[Route('/{id}', name: 'app_banner_show', methods: ['GET'])]
    public function show(Banner $banner): Response
    {
        return $this->render('banner/show.html.twig', [
            'banner' => $banner,
        ]);
    }

    #[RBAC\IsGranted("/banners/update")]
    #[Route('/{id}/edit', name: 'app_banner_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Banner $banner, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(BannerType::class, $banner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $dir = $this->getParameter('image_directory');
                $fileUploader->setDir('');
                $fileUploader->setTargetDirectory($dir);
                $imageFileName = $fileUploader->upload($image);
                $banner->setImage($imageFileName);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Banner berhasil diperbarui.');
            return $this->redirectToRoute('app_banner_show', ['id' => $banner->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('banner/edit.html.twig', [
            'banner' => $banner,
            'form' => $form,
        ]);
    }

    #[RBAC\IsGranted("/banners/delete")]
    #[Route('/{id}', name: 'app_banner_delete', methods: ['POST'])]
    public function delete(Request $request, Banner $banner, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $banner->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($banner);
            $entityManager->flush();
            $this->addFlash('success', 'Banner berhasil dihapus.');
        }

        return $this->redirectToRoute('app_banner_index', [], Response::HTTP_SEE_OTHER);
    }
}
