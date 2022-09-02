<?php

namespace App\Controller;

use App\Entity\Upload;
use App\Form\UploadType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomePageController extends AbstractController
{
    #[Route('/home/page', name: 'app_home_page')]
    public function index(): Response
    {
        $upload = new Upload();
        $form = $this->createForm(UploadType::class, $upload);
        return $this->render('home_page/index.html.twig', [
            'form'== $form->createView(),
            'controller_name' => 'HomePageController',
        ]);
    }
}
