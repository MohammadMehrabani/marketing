<?php

namespace App\DTO;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\ValidatedInput;

class ProductDto
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?UploadedFile $image, // $request->file()
        public readonly ?string $imageName,
        public readonly ?string $url,
        public readonly ?int $viewCount,
        public readonly ?int $merchantId
    ) {}

    public static function fromRequest(Request|ValidatedInput $request)
    {
        return new self(
            $request->id ?? null,
            $request->title ?? null,
            $request->description ?? null,
            $request->image ?? null,
            $request->imageName ?? null,
            $request->url ?? null,
            $request->viewCount ?? null,
            $request->merchantId ?? null
        );
    }

    public static function fromArray(array $array)
    {
        return new self(
            $array['id'] ?? null,
            $array['title'] ?? null,
            $array['description'] ?? null,
            $array['image'] ?? null,
            $array['imageName'] ?? null,
            $array['url'] ?? null,
            $array['viewCount'] ?? null,
            $array['merchantId'] ?? null
        );
    }
}
