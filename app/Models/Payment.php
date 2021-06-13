<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = 'id';
    protected $fillable = [
        'nominal', 'periode_start', 'periode_end', 'user_id', 'student_id', 'payment_type_id'
    ];

    public function rules()
    {
        return [
            'nominal' => 'required|numeric',
            'periode_start' => 'required|date_format:Y:m:d|before:periode_end',
            'periode_end' => 'required|date_format:Y:m:d|after:periode_start',
            'user_id' => 'required|numeric',
            'student_id' => 'required|numeric',
            'payment_type_id' => 'required|numeric',
        ];
    }
}
