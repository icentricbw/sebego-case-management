<?php

namespace App\Entity;

use App\Enum\ClientRole;
use App\Repository\MatterClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MatterClientRepository::class)]
class MatterClient
{
    use TimestampableEntity;
    use BlameableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'matterClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Matter $matter = null;

    #[ORM\ManyToOne(inversedBy: 'matterClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\Column(enumType: ClientRole::class)]
    private ?ClientRole $clientRole = null;

    public function __construct()
    {
        $this->id = Uuid::v7();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getMatter(): ?Matter
    {
        return $this->matter;
    }

    public function setMatter(?Matter $matter): static
    {
        $this->matter = $matter;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getClientRole(): ?ClientRole
    {
        return $this->clientRole;
    }

    public function setClientRole(ClientRole $clientRole): static
    {
        $this->clientRole = $clientRole;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s - %s',
            $this->client?->getFullName() ?? $this->client?->getCompanyName() ?? 'Unknown Client',
            $this->clientRole?->value ?? 'No Role'
        );
    }
}
