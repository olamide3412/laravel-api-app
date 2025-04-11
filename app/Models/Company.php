<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'description',
        'contactEmail',
        'contactPhone',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function jobs(){
        return $this->hasMany(Job::class);
    }
}
