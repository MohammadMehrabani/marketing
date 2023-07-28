<?php

namespace App\DTO;

class MarketerProductDto
{
    public function __construct(
        public $fromDate,
        public $toDate,
        public $marketerId,
        public $productId,
        public $creationDate,
    ) {
    }

    public static function fromArray(array $array)
    {
        return new self(
            $array['fromDate'] ?? null,
            $array['toDate'] ?? date('Y-m-d'),
            $array['marketerId'] ?? null,
            $array['productId'] ?? null,
            $array['creationDate'] ?? null,
        );
    }
}
