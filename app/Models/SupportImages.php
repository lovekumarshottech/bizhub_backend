<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Support;

class SupportImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_id',
        'file_name',
    ];
    public function support()
    {
        return $this->belongsTo(Support::class);
    }
}
