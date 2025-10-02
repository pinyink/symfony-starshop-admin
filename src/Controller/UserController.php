<?php

namespace App\Controller;

use App\Entity\User;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigStringColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user', methods:['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $table = $dataTableFactory->create()
        ->add('link', TwigStringColumn::class, [
            'template' => '<a href="javascript:;" class="btn btn-sm btn-primary text-white">Detail</a> <a href="javascript:;" class="btn btn-sm btn-info text-white">Edit</a>',
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
}
