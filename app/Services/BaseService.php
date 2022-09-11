<?php

namespace App\Services;

use App\Enums\HttpStatusCode;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NotFoundException;
use Exception;


class BaseService
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    function model()
    {
        return $this->model;
    }

    function all()
    {
        return $this->model::all();
    }

    function getOne(...$args)
    {

        if (empty($args)) {
            return null;
        }
        if (count($args) !== 1) {
            $primaryKeyName = array_column_default($args, 0,  "");
            $primaryKeyValue = array_column_default($args, 1, null);
            if (!boolval($primaryKeyName) || !boolval($primaryKeyValue)) {
                return null;
            }
            return $this->model::where($primaryKeyName, $primaryKeyValue)->take(1)->get();
        } else {
            return $this->model::find((int) $args[0]);
        }
    }
    public function store($data = [])
    {
        $withTimeStamp = array_merge($data, ['account_id' => auth()->user()->id, 'create_at' => now()]);
        $filledDataModel = new $this->model($withTimeStamp);
        try {
            $filledDataModel->saveOrFail();
        } catch (\Throwable $t) {
            throw (new InternalErrorException($t));
        }
        return $filledDataModel;
    }
    public function update(int $id, array $updateField)
    {
        if (empty($id) ||  gettype($id) !== 'integer') {
            throw (new NotFoundException());
        }
        $needUpdateModel = $this->model::find($id);
        foreach ($updateField as $fieldName => $fieldValue) {
            $needUpdateModel[$fieldName] = $fieldValue;
        }
        try {
            $needUpdateModel->saveOrFail();
            return $needUpdateModel;
        } catch (\Throwable $th) {
            throw (new InternalErrorException($th));
        }
    }
}
