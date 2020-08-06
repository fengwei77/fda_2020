<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Quickly_inquiry extends Model
{
    use SoftDeletes;
    use \Rutorika\Sortable\SortableTrait;

    protected $stable = 'quickly_inquiries';
    //設定主鍵
    public $primaryKey = 'id';
    protected static $sortableField = 'item_date';
    protected $fillable = [
        'id',
        'name',
        'phone',
        'email',
        'company',
        'category',
        'box_type',
        'size',
        'reply_member',
        'reply_content',
        'reply_date',
        'updated_at',
        'created_at'
    ];

    protected $dates = ['deleted_at'];


}
