<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comment';
    protected $hidden = ['account_id', 'blog_id', 'reply_to'];
    protected $fillable = ['content', 'blog_id', 'reply_to', 'create_at', 'account_id'];

    public $timestamps = false;

    public  function owner() {
        return $this->hasOne(User::class, 'id', 'account_id')->select(['id', 'firstName', 'lastName']);
    }

    public function reply() {
        return $this->hasMany(Comment::class, "reply_to", 'id')->with('owner');
    }
}
