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