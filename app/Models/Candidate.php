<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;


class Candidate extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'candidates';
    protected $fillable = ['name', 'NIM', 'visi', 'misi', 'image' , 'category_id', ];

   public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function vote()
    {
        return $this->hasMany(Vote::class, 'candidate_id');
    }

}
