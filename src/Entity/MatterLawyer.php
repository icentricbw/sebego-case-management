<?php

namespace App\Entity;

use App\Enum\RoleType;
use App\Repository\MatterLawyerRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MatterLawyerRepository::class)]
class MatterLawyer
{
    use BlameableEntity;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'matterLawyers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Matter $matter = null;

    #[ORM\ManyToOne(inversedBy: 'matterLawyers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $lawyer = null;

    #[ORM\Column(enumType: RoleType::class)]
    private ?RoleType $roleType = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $unassignedAt = null;

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

    public function getLawyer(): ?User
    {
        return $this->lawyer;
    }

    public function setLawyer(?User $lawyer): static
    {
        $this->lawyer = $lawyer;

        return $this;
    }

    public function getRoleType(): ?RoleType
    {
        return $this->roleType;
    }

    public function setRoleType(RoleType $roleType): static
    {
        $this->roleType = $roleType;

        return $this;
    }

    public function getUnassignedAt(): ?\DateTimeImmutable
    {
        return $this->unassignedAt;
    }

    public function setUnassignedAt(?\DateTimeImmutable $unassignedAt): static
    {
        $this->unassignedAt = $unassignedAt;

        return $this;
    }
}
