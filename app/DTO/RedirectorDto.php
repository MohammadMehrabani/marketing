<?php

namespace App\DTO;

class RedirectorDto
{
    public function __construct(
        public $marketerId,
        public $productId,
    ) {
    }

    public static function fromArray(array $array)
    {
        return new self(
            $array['marketer'] ?? null,
            $array['product'] ?? null,
        );
    }
}
