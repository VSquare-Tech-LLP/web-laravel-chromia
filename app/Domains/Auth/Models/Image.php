<?php

//namespace App\Models;
namespace App\Domains\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\Flux\Models\Category;

class Image extends Model
{
    use HasFactory;

    protected $table = 'photos';

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
