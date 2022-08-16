<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 * @property mixed $account_id
 * @property mixed $comment_id
 */
class Vote extends Model
{
    use HasFactory;
    protected $table = 'vote';
    protected $fillable = ['account_id', 'comment_id', 'create_at'];
    public $timestamps = false;
    protected $primaryKey =['account_id', 'comment_id'];

    public function owner() {
        return $this->hasOne(User::class, 'id', 'account_id');
    }
}
