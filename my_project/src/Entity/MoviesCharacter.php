<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'movies_characters')]
class MoviesCharacter
{

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: "Movie", inversedBy: "movieCharacters")]
    #[ORM\JoinColumn(name: "movie_id", referencedColumnName: "id", nullable: false)]
    private $movie;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: "Character", inversedBy: "movieCharacters")]
    #[ORM\JoinColumn(name: "character_id", referencedColumnName: "id", nullable: false)]
    private $character;

    public function getMovie(): int
    {
        return $this->movie;
    }

    public function setMovie($movie): void
    {
        $this->movie = $movie;
    }

    public function getCharacter(): int
    {
        return $this->character;
    }

    public function setCharacter($character): void
    {
        $this->character = $character;
    }


}