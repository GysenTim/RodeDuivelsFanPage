<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column]
    private ?int $merchid = null;

    #[ORM\Column]
    private ?int $userNR = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(\DateTimeInterface $dob): static
    {
        $this->dob = $dob;

        return $this;
    }

    public function getMerchid(): ?int
    {
        return $this->merchid;
    }

    public function setMerchid(int $merchid): static
    {
        $this->merchid = $merchid;

        return $this;
    }

    public function getUserNR(): ?int
    {
        return $this->userNR;
    }

    public function setUserNR(int $userNR): static
    {
        $this->userNR = $userNR;

        return $this;
    }
}
