<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFeedback extends Model
{
    use HasFactory;
    protected $table="user_feedbacks";
    protected $fillable = ['review_id', 'card_question_id', 'quantity'];
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function cardQuestion()
    {
        return $this->belongsTo(CardQuestion::class);
    }
}
