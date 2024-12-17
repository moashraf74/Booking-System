<?php

namespace App\Models;

use App\Models\Business;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['business_id','name','price'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    
}
