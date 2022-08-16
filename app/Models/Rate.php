<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $fillable = ['rate', 'account_id', 'blog_id', 'create_at'];
    public $timestamps = false;
    protected $table = 'rate';
}
