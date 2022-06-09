<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExperiencesRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ExperiencesRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['collab_list'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['info_user', 'collab_list'])]
    private $jobName;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['info_user', 'collab_list'])]
    private $startedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['info_user', 'collab_list'])]
    private $endAt;

    #[ORM\Column(type: 'text')]
    #[Groups(['info_user', 'collab_list'])]
    private $description;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'experiences')]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobName(): ?string
    {
        return $this->jobName;
    }

    public function setJobName(string $jobName): self
    {
        $this->jobName = $jobName;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function __toString()
    {
        return $this->jobName;
    }
}
