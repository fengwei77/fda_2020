<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Prize extends Model
{
    use SoftDeletes;
    protected $stable = 'prizes';
    //設定主鍵
    public $primaryKey = 'id';
    protected $fillable = [
        'id',
        'uuid',
        'event_id',
        'lottery_list',
        'name',
        'desc',
        'files',
        'qty',
        'usage_sum',
        'day_limit',
        'pbb',
        'start_date',
        'expire_date',
        'has_draw',
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
