<?php

namespace App\Entity;

use App\Entity\Skill;
use App\Entity\Mission;
use App\Entity\Experience;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['collab_list'])]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['info_user', 'collab_list'])]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['info_user', 'collab_list'])]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['info_user', 'collab_list'])]
    private $firstName;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['info_user', 'collab_list'])]
    private $status;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['info_user'])]
    private $is_admin;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['info_user'])]
    private $is_collab;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['info_user'])]
    private $is_commercial;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Experience::class)]
    // #[Groups(['collab_list'])]
    private $experiences;

    #[ORM\ManyToMany(targetEntity: Skill::class, mappedBy: 'user')]
    #[Groups(['info_user', 'collab_list'])]
    private $skills;

    #[ORM\ManyToMany(targetEntity: Mission::class, mappedBy: 'user')]
    #[Groups(['info_user', 'collab_list'])]
    private $mission;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CardSkill::class, orphanRemoval: true)]
    private $cardSkills;

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->mission = new ArrayCollection();
        $this->cardSkills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function isIsAdmin(): ?bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): self
    {
        $this->is_admin = $is_admin;

        return $this;
    }

    public function isIsCollab(): ?bool
    {
        return $this->is_collab;
    }

    public function setIsCollab(bool $is_collab): self
    {
        $this->is_collab = $is_collab;

        return $this;
    }

    public function isIsCommercial(): ?bool
    {
        return $this->is_commercial;
    }

    public function setIsCommercial(bool $is_commercial): self
    {
        $this->is_commercial = $is_commercial;

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->setUser($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
            $skill->addUser($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            $skill->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMission(): Collection
    {
        return $this->mission;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->mission->contains($mission)) {
            $this->mission[] = $mission;
            $mission->addUser($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->mission->removeElement($mission)) {
            $mission->removeUser($this);
        }

        return $this;
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
            $cardSkill->setUser($this);
        }

        return $this;
    }

    public function removeCardSkill(CardSkill $cardSkill): self
    {
        if ($this->cardSkills->removeElement($cardSkill)) {
            // set the owning side to null (unless already changed)
            if ($cardSkill->getUser() === $this) {
                $cardSkill->setUser(null);
            }
        }

        return $this;
    }
}
