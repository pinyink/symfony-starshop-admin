<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\BannerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LandingController extends AbstractController
{
    #[Route('/', name: 'app_landing')]
    public function index(BannerRepository $bannerRepository): Response
    {
        return $this->render('landing/index.html.twig', [
            'banner_repository' => $bannerRepository,
        ]);
    }
}
