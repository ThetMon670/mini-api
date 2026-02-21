<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // GET SEARCH PARAMETERS
        $searchTerm = $request->input('q');

        // VALIDATE AND SET SORTING PARAMETERS
        $validSortColumns = ['id', 'title', 'slug', 'created_at', 'updated_at'];

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
            : 10;

        // INITIALIZE QUERY
        $query = Category::query()->where('user_id', Auth::id());

        // APPLY SEARCH FILTER
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('slug', 'like', '%' . $searchTerm . '%');
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

        // RETURN RESOURCE COLLECTION (Same Style as CustomerController)
        return CategoryResource::collection($categories)
            ->additional([
                'message' => 'Categories are retrieved successfully',
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create([
            ...$request->validated(),
            'slug' => Str::slug($request->title),
            'user_id' => Auth::id(),
        ]);

        return new CategoryResource($category, 'Categories are created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category, 'Categories are retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if (isset($data['title']) && $data['title'] !== $category->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        $category->update($data);

        return new CategoryResource($category, 'Categories are updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'data' => [
                'success' => true,
                'message' => 'Categories are deleted successfully',
            ]
        ]);
    }
}