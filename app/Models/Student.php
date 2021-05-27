<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = 'id';
    protected $fillable = [
        'nisn', 'name', 'address', 'class_id'
    ];

    public function rules()
    {
        return [
            'nisn' => 'required|numeric|unique:students',
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',
            'address' => 'required|max:200',
            'class_id' => 'required|numeric',
        ];
    }

    public function classRelationship()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'id');
    }
}
