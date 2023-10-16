<?php

namespace App\Entity;

use App\Repository\PayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PayRepository::class)]
class Pay implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'pay', targetEntity: sub::class)]
    private Collection $sub;

    public function __construct()
    {
        $this->sub = new ArrayCollection();
    }

    public function jsonSerialize(): array
    {
        return [
            "type" => "pay",
            "id" => $this->getId(),
            "attributes" => [
                "name" => $this->name,
                "description" => $this->description ?? "",
            ]
        ];
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, sub>
     */
    public function getSub(): Collection
    {
        return $this->sub;
    }

    public function addSub(sub $sub): static
    {
        if (!$this->sub->contains($sub)) {
            $this->sub->add($sub);
            $sub->setPay($this);
        }

        return $this;
    }

    public function removeSub(sub $sub): static
    {
        if ($this->sub->removeElement($sub)) {
            // set the owning side to null (unless already changed)
            if ($sub->getPay() === $this) {
                $sub->setPay(null);
            }
        }

        return $this;
    }
}
