<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogQuery extends Model
{
    use HasFactory;
    protected $table = 'log_query';
    public $timestamps = false;


}
