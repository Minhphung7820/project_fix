<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categrory extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'slug',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'created_by',
        'created_at',
        'updated_at',
    ];
    use HasFactory;
}
