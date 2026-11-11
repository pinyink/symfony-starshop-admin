<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Setting;
use App\Services\FileUploader;
use App\Services\SettingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/settings')]
#[IsGranted('ROLE_USER')]
final class SettingController extends AbstractController
{
    #[Route('/', name: 'app_settings_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, SettingService $settingService, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('settings');
        $repo = $entityManager->getRepository(Setting::class);
        $settings = $repo->findAll();

        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('settings_edit', $request->request->get('_token'))) {
                $this->addFlash('error', 'Token CSRF tidak valid.');
                return $this->redirectToRoute('app_settings_index');
            }

            $postData = $request->request->all('settings');
            $filesData = $request->files->all('settings');

            foreach ($postData as $key => $value) {
                $settingService->set($key, $value);
            }

            // Handle file uploads if any
            foreach ($filesData as $key => $file) {
                if ($file) {
                    $dir = $this->getParameter('kernel.project_dir') . '/public/uploads/settings';
                    $fileUploader->setDir('');
                    $fileUploader->setTargetDirectory($dir);
                    $filename = $fileUploader->upload($file);
                    $settingService->set($key, 'uploads/settings/' . $filename);
                }
            }

            $this->addFlash('success', 'Pengaturan berhasil disimpan.');
            return $this->redirectToRoute('app_settings_index');
        }

        // Group settings
        $grouped = [];
        foreach ($settings as $setting) {
            $grouped[$setting->getGroupName()][] = $setting;
        }

        return $this->render('setting/index.html.twig', [
            'grouped_settings' => $grouped,
        ]);
    }
}
