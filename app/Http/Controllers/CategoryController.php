<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('created_by', $request->user()->id)->get();

        return inertia('Category/Index',
            [
                'categories' => $categories
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Category::create([
            'name' => $request->name,
            'created_by' => $request->user()->id,
        ]);

        return back()->with(['success' => 'Category created successfully']);
    }

    public function getMyCategory(Request $request)
    {
        $categories = Category::where('created_by', $request->user()->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
