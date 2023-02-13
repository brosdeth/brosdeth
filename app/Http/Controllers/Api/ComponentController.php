<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Categories;
use App\User;
use Spatie\Permission\Models\Role;

class ComponentController extends Controller
{
    public function comUser()
    {
        return User::get();
    }
    public function comCategories()
    {
        return Categories::get();
    }
    public function comRole()
    {
        return Role::get();
    }

}
