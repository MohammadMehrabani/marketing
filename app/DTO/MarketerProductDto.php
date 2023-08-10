<?php

namespace App\DTO;

use Illuminate\Http\Request;
use Illuminate\Support\ValidatedInput;

class MarketerProductDto
{
    public function __construct(
        public readonly ?string $fromDate,
        public readonly ?string $toDate,
        public readonly ?string $marketerId,
        public readonly ?string $productId,
        public readonly ?string $creationDate,
    ) {}

    public static function fromRequest(Request|ValidatedInput $request)
    {
        return new self(
            $request->fromDate ?? null,
            $request->toDate ?? date('Y-m-d'),
            $request->marketerId ?? null,
            $request->productId ?? null,
            $request->creationDate ?? null,
        );
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
