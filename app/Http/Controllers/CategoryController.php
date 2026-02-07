<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * List categories with search, sort, and pagination.
     */
    public function index(Request $request)
    {
        $query = Category::query();
        // Eager load the user
        $query->with('user');

        // Search by title or slug
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination or fetch all
        $all = filter_var($request->input('all', false), FILTER_VALIDATE_BOOLEAN);
        if ($all) {
            $categories = $query->get();
        } else {
            $categories = $query->paginate(10);
        }

        // Return wrapped response
        return response()->json([
            'success' => CategoryResource::collection($categories)->response()->getData(true),
            'message' => 'Categories are retrieved successfully',
        ]);
    }

    /**
     * Store a new category.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validatedData = $request->validated();
        $category = Category::create($validatedData);

        return response()->json([
            'success' => new CategoryResource($category),
            'message' => 'Categories are created successfully',
        ]);
    }

    /**
     * Show a single category.
     */
    public function show(Category $category)
    {
        return response()->json([
            'success' => new CategoryResource($category),
            'message' => 'Categories are retrieved successfully',
        ]);
    }

    /**
     * Update a category.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        return response()->json([
            'success' => new CategoryResource($category),
            'message' => 'Categories are updated successfully',
        ]);
    }

    /**
     * Delete a category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Categories are deleted successfully',
        ]);
    }
}
