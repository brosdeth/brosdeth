<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{
    use SoftDeletes, LogsActivity;
    protected $table = 'items';
    protected $fillable = [
        'category_id', 'item_code', 'barcode', 'item_name', 'price', 'cost', 'image', 'description', 'unit_name', 'is_expired'
    ];
    protected static $logAttributes = [
        'category_id', 'item_code', 'barcode', 'item_name', 'price', 'cost', 'image', 'description', 'unit_name', 'is_expired'
    ];
    protected static $recordEvents = ['deleted', 'created', 'updated'];
    protected static $logName = 'Item Model';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return "This user has been {$eventName}";
    }
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->ip = request()->ip();
        $activity->agent = request()->header('user-agent');
    }
    public function stock()
    {
        return $this->hasMany(ItemStock::class, 'item_id');
    }
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    public function itemAttribute()
    {
        return $this->hasMany(ItemAttribute::class, 'item_id');
    }
    public function itemAttributeStock()
    {
        return $this->hasMany(ItemStock::class, 'item_id')->where('have_attr',2);
    }
    
    public function itemSplit()
    {
        return $this->hasMany(ItemStock::class, 'item_id')->where('parent_id','<>',0)->groupBy('item_split_key');
    }
    // public function itemSplit()
    // {
    //     return $this->hasMany(ItemstockSplit::class, 'item_id');
    // }
}
