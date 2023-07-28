<?php

namespace App\DTO;

class ProductDto
{
    public function __construct(
        public $title,
        public $description,
        public $image, // $request->file()
        public $imageName,
        public $url,
        public $viewCount,
        public $merchantId
    ) {
    }

    public static function fromArray(array $array)
    {
        return new self(
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
