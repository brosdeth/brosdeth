<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemStock extends Model
{
    use SoftDeletes;
    protected $table = 'item_stocks';
    protected $fillable = [
        'parent_id','item_id','unit_name','units','barcode','code','price','cost','attrs','stock_qty','is_expired','divide','item_split_id','have_attr','item_split_key'
    ];
    protected $casts = [
        'attrs' => 'array',
        'units' => 'array',
    ];
    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }
    public function balanceStock()
    {
        return $this->hasOne(StockBalance::class,'stock_id','id');
    }

    public function balanceStockParent()
    {
        return $this->hasOne(StockBalance::class,'stock_id','parent_id');
    }

    public function discounts()
    {
        return $this->hasMany(DiscountDetail::class,'stock_id');
    }
}
