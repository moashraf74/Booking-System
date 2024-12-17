<?php

namespace App\Models;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

protected $fillable =['user_id','service_id','rating','comment'];

public function user(){
    return $this->belongsTo(User::class);
}

public function service(){
    return $this->belongsTo(Service::class);
}


}
