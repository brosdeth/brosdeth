<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyInfor extends Model
{
    use SoftDeletes;
    protected $table = 'company_infors';
    protected $fillable = [
        'name_en','name_km', 'contact_number', 'image', 'email', 'address', 'website'
    ];
}
