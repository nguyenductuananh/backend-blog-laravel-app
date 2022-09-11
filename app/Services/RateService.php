<?php

namespace App\Services;

use App\Models\Rate;

class RateService extends BaseService
{
    public function __construct(Rate $model)
    {
        $this->model = $model;
    }
}
