<?php

namespace App\Entity;

use App\Repository\CaseTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CaseTypeRepository::class)]
class CaseType
{
    use TimestampableEntity;
    use BlameableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Matter>
     */
    #[ORM\OneToMany(targetEntity: Matter::class, mappedBy: 'caseType')]
    private Collection $matters;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->matters = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
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
            $matter->setCaseType($this);
        }

        return $this;
    }

    public function removeMatter(Matter $matter): static
    {
        if ($this->matters->removeElement($matter)) {
            // set the owning side to null (unless already changed)
            if ($matter->getCaseType() === $this) {
                $matter->setCaseType(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
