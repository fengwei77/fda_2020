<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use SoftDeletes;
    protected $stable = 'players';
    //設定主鍵
    public $primaryKey = 'id';
    protected $fillable = [
        'id',
        'uuid',
        'fb_id',
        'fb_name',
        'fb_email',
        'username',
        'phone',
        'email',
        'gender',
        'country',
        'district',
        'address',
        'score',
        'timer',
        'prize_uuid',
        'prize',
        'win_status',
        'item_status',
        'has_deleted',
        'updated_at',
        'created_at'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

}
