<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $table="cards";

    public function questions(){
        return $this->hasMany(CardQuestion::class);
    }
}
