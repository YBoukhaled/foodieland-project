<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Category
{

    public function index()
    {
        return response()->json(\App\Models\Category::all());
    }
}
