<?php

namespace App\Model;

class MerchItem
{
    public function __construct(
        private int $id,
        private string $name,
        private string $description,
        private int $price,
        private int $quantity,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
