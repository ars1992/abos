<?php

namespace App\Model;

class Sub{

    protected string $name;
    protected \DateTime $startDate;

    public function toArray(): array
    {
        return [
            "name" => $this->name,
            "startDate" => $this->startDate,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setSartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }


}