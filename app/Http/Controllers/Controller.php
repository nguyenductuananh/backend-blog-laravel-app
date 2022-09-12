<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function getService()
    {
        return $this->service;
    }

    public function formatJson($data = [], $statusCode = HttpStatusCode::SUCCESS)
    {
        return response()->json(response_format_data($data, $statusCode), $statusCode);
    }
}
