<?php

namespace App\Services;

use App\Enums\HttpStatusCode;
use App\Exceptions\InternalErrorException;
use App\Models\Rate;
use Throwable;

class RateService extends BaseService
{
    public function __construct(Rate $model)
    {
        $this->model = $model;
    }

    public function storeRate(array $store_data)
    {
        $rated = Rate::where('account_id', auth()->user()->id)->where('blog_id', array_column_default($store_data, 'blog_id', null))->get();

        if (boolval($rated->count())) {
            return false;
        }
        try {
            $this->store($store_data);
            return true;
        } catch (Throwable $th) {
            throw new InternalErrorException($th);
        }
    }

    public function deleteRate($blog_id)
    {
        try {
            $this->model::where('account_id', auth()->user()->id)->where("blog_id", $blog_id)->delete();
            return true;
        } catch (Throwable $th) {
            throw new InternalErrorException($th);
        }
    }
}
