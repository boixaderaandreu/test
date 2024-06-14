<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'movies_characters')]
class MoviesCharacters
{
    /**
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="movieCharacters")
     * @ORM\JoinColumn(name="movie_id", referencedColumnName="id", nullable=false)
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity="Character", inversedBy="movieCharacters")
     * @ORM\JoinColumn(name="character_id", referencedColumnName="id", nullable=false)
     */
    private $character;

    /**
     * @return mixed
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * @param mixed $movie
     */
    public function setMovie($movie): void
    {
        $this->movie = $movie;
    }

    /**
     * @return mixed
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * @param mixed $character
     */
    public function setCharacter($character): void
    {
        $this->character = $character;
    }


}