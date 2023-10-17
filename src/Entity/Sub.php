<?php

namespace App\Entity;

use App\Repository\SubRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SubRepository::class)]
class Sub implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\ManyToOne(inversedBy: 'sub')]
    private ?Pay $pay = null;


    public function jsonSerialize(): array
    {
        $returnValue = [
            "type" => "sub",
            "Id" => $this->getId(),
            "attributes" => [
                "Name" => $this->name,
                "StartDate" => $this->getStartDate(),
            ],
            "links" => [
                //TODO Router nutzen für Link
                "self" => "/sub/" . $this->getId()
            ],
        ];

        if ($this->getPay()) {
            $this->addPayRelation($returnValue);
        }

        return $returnValue;
    }

    private function addPayRelation(array &$returnValue): void
    {
        $returnValue["relationships"] = [
            "pay" => [
                "links" => [
                    "self" => "",
                    "related" => "/payType/" . $this->getPay()->getId(),
                ]
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getPay(): ?Pay
    {
        return $this->pay;
    }

    public function setPay(?Pay $pay): static
    {
        $this->pay = $pay;

        return $this;
    }
}
