<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SkillRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['info_user', 'collab_list'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['info_user', 'collab_list'])]
    private $name;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'skills')]
    // #[Groups(['collab_list'])]
    private $user;

    #[ORM\OneToMany(mappedBy: 'skill', targetEntity: CardSkill::class)]
    private $cardSkills;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->cardSkills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, CardSkill>
     */
    public function getCardSkills(): Collection
    {
        return $this->cardSkills;
    }

    public function addCardSkill(CardSkill $cardSkill): self
    {
        if (!$this->cardSkills->contains($cardSkill)) {
            $this->cardSkills[] = $cardSkill;
            $cardSkill->setSkill($this);
        }

        return $this;
    }

    public function removeCardSkill(CardSkill $cardSkill): self
    {
        if ($this->cardSkills->removeElement($cardSkill)) {
            // set the owning side to null (unless already changed)
            if ($cardSkill->getSkill() === $this) {
                $cardSkill->setSkill(null);
            }
        }

        return $this;
    }
}
