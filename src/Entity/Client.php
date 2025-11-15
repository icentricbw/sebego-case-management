<?php

namespace App\Entity;

use App\Enum\ClientType;
use App\Enum\IdentificationType;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    use TimestampableEntity;
    use BlameableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(length: 255)]
    private ?string $identificationNumber = null;

    #[ORM\Column(enumType: ClientType::class)]
    private ?ClientType $clientType = null;

    #[ORM\Column(enumType: IdentificationType::class)]
    private ?IdentificationType $identificationType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $companyName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $registrationNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $authorizedRepresentativeName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $authorizedRepresentativePhone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $authorizedRepresentativeEmail = null;

    #[ORM\Column(length: 255)]
    private ?string $primaryPhone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $secondaryPhone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $residentialAddress = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $postalAddress = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $physicalAddress = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    /**
     * @var Collection<int, MatterClient>
     */
    #[ORM\OneToMany(targetEntity: MatterClient::class, mappedBy: 'client')]
    private Collection $matterClients;

    /**
     * @var Collection<int, Document>
     */
    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: 'client')]
    private Collection $documents;

    /**
     * @var Collection<int, CommunicationLog>
     */
    #[ORM\OneToMany(targetEntity: CommunicationLog::class, mappedBy: 'client')]
    private Collection $communicationLogs;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->matterClients = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->communicationLogs = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getIdentificationNumber(): ?string
    {
        return $this->identificationNumber;
    }

    public function setIdentificationNumber(string $identificationNumber): static
    {
        $this->identificationNumber = $identificationNumber;

        return $this;
    }

    public function getClientType(): ?ClientType
    {
        return $this->clientType;
    }

    public function setClientType(ClientType $clientType): static
    {
        $this->clientType = $clientType;

        return $this;
    }

    public function getIdentificationType(): ?IdentificationType
    {
        return $this->identificationType;
    }

    public function setIdentificationType(IdentificationType $identificationType): static
    {
        $this->identificationType = $identificationType;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber(?string $registrationNumber): static
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    public function getAuthorizedRepresentativeName(): ?string
    {
        return $this->authorizedRepresentativeName;
    }

    public function setAuthorizedRepresentativeName(?string $authorizedRepresentativeName): static
    {
        $this->authorizedRepresentativeName = $authorizedRepresentativeName;

        return $this;
    }

    public function getAuthorizedRepresentativePhone(): ?string
    {
        return $this->authorizedRepresentativePhone;
    }

    public function setAuthorizedRepresentativePhone(?string $authorizedRepresentativePhone): static
    {
        $this->authorizedRepresentativePhone = $authorizedRepresentativePhone;

        return $this;
    }

    public function getAuthorizedRepresentativeEmail(): ?string
    {
        return $this->authorizedRepresentativeEmail;
    }

    public function setAuthorizedRepresentativeEmail(?string $authorizedRepresentativeEmail): static
    {
        $this->authorizedRepresentativeEmail = $authorizedRepresentativeEmail;

        return $this;
    }

    public function getPrimaryPhone(): ?string
    {
        return $this->primaryPhone;
    }

    public function setPrimaryPhone(string $primaryPhone): static
    {
        $this->primaryPhone = $primaryPhone;

        return $this;
    }

    public function getSecondaryPhone(): ?string
    {
        return $this->secondaryPhone;
    }

    public function setSecondaryPhone(?string $secondaryPhone): static
    {
        $this->secondaryPhone = $secondaryPhone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getResidentialAddress(): ?string
    {
        return $this->residentialAddress;
    }

    public function setResidentialAddress(?string $residentialAddress): static
    {
        $this->residentialAddress = $residentialAddress;

        return $this;
    }

    public function getPostalAddress(): ?string
    {
        return $this->postalAddress;
    }

    public function setPostalAddress(?string $postalAddress): static
    {
        $this->postalAddress = $postalAddress;

        return $this;
    }

    public function getPhysicalAddress(): ?string
    {
        return $this->physicalAddress;
    }

    public function setPhysicalAddress(?string $physicalAddress): static
    {
        $this->physicalAddress = $physicalAddress;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

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
            $matterClient->setClient($this);
        }

        return $this;
    }

    public function removeMatterClient(MatterClient $matterClient): static
    {
        if ($this->matterClients->removeElement($matterClient)) {
            // set the owning side to null (unless already changed)
            if ($matterClient->getClient() === $this) {
                $matterClient->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setClient($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getClient() === $this) {
                $document->setClient(null);
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
            $communicationLog->setClient($this);
        }

        return $this;
    }

    public function removeCommunicationLog(CommunicationLog $communicationLog): static
    {
        if ($this->communicationLogs->removeElement($communicationLog)) {
            // set the owning side to null (unless already changed)
            if ($communicationLog->getClient() === $this) {
                $communicationLog->setClient(null);
            }
        }

        return $this;
    }
}
