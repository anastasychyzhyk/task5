<?php

namespace App\Entity;

use App\Repository\PlayRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayRepository::class)
 */
class Play
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
	
	/**
     * @ORM\Column(type="integer")
     */
    private $idGame;

    public function getIdGame(): ?integer
    {
        return $this->idGame;
    }
	public function setIdGame(int $idGame): self
    {
		$this->idGame=$idGame;
        return $this;
    }
	
	/**
     * @ORM\Column(type="string", nullable=true)
     */
    private $userName;

    public function getUserName(): ?string
    {
        return $this->userName;
    }
	public function setUserName(string $userName): self
    {
		$this->userName=$userName;
        return $this;
    }
	
	/**
     * @ORM\Column(type="integer")
     */
    private $x;

    public function getX(): ?int
    {
        return $this->x;
    }
	public function setX(int $x): self
    {
		$this->x=$x;
        return $this;
    }
	
	/**
     * @ORM\Column(type="integer")
     */
    private $y;

    public function getY(): ?int
    {
        return $this->y;
    }
	public function setY(int $y): self
    {
		$this->y=$y;
        return $this;
    }
}
