<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $query = Recipe::with('category');

        // Filter by category: GET /api/recipes?category_id=2
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string',
            'prep_time'   => 'required|integer',
            'cook_time'   => 'required|integer',
            'image_url'   => 'nullable|string',
            'category'    => 'required|string',
        ]);

        $categoryRecord = Category::where('name', $request->category)->first();

        if (!$categoryRecord) {
            return response()->json([
                'message' => 'The selected category name does not exist in the categories table.'
            ], 422);
        }

        $validatedData['category_id'] = $categoryRecord->id;
        $validatedData['category']    = $categoryRecord->name;

        $recipe = Recipe::create($validatedData);

        return response()->json($recipe->load('category'), 201);
    }

    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string',
            'prep_time'   => 'required|integer',
            'cook_time'   => 'required|integer',
            'image_url'   => 'nullable|string',
            'category'    => 'required|string',
        ]);

        $categoryRecord = Category::where('name', $request->category)->first();

        if (!$categoryRecord) {
            return response()->json(['message' => 'Invalid category'], 422);
        }

        $validatedData['category_id'] = $categoryRecord->id;
        $validatedData['category']    = $categoryRecord->name;

        $recipe->update($validatedData);

        return response()->json($recipe->load('category'));
    }

    public function show($id): JsonResponse
    {
        $recipe = Recipe::with('category')->find($id);

        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        return response()->json($recipe);
    }

    public function destroy($id)
    {
        Recipe::destroy($id);

        return response()->json(['message' => 'Recipe deleted successfully']);
    }

    public function search(Request $request)
    {
        $query = Recipe::with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . trim($request->search) . '%');
        }

        if ($request->filled('prep_time')) {
            $query->where('prep_time', (int) $request->prep_time);
        }

        return $query->get();
    }

    public function getCategories()
    {
        return response()->json(Category::all());
    }
}
