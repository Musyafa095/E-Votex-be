<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Vote extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'votes';
    protected $fillable = ['vote', 'user_id', 'candidate_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}