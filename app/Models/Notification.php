<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    public $timestamps = false;
    protected $fillable = ['account_id', 'content', 'create_at', 'comment_id', 'blog_id'];
}
