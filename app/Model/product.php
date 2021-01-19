<?php

namespace App\Model;
use App\Model\review;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    public function reviews()
    {
        return $this->hasMany(review::class);
    }
}
