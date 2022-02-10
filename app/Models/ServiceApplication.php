<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ServiceApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id ',
        'service_id',
        'amount',
        'description',
        'status',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function service() {
        return $this->belongsTo(Service::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class,'service_id','id')->whereNull('parent_id');
    }
    public function getCreatedAtAttribute($date) {
        if (!empty($date))
            return Carbon::parse($date)->diffForHumans();
    }
}
