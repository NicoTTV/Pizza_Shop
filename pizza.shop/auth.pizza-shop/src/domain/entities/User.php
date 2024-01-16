<?php

namespace pizzashop\auth\api\domain\entities;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $connection = 'auth';
    protected $table = 'users';
    protected $primaryKey = 'email';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = ['email', 'password', 'active', 'activation_token', 'activation_token_expiration_date', 'refresh_token', 'refresh_token_expiration_date', 'reset_passwd_token', 'reset_passwd_token_expiration_date', 'username'];
}