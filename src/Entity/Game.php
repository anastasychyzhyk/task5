<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
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
    private $size;

    public function getSize(): ?int
    {
        return $this->size;
    }
	public function setSize(int $size): self
    {
		$this->size=$size;
        return $this;
    }
	
	/**
     * @ORM\Column(type="string", length=255)
     */
    private $firstUserName;

    public function getFirstUserName(): ?string
    {
        return $this->firstUserName;
    }
	public function setFirstUserName(string $firstUserName): self
    {
		$this->firstUserName=$firstUserName;
        return $this;
    }
	
	/**
     * @ORM\Column(type="string", length=255)
     */
    private $secondUserName;

    public function getSecondUserName(): ?string
    {
        return $this->secondUserName;
    }
	public function setSecondUserName(string $secondUserName): self
    {
		$this->secondUserName=$secondUserName;
        return $this;
    }
	
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastMoveUserName;

    public function getLastMoveUserName(): ?string
    {
        return $this->lastMoveUserName;
    }
	public function setLastMoveUserName(string $lastMoveUserName): self
    {
		$this->lastMoveUserName=$lastMoveUserName;
        return $this;
    }
	
	/**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getName(): ?string
    {
        return $this->name;
    }
	public function setName(string $name): self
    {
		$this->name=$name;
        return $this;
    }
	/**
     * @ORM\Column(type="string", length=255)
     */
    private $tags;

    public function getTags(): ?string
    {
        return $this->tags;
    }
	public function setTags(string $tags): self
    {
		$this->tags=$tags;
        return $this;
    }
}

