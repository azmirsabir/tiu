<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table="reviews";
    protected $fillable=["u_id"];
    public function user()
    {
        return $this->belongsTo(User::class, 'u_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(UserFeedback::class);
    }

}
