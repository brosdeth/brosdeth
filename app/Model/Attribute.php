<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Attribute extends Model
{
    use SoftDeletes, LogsActivity;
    protected $table = 'attributes';
    protected $fillable = [
        'attr_name', 'description'
    ];
    protected static $logAttributes = [
        'attr_name', 'description'
    ];
    protected static $recordEvents = ['deleted', 'created', 'updated'];
    protected static $logName = 'Attribute Model';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return "This Attribute has been {$eventName}";
    }
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->ip = request()->ip();
        $activity->agent = request()->header('attribute-agent');
    }
}
