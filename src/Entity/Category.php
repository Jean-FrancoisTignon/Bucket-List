<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Wish::class, orphanRemoval: true, cascade: ['remove'])]
    private Collection $wishs;

    public function __construct()
    {
        $this->wishs = new ArrayCollection();
    }

    public function getId(): ?int
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
     * @return Collection<int, Wish>
     */
    public function getWishs(): Collection
    {
        return $this->wishs;
    }

    public function addWish(Wish $wish): static
    {
        if (!$this->wishs->contains($wish)) {
            $this->wishs->add($wish);
            $wish->setCategory($this);
        }

        return $this;
    }

    public function removeWish(Wish $wish): static
    {
        if ($this->wishs->removeElement($wish)) {
            // set the owning side to null (unless already changed)
            if ($wish->getCategory() === $this) {
                $wish->setCategory(null);
            }
        }

        return $this;
    }
}
