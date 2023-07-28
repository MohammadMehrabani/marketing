<?php

namespace App\Traits;

use App\Exceptions\ApiException;

trait SharedBetweenModels
{
    public function scopeCustomOrderBy($query, $orderBy)
    {
        if ($orderBy) {
            if (strstr($orderBy, ',')  && count(explode(',', $orderBy)) == 2) {
                $order = explode(',', $orderBy);
                if (!in_array($order[0], $this->orderableColumns)) {
                    throw new ApiException('can\'t sort by <'.$order[0].'> column, only using: '.implode(', ', $this->orderableColumns), 400);
                }
                if (!in_array($order[1], ['asc', 'desc', 'ASC', 'DESC'])) {
                    throw new ApiException('can\'t be used with <'.$order[1].'>, only using: asc, desc, ASC, DESC', 400);
                }
                $query->orderBy($order[0], $order[1]);
            }
        }

        return $query;
    }
}
