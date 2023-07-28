<?php

namespace App\Models;

use App\DTO\MarketerProductDto;
use App\Traits\SharedBetweenModels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketerProduct extends Model
{
    use HasFactory, SharedBetweenModels, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'marketer_id',
        'product_id',
        'view_count',
        'creation_date'
    ];

    protected $orderableColumns = [
        'id'
    ];

    public function scopeFilter($query, MarketerProductDto $arguments)
    {
        if ($arguments->fromDate)
            $query->whereBetween('creation_date', [$arguments->fromDate, $arguments->toDate]);
        if ($arguments->marketerId)
            $query->where('marketer_id', $arguments->marketerId);

        return $query;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
