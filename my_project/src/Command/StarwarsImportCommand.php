<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Character;
use App\Entity\Movie;
use App\Entity\MovieCharacter;
use Symfony\Component\HttpClient\HttpClient;


class StarwarsImportCommand extends Command
{
    private const API_URL = 'https://swapi.dev/api';
    private const API_URL_MOVIES_ENDPOINT = '/films/';

    private $entities;

    public function __construct(EntityManagerInterface $entities)
    {
        parent::__construct();
        $this->entities = $entities;
    }

    protected function configure(): void
    {
        $this->setName('starwars:import');
        $this->setDescription('Imports Star Wars data from ' . self::API_URL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = HttpClient::create();

        $response = $client->request('GET', self::API_URL . self::API_URL_MOVIES_ENDPOINT);
        $data = $response->toArray();

        // Loop movies (we can find characters data inside that endpoint)
        foreach ($data['results'] as $film) {

            // Movies creation
            $movie = new Movie();
            $movie->setName($film['title']);
            $this->entities->persist($movie);

            // Get every character data to create it's entity with fresh data from the API
            foreach ($film['characters'] as $characterUrl) {

                $characterResponse = $client->request('GET', $characterUrl);
                $characterData = $characterResponse->toArray();

                // Get character
                $character = $this->entities->getRepository(Character::class)->findOneBy([
                    'name' => $characterData['name']
                ]);

                if (empty($character)) {
                    // Create and persist the Character entity if it doesn't exist
                    $character = new Character();
                    $character->setName($characterData['name']);
                    $character->setMass($characterData['mass']);
                    $character->setHeight($characterData['height']);
                    $character->setGender($characterData['gender']);
                    $this->entities->persist($character);
                }

                // Create and persist the MovieCharacter entity
                $movieCharacter = new MovieCharacter();
                $movieCharacter->setMovie($movie);
                $movieCharacter->setCharacter($character);
                $this->entities->persist($movieCharacter);
            }
        }

        // Prevent unwanted data after updates
        $this->entities->flush();

        return Command::SUCCESS;
    }

}
