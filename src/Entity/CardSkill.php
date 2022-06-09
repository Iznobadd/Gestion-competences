<?php

namespace App\Entity;

use App\Entity\Skill;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CardSkillRepository;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CardSkillRepository::class)]
class CardSkill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['info_user'])]
    private $id;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['info_user'])]
    private $love;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['info_user'])]
    private $stars;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'cardSkills')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Skill::class, inversedBy: 'cardSkills')]
    #[ORM\JoinColumn(nullable: false)]
    private $skill;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isLove(): ?bool
    {
        return $this->love;
    }

    public function setLove(bool $love): self
    {
        $this->love = $love;

        return $this;
    }

    public function getStars(): ?int
    {
        return $this->stars;
    }

    public function setStars(?int $stars): self
    {
        $this->stars = $stars;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }
}
