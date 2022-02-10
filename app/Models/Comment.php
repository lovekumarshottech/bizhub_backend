<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'user_id', 'parent_id', 'comment','is_active'];

    public function getCreatedAtAttribute($date) {
        if (!empty($date))
            return Carbon::parse($date)->diffForHumans();
    }
    /**
     * Write Your Code..
     *
     * @return string
    */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * Write Your Code..
     *
     * @return string
    */
    public function reply()
    {
        return $this->hasOne(Comment::class, 'parent_id');
    }

    public function application()
    {
        return $this->belongsTo(ServiceApplication::class);
    }
}
