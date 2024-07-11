<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardQuestion extends Model
{
    use HasFactory;
    protected $table="card_questions";

    public function userFeedbacks()
    {
        return $this->hasOne(UserFeedback::class);
    }
}
