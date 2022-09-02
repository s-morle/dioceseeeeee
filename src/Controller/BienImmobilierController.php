<?php

namespace App\Controller;

use App\Entity\BienImmobilier;
use App\Form\BienImmobilierType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BienImmobilierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

class BienImmobilierController extends AbstractController
{
    #[Route('/', name: 'app_bien_immobilier_index', methods: ['GET'])]
    #[ParamConverter('post', class: 'BienImmobilier')]


    public function index(BienImmobilierRepository $bienImmobilierRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        return $this->render('bien_immobilier/index.html.twig', [
            'bien_immobiliers' => $bienImmobilierRepository->findAll(),
        ]);
    }
    #[Route('/bien_immobilier/new', name: 'app_bien_immobilier_new')]
    public function new(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        $bien = new bienImmobilier();
        $form = $this->createForm(BienImmobilierType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $document */
            $document = $form->get('Documents')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($document) {
                $originalFilename = pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $document->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $document->move(
                        $this->getParameter('Documents_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'Documentsname' property to store the PDF file name
                // instead of its contents
                $bien->setDocuments(
                    new File(
                        $this->getParameter('Documents_directory') . '/' . $newFilename
                    )
                );
            }

            // Sauvegarder le BienImmobilier.
            $em = $doctrine->getManager();
            $em->persist($bien);
            $em->flush();
            $this->addFlash('success', 'Le document est enregistré');

            return $this->redirectToRoute('app_bien_immobilier_index');
        }

        return $this->renderForm('bien_immobilier/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{id}/show', name: 'app_bien_immobilier_show', methods: ['GET'])]
    public function show(BienImmobilier $bienImmobilier): Response
    {
        return $this->render('bien_immobilier/show.html.twig', [
            'bien_immobilier' => $bienImmobilier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bien_immobilier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BienImmobilier $bienImmobilier, BienImmobilierRepository $bienImmobilierRepository): Response
    {
        $form = $this->createForm(BienImmobilierType::class, $bienImmobilier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bienImmobilierRepository->add($bienImmobilier, true);

            return $this->redirectToRoute('app_bien_immobilier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien_immobilier/edit.html.twig', [
            'bien_immobilier' => $bienImmobilier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_bien_immobilier_delete', methods: ['POST'])]
    public function delete(Request $request, BienImmobilier $bienImmobilier, BienImmobilierRepository $bienImmobilierRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bienImmobilier->getId(), $request->request->get('_token'))) {
            $bienImmobilierRepository->remove($bienImmobilier, true);
        }

        return $this->redirectToRoute('app_bien_immobilier_index', [], Response::HTTP_SEE_OTHER);
    }
}