<?php

namespace App\Entity;

use App\Enum\StatusType;
use App\Repository\MatterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MatterRepository::class)]
class Matter
{
    use TimestampableEntity;
    use BlameableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(type: 'integer', unique: true)]
    private ?int $fileNumber = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'matters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CaseType $caseType = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $filingDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $closingDate = null;

    #[ORM\ManyToOne(inversedBy: 'matters')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $leadLawyer = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $secretary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(enumType: StatusType::class)]
    private ?StatusType $statusType = null;

    /**
     * @var Collection<int, MatterClient>
     */
    #[ORM\OneToMany(targetEntity: MatterClient::class, mappedBy: 'matter')]
    private Collection $matterClients;

    /**
     * @var Collection<int, MatterLawyer>
     */
    #[ORM\OneToMany(targetEntity: MatterLawyer::class, mappedBy: 'matter')]
    private Collection $matterLawyers;

    /**
     * @var Collection<int, MatterUpdate>
     */
    #[ORM\OneToMany(targetEntity: MatterUpdate::class, mappedBy: 'matter')]
    private Collection $matterUpdates;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'matter')]
    private Collection $tasks;

    /**
     * @var Collection<int, Archive>
     */
    #[ORM\OneToMany(targetEntity: Archive::class, mappedBy: 'matter')]
    private Collection $archives;

    /**
     * @var Collection<int, FileMovement>
     */
    #[ORM\OneToMany(targetEntity: FileMovement::class, mappedBy: 'matter')]
    private Collection $fileMovements;

    /**
     * @var Collection<int, CommunicationLog>
     */
    #[ORM\OneToMany(targetEntity: CommunicationLog::class, mappedBy: 'matter')]
    private Collection $communicationLogs;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->matterClients = new ArrayCollection();
        $this->matterLawyers = new ArrayCollection();
        $this->matterUpdates = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->archives = new ArrayCollection();
        $this->fileMovements = new ArrayCollection();
        $this->communicationLogs = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFileNumber(): ?string
    {
        return $this->fileNumber;
    }

    public function setFileNumber(string $fileNumber): static
    {
        $this->fileNumber = $fileNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCaseType(): ?CaseType
    {
        return $this->caseType;
    }

    public function setCaseType(?CaseType $caseType): static
    {
        $this->caseType = $caseType;

        return $this;
    }

    public function getFilingDate(): ?\DateTimeImmutable
    {
        return $this->filingDate;
    }

    public function setFilingDate(\DateTimeImmutable $filingDate): static
    {
        $this->filingDate = $filingDate;

        return $this;
    }

    public function getClosingDate(): ?\DateTimeImmutable
    {
        return $this->closingDate;
    }

    public function setClosingDate(?\DateTimeImmutable $closingDate): static
    {
        $this->closingDate = $closingDate;

        return $this;
    }

    public function getLeadLawyer(): ?User
    {
        return $this->leadLawyer;
    }

    public function setLeadLawyer(?User $leadLawyer): static
    {
        $this->leadLawyer = $leadLawyer;

        return $this;
    }

    public function getSecretary(): ?User
    {
        return $this->secretary;
    }

    public function setSecretary(?User $secretary): static
    {
        $this->secretary = $secretary;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getStatusType(): ?StatusType
    {
        return $this->statusType;
    }

    public function setStatusType(StatusType $statusType): static
    {
        $this->statusType = $statusType;

        return $this;
    }

    /**
     * @return Collection<int, MatterClient>
     */
    public function getMatterClients(): Collection
    {
        return $this->matterClients;
    }

    public function addMatterClient(MatterClient $matterClient): static
    {
        if (!$this->matterClients->contains($matterClient)) {
            $this->matterClients->add($matterClient);
            $matterClient->setMatter($this);
        }

        return $this;
    }

    public function removeMatterClient(MatterClient $matterClient): static
    {
        if ($this->matterClients->removeElement($matterClient)) {
            // set the owning side to null (unless already changed)
            if ($matterClient->getMatter() === $this) {
                $matterClient->setMatter(null);
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
            $matterLawyer->setMatter($this);
        }

        return $this;
    }

    public function removeMatterLawyer(MatterLawyer $matterLawyer): static
    {
        if ($this->matterLawyers->removeElement($matterLawyer)) {
            // set the owning side to null (unless already changed)
            if ($matterLawyer->getMatter() === $this) {
                $matterLawyer->setMatter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MatterUpdate>
     */
    public function getMatterUpdates(): Collection
    {
        return $this->matterUpdates;
    }

    public function addMatterUpdate(MatterUpdate $matterUpdate): static
    {
        if (!$this->matterUpdates->contains($matterUpdate)) {
            $this->matterUpdates->add($matterUpdate);
            $matterUpdate->setMatter($this);
        }

        return $this;
    }

    public function removeMatterUpdate(MatterUpdate $matterUpdate): static
    {
        if ($this->matterUpdates->removeElement($matterUpdate)) {
            // set the owning side to null (unless already changed)
            if ($matterUpdate->getMatter() === $this) {
                $matterUpdate->setMatter(null);
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
            $task->setMatter($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getMatter() === $this) {
                $task->setMatter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Archive>
     */
    public function getArchives(): Collection
    {
        return $this->archives;
    }

    public function addArchive(Archive $archive): static
    {
        if (!$this->archives->contains($archive)) {
            $this->archives->add($archive);
            $archive->setMatter($this);
        }

        return $this;
    }

    public function removeArchive(Archive $archive): static
    {
        if ($this->archives->removeElement($archive)) {
            // set the owning side to null (unless already changed)
            if ($archive->getMatter() === $this) {
                $archive->setMatter(null);
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
            $fileMovement->setMatter($this);
        }

        return $this;
    }

    public function removeFileMovement(FileMovement $fileMovement): static
    {
        if ($this->fileMovements->removeElement($fileMovement)) {
            // set the owning side to null (unless already changed)
            if ($fileMovement->getMatter() === $this) {
                $fileMovement->setMatter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommunicationLog>
     */
    public function getCommunicationLogs(): Collection
    {
        return $this->communicationLogs;
    }

    public function addCommunicationLog(CommunicationLog $communicationLog): static
    {
        if (!$this->communicationLogs->contains($communicationLog)) {
            $this->communicationLogs->add($communicationLog);
            $communicationLog->setMatter($this);
        }

        return $this;
    }

    public function removeCommunicationLog(CommunicationLog $communicationLog): static
    {
        if ($this->communicationLogs->removeElement($communicationLog)) {
            // set the owning side to null (unless already changed)
            if ($communicationLog->getMatter() === $this) {
                $communicationLog->setMatter(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->fileNumber.' '.$this->description;
    }
}
