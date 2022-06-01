<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'user_id ',
        'category_id',
        'title',
        'description',
        'address',
        'latitude',
        'longitude',
        'amount',
        'is_negotiable',
        'start_date',
        'no_of_ppl',
        'status',
        'type',
        'image'
    ];


    protected $hidden = [
        // 'updated_at',
    ];

    public function getCreatedAtAttribute($date)
    {
        if (!empty($date))
            return Carbon::parse($date)->diffForHumans();
    }

    public function getAmountAttribute($value)
    {
        return "$" . $value;
    }

    public function scopeActive($query)
    {
        return $query->where('status', '0');
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(ServiceApplication::class);
    }

    public function application()
    {
        return $this->hasOne(ServiceApplication::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function support()
    {
        return $this->hasOne(Support::class);
    }
}
