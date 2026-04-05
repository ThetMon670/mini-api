<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isNumeric;

class MenuController extends Controller
{

    public function index(Request $request)
    {
        // GET SEARCH TERM FROM REQUEST
        $searchTerm = $request->input("q");

        // VALIDATE AND SET SORTING PARAMETERS
        $validSortColumn = ['id', 'title', 'price'];

        $sortBy = in_array($request->input('sort_by'), $validSortColumn, true) ? $request->input('sort_by') : "id";

        $sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc'], true) ? $request->input('sort_direction') : 'desc';

        // GET PRICE RANGE PARAMETERS
        $priceMin = $request->input("price_min");
        $priceMax = $request->input("price_max");

        // VALIDATE AND SET PAGINATION LIMIT
        $limit = $request->input('limit', 5);

        $limit = is_numeric($limit) && $limit > 0 && $limit <= 100 ? (int) $limit :  10;

        // INITIALIZE QUERY WITH USER SCOPE
        $query = Menu::query()->where('user_id', Auth::id())->with('category');

        // APPLY SEARCH FILTER IF SEARCH TERM EXISTS
        if ($searchTerm) {
            $query->when($searchTerm, function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhereHas('category', function ($cat) use ($searchTerm) {
                        $cat->where('title', 'like', "%{$searchTerm}%")
                            ->orWhere('slug', 'like', "%{$searchTerm}%");
                    });;
            });
        }

        // APPLY PRICE RANGE FILTER
        if ($priceMin !== null && isNumeric($priceMin)) {
            $query->where('price', ">=", (float) $priceMin);
        }
        if ($priceMax !== null && isNumeric($priceMax)) {
            $query->where('price', "<=", (float) $priceMax);
        }

        //unit filtered 
        $unit = $request->input('filter_by_unit');
        if ($unit) {
            $query->where('unit', $unit);
        }

        //filtered by category-by-id
        $category = $request->input('filter_by_category_id');
        if ($category) {
            $query->where('category_id', $category);
        }

        //APPLY SORTING PRODUCT
        $query->orderBy($sortBy, $sortDirection);

        // EXECUTE PAGINATED QUERY
        $menus = $query->paginate($limit);

        // PRESERVE ALL QUERY PARAMETERS IN PAGINATION LINKS
        $menus->appends([
            'q' => $searchTerm,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'limit' => $limit,
            'price_min' => $priceMin,
            'price_max' => $priceMax,
        ]);

        return MenuResource::collection($menus)->additional(["message" => "Menus are retrieved successfully"]);
    }

    public function store(Request $request)
    {
        dd($request->file('image'));
        return response()->json([
            'data' => $request->all()
        ]);
        $menus = Menu::create([
            ...$request->validated(),
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Menu is created successfully',
            'data' => new MenuResource($menus)
        ]);
    }

    public function show(Menu $menu)
    {
        return response()->json([
            'message' => 'Menus are retrieved successfully',
            'data' => new MenuResource($menu)
        ]);
    }

    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $data = $request->validated();

        if (isset($data['title']) &&  $data['title'] !== $menu->title) {
            $data['slug'] = Str::slug($data['title']);
        }
        $menu->update($data);

        return response()->json([
            'message' => "Menu updated successfully",
            'data' => new MenuResource($menu)
        ]);
    }


    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json([
            'success' => $menu,
            'message' => 'Menu is deleted successfully'
        ]);
    }
}
