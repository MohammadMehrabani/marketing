<?php

namespace App\Contracts;

use App\DTO\MarketerProductDto;

interface RedirectorServiceInterface
{
    public function redirect(MarketerProductDto $marketerProductDto);
}
