<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $account_id
 * @property mixed $title
 */
class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $fillable = ['title', 'account_id'];
    protected $hidden = ['pivot'];
    public $timestamps = false;
}
