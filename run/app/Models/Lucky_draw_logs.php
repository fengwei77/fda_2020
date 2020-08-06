<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Lucky_draw_logs extends Model
{
    use SoftDeletes;
    protected $stable = 'lucky_draw_logs';
    //設定主鍵
    public $primaryKey = 'id';
    protected $fillable = [
        'id',
        'player_uuid',
        'fb_id',
        'fb_name',
        'fb_email',
        'username',
        'phone',
        'email',
        'gender',
        'address',
        'invoice',
        'invoice_date',
        'invoice_code',
        'prize_uuid',
        'prize',
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
