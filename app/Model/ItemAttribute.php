<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemAttribute extends Model
{
    use SoftDeletes;
    protected $table = 'item_attributes';
    protected $fillable = [
        'item_id','attr_name','attr_value'
    ];
    protected $casts = [
        'attr_value' => 'array'
    ];
}
