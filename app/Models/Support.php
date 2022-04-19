<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SupportImages;
use App\Models\Service;
use Carbon\Carbon;

class Support extends Model
{
    const ACTIVE = 0;
    const CLOSED = 1;

    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'closed_description',
        'service_id',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function images()
    {
        return $this->hasMany(SupportImages::class);
    }

    public function getCreatedAtAttribute($date)
    {
        if (!empty($date))
            return Carbon::parse($date)->diffForHumans();
    }
}
