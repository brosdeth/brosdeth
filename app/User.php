<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
  use HasRoles, SoftDeletes, LogsActivity;
  // -1001550797537
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */


  protected $fillable = [
    'name', 'email', 'password', 'type', 'is_active', 'contact_number', 'address', 'desc'
  ];

  protected static $logAttributes = [
    'name', 'email', 'password', 'type', 'is_active', 'contact_number', 'address', 'desc'
  ];
  protected static $recordEvents = ['deleted', 'created', 'updated'];
  protected static $logName = 'User Model';
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
  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  public function scopeSearch($query, $searchText)
    {
        if(isset($searchText)){
            $query->where("name","LIKE","$searchText%");
            $query->orWhere("email","LIKE","$searchText%");
            $query->orWhere("contact_number","LIKE","$searchText%");
            $query->orWhereHas("roles",function($q) use($searchText){
                $q->where("name","LIKE","$searchText%");
            });
        }
        return $query;
    }

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];
  public function getAllPermissionsAttribute()
  {
    $permissions = [];
    foreach (Permission::all() as $permission) {
      if (Auth::user()->can($permission->name)) {
        $permissions[] = $permission->name;
      }
    }
    return $permissions;
  }
}
