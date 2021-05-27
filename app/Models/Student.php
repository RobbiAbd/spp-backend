<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nisn', 'name', 'address', 'class_id'
    ];

    public function classRelationship()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'id');
    }
}
