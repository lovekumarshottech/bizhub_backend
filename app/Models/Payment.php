<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  use HasFactory;

  protected $fillable = [
    'from_user_id',
    'to_user_id',
    'service_id',
    'transaction_id',
    'amount',
  ];
  
  public function getCreatedAtAttribute($date)
    {
        if (!empty($date))
            return Carbon::parse($date)->diffForHumans();
    }

  public function from()
  {
    return $this->belongsTo(User::class, 'from_user_id');
  }

  public function to()
  {
    return $this->belongsTo(User::class, 'to_user_id');
  }

     public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
