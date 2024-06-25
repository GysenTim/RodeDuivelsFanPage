<?php

namespace App\Repository;

use App\Model\MerchItem;
use Psr\Log\LoggerInterface;

class MerchRepository
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function findAll(): array
    {
        $this->logger->info('Getting merch collection');

        return [
            new MerchItem(
                1,
                'T-shirt',
                'Leuke t-shirt',
                100,
                12,
            ),
            new MerchItem(
                2,
                'Trui',
                'Lelijke trui',
                300,
                154,
            ),
            new MerchItem(
                3,
                'Pet',
                'Om de haarlijn te verbergen',
                12000,
                55,
            ),
        ];
    }

    public function find(int $id): ?MerchItem
    {
        foreach ($this->findAll() as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }

        return null;
    }
}
