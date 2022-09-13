<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @property mixed $content
 * @property mixed $account_id
 * @property mixed $title
 * @property mixed $create_at
 * @method static search(array|string|null $query)
 */
class Blog extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'blog';
    protected $hidden = ['account_id'];
    protected $fillable = ['content', 'account_id', 'title', 'create_at'];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'blog_id', 'id')->where("reply_to", null)->with('owner')->with("reply");
    }

    public function author(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Account::class, 'id', 'id', 'account_id', 'user_id');
    }

    public function scopeSearch($query, $pureFilter)
    {
        $filter = array_filter($pureFilter, function ($key) {
            $filter_only = ['id', 'account_id', 'content', 'title', 'startDate', 'endDate'];
            return array_search($key, $filter_only) !== -1;
        }, ARRAY_FILTER_USE_KEY);

        if (isset($filter['id'])) {
            $query->where("id", $filter['id']);
            unset($filter['id']);
        }
        if (isset($filter['account_id'])) {
            $query->where("account_id", $filter['account_id']);
            unset($filter['account_id']);
        }

        if (isset($filter['startDate'])) {
            $query->whereDate("create_at", ">=", date('Y-m-d', strtotime($filter['startDate'])));
            unset($filter['startDate']);
        }
        if (isset($filter['endDate'])) {
            $query->whereDate("create_at", "<=", date('Y-m-d', strtotime($filter['endDate'])));
            unset($filter['endDate']);
        }
        foreach ($filter as $key => $value) {
            $query->where($key, 'LIKE', '%' . $value . '%');
        }
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'blog_category', 'blog_id', 'category_id', 'id', 'id')->distinct();
    }

    public function scopeAvgRate($query)
    {
        return $query->selectRaw("blog.*, avg(rate) as avgRate")
            ->leftJoin("rate", "rate.blog_id", "=", "blog.id")
            ->groupBy("id");
    }
}
