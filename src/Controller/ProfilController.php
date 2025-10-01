<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil', methods:['GET', 'POST'])]
    public function index(EntityManagerInterface $em, Security $security, Request $request, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(ProfilType::class, $user);                
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $em->getRepository(User::class)->findOneBy(['username' => $user->getUserIdentifier()]);
            $foto = $form->get('foto')->getData();
            if ($foto) {
                $dir = $this->getParameter('foto_profil_directory');
                $fileUploader->setDir('');
                $fileUploader->setTargetDirectory($dir);
                $fotoFileName = $fileUploader->upload($foto);
                $user->setFoto($fotoFileName);
            }
            $em->flush();
            $this->addFlash('success', 'Update Data Berhasil');
        } else {
            if ($form->isSubmitted()) {
                $this->addFlash('error', 'Update Data Gagal'. $form->getErrors(true, false));
            }
        }
        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }
}
