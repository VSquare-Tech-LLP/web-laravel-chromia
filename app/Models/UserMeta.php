<?php

namespace App\Models;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'image',
        'short_bio',
        'long_bio',
    ];

    public function getImagePathAttribute()
    {
        if (! empty($this->image)) {
            return asset("storage/profile/" . $this->image);
        }

        return asset('img/user-icon.png');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
