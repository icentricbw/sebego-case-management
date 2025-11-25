<?php

namespace App\Entity;

use App\Enum\LocationType;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    use TimestampableEntity;
    use BlameableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: LocationType::class)]
    private ?LocationType $locationType = null;

    /**
     * @var Collection<int, FileMovement>
     */
    #[ORM\OneToMany(targetEntity: FileMovement::class, mappedBy: 'fromLocation')]
    private Collection $movementsFrom;

    /**
     * @var Collection<int, FileMovement>
     */
    #[ORM\OneToMany(targetEntity: FileMovement::class, mappedBy: 'toLocation')]
    private Collection $movementsTo;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->movementsFrom = new ArrayCollection();
        $this->movementsTo = new ArrayCollection();
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

    public function getLocationType(): ?LocationType
    {
        return $this->locationType;
    }

    public function setLocationType(?LocationType $locationType): void
    {
        $this->locationType = $locationType;
    }

    /**
     * @return Collection<int, FileMovement>
     */
    public function getMovementsFrom(): Collection
    {
        return $this->movementsFrom;
    }

    public function addMovementsFrom(FileMovement $movementsFrom): static
    {
        if (!$this->movementsFrom->contains($movementsFrom)) {
            $this->movementsFrom->add($movementsFrom);
            $movementsFrom->setFromLocation($this);
        }

        return $this;
    }

    public function removeMovementsFrom(FileMovement $movementsFrom): static
    {
        if ($this->movementsFrom->removeElement($movementsFrom)) {
            // set the owning side to null (unless already changed)
            if ($movementsFrom->getFromLocation() === $this) {
                $movementsFrom->setFromLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FileMovement>
     */
    public function getMovementsTo(): Collection
    {
        return $this->movementsTo;
    }

    public function addMovementsTo(FileMovement $movementsTo): static
    {
        if (!$this->movementsTo->contains($movementsTo)) {
            $this->movementsTo->add($movementsTo);
            $movementsTo->setToLocation($this);
        }

        return $this;
    }

    public function removeMovementsTo(FileMovement $movementsTo): static
    {
        if ($this->movementsTo->removeElement($movementsTo)) {
            // set the owning side to null (unless already changed)
            if ($movementsTo->getToLocation() === $this) {
                $movementsTo->setToLocation(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name.' '.$this->locationType->name;
    }
}
