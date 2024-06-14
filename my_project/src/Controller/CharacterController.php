<?php
// src/Controller/CharacterController.php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CharacterType;
use Symfony\Component\String\Slugger\SluggerInterface;

class CharacterController extends AbstractController
{
    private $entityManager;
    private $slugger;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

    #[Route('/character/{id}/edit', name: 'character_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Character $character): Response
    {
        // Create the edit form
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFile = $form->get('pictureFile')->getData();

            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if file upload fails
                }

                // Set the picture URL in the entity
                $character->setPicture($this->getParameter('pictures_directory').'/'.$newFilename);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Character updated successfully.');

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('character/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/character/{id}/remove', name: 'character_remove', methods: ['POST'])]
    public function remove(Request $request, EntityManagerInterface $entityManager, Character $character): Response
    {
        $entityManager->remove($character);
        $entityManager->flush();

        $this->addFlash('success', 'Character removed successfully.');

        return $this->redirectToRoute('app_homepage');
    }
}
