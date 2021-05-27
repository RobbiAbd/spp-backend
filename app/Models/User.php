<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'is_active', 'level_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function rules()
    {
        return [
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',
            'username' => 'required|unique:users|max:200',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'level_id' => 'required|numeric',
            'is_active' => 'required|numeric',
        ];
    }

    public function levelRelationship()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }
}
