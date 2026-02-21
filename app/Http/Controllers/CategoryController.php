<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * List categories with search, sort, and pagination.
     */
    public function index(Request $request)
    {
        // GET SEARCH PARAMETERS
        $searchTerm = $request->input('q');

        // VALIDATE AND SET SORTING PARAMETERS
        $validSortColumns = ['id', 'title', 'created_at', 'updated_at'];

        $sortBy = in_array($request->input('sort_by'), $validSortColumns, true)
            ? $request->input('sort_by')
            : 'id';

        $sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc'], true)
            ? $request->input('sort_direction')
            : 'desc';

        // VALIDATE AND SET PAGINATION LIMIT
        $limit = $request->input('limit', 5);
        $limit = is_numeric($limit) && $limit > 0 && $limit <= 100
            ? (int) $limit
            : 5;

        // INITIALIZE QUERY (Scoped to authenticated user)
        $query = Category::query()->where('user_id', Auth::id());

        // APPLY SEARCH FILTER
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%');
            });
        }

        // APPLY SORTING
        $query->orderBy($sortBy, $sortDirection);

        // EXECUTE PAGINATED QUERY
        $categories = $query->paginate($limit);

        // PRESERVE QUERY PARAMETERS IN PAGINATION LINKS
        $categories->appends([
            'q' => $searchTerm,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'limit' => $limit,
        ]);

        // RETURN RESOURCE COLLECTION
        return CategoryResource::collection($categories)
            ->additional([
                'message' => 'Categories retrieved successfully',
            ]);
    }

    /**
     * Store a new category.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create([
            ...$request->validated(),
            'slug' => Str::slug($request->title),
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'data' => new CategoryResource($category),
            'message' => 'Categories are created successfully',
        ]);
    }

    /**
     * Show a single category.
     */
    public function show(Category $category)
    {
        return response()->json([
            'data' => new CategoryResource($category),
            'message' => 'Categories are retrieved successfully',
        ]);
    }

    /**
     * Update a category.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        if (isset($data['title']) &&  $data['title'] !== $category->title) {
            $data['slug'] = Str::slug($data['title']);
        }
        $category->update($data);
        return response()->json([
            'data' => new CategoryResource($category),
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
