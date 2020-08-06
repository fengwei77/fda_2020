<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class About extends Model
{
    use SoftDeletes;
    use \Rutorika\Sortable\SortableTrait;

    protected $stable = 'about';
    //設定主鍵
    public $primaryKey = 'id';
    protected static $sortableField = 'sequence_number';
    protected $fillable = [
        'id',
        'lang',
        'uuid',
        'title',
        'description',
        'content',
        'item_date',
        'images',
        'files',
        'hot',
        'view_count',
        'sequence_number',
        'verify',
        'item_status',
        'has_deleted',
        'updated_at',
        'created_at'
    ];

    protected $dates = ['deleted_at']; 

}
