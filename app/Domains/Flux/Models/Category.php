<?php

namespace App\Domains\Flux\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
