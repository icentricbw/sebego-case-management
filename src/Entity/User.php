<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Matter>
     */
    #[ORM\OneToMany(targetEntity: Matter::class, mappedBy: 'leadLawyer')]
    private Collection $matters;

    /**
     * @var Collection<int, MatterLawyer>
     */
    #[ORM\OneToMany(targetEntity: MatterLawyer::class, mappedBy: 'lawyer')]
    private Collection $matterLawyers;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'assignedTo')]
    private Collection $tasks;

    /**
     * @var Collection<int, FileMovement>
     */
    #[ORM\OneToMany(targetEntity: FileMovement::class, mappedBy: 'fromUser')]
    private Collection $fileMovements;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?bool $isActive = null;


    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->matters = new ArrayCollection();
        $this->matterLawyers = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->fileMovements = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    /**
     * @return Collection<int, Matter>
     */
    public function getMatters(): Collection
    {
        return $this->matters;
    }

    public function addMatter(Matter $matter): static
    {
        if (!$this->matters->contains($matter)) {
            $this->matters->add($matter);
            $matter->setLeadLawyer($this);
        }

        return $this;
    }

    public function removeMatter(Matter $matter): static
    {
        if ($this->matters->removeElement($matter)) {
            // set the owning side to null (unless already changed)
            if ($matter->getLeadLawyer() === $this) {
                $matter->setLeadLawyer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MatterLawyer>
     */
    public function getMatterLawyers(): Collection
    {
        return $this->matterLawyers;
    }

    public function addMatterLawyer(MatterLawyer $matterLawyer): static
    {
        if (!$this->matterLawyers->contains($matterLawyer)) {
            $this->matterLawyers->add($matterLawyer);
            $matterLawyer->setLawyer($this);
        }

        return $this;
    }

    public function removeMatterLawyer(MatterLawyer $matterLawyer): static
    {
        if ($this->matterLawyers->removeElement($matterLawyer)) {
            // set the owning side to null (unless already changed)
            if ($matterLawyer->getLawyer() === $this) {
                $matterLawyer->setLawyer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setAssignedTo($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getAssignedTo() === $this) {
                $task->setAssignedTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FileMovement>
     */
    public function getFileMovements(): Collection
    {
        return $this->fileMovements;
    }

    public function addFileMovement(FileMovement $fileMovement): static
    {
        if (!$this->fileMovements->contains($fileMovement)) {
            $this->fileMovements->add($fileMovement);
            $fileMovement->setFromUser($this);
        }

        return $this;
    }

    public function removeFileMovement(FileMovement $fileMovement): static
    {
        if ($this->fileMovements->removeElement($fileMovement)) {
            // set the owning side to null (unless already changed)
            if ($fileMovement->getFromUser() === $this) {
                $fileMovement->setFromUser(null);
            }
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function __toString(): string
    {
        return $this->firstName.' '.$this->lastName.' '.$this->email;
    }
}
