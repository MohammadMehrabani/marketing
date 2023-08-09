<?php

namespace App\Traits;

trait SharedBetweenControllers
{
    private function perPage()
    {
        return request()->has('perPage') ? request()->get('perPage') : 15;
    }

    private function orderBy()
    {
        return request()->has('orderBy') ? request()->get('orderBy') : '';
    }
}
