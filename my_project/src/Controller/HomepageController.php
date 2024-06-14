<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// src/Controller/HomepageController.php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class HomepageController extends AbstractController
{
    private $entityManager; // @TODO: Using entity manager directly instead of doctrine due local issues - to fix

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/homepage', name: 'app_homepage')]
    public function index(Request $request): Response
    {
        $characters = [];

        $searchTerm = $request->query->get('search');

        // TODO: To remove once data properly obtained
        $hardcodedTestChars =  [
            ['id' => 1, 'name' => 'Luck', 'image' => 'image.png', 'mass' => '100', 'height' => '185'],
            ['id' => 2, 'name' => 'Obi Wan', 'image' => 'image2.png', 'mass' => '80', 'height' => '180'],
            ['id' => 3, 'name' => 'Doku', 'image' => 'image3.png', 'mass' => '90', 'height' => '190']
        ];
        if ($searchTerm) {
            $filteredChars = array_filter($hardcodedTestChars, function($char) use ($searchTerm) {
                return stripos($char['name'], $searchTerm) !== false;
            });
            $finalCharacters = array_values($filteredChars);
        } else {
            $finalCharacters = $hardcodedTestChars;
        }
        return $this->render('homepage/index.html.twig', [
            'characters' => $finalCharacters,
            'searchTerm' => $searchTerm,
        ]);
        // TODO: END TODO

        if ($searchTerm) {
            $characters = $this->entityManager->getRepository(Character::class)
                ->findBy(['name' => $searchTerm]);
        } else {
            $characters = $this->entityManager->getRepository(Character::class)
                ->findAll();
        }

        return $this->render('homepage/index.html.twig', [
            'characters' => $characters,
            'searchTerm' => $searchTerm,
        ]);
    }
}