<?php

namespace App\Models;

use App\DTO\ProductDto;
use App\Traits\SharedBetweenModels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory, SharedBetweenModels, SoftDeletes;

    protected $fillable = [
        'title',
        'url',
        'image',
        'description',
        'view_count',
        'merchant_id'
    ];

    protected $orderableColumns = [
        'id'
    ];

    public function scopeFilter($query, ProductDto $arguments)
    {
        if ($arguments->title)
            $query->where('title', 'LIKE', '%' . $arguments->title . '%');
        if ($arguments->merchantId)
            $query->where('merchant_id', $arguments->merchantId);

        return $query;
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => !empty($value)
                                        ? asset('uploads/product/'.$this->id.'/'.$value)
                                        : null,
        );
    }
}
