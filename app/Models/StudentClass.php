<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    protected $guarded = 'id';
    protected $table = 'classes';
    protected $fillable = [
        'name',
    ];

    public function classRelationship()
    {
        return $this->hasOne(Student::class);
    }
}
