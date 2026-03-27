<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use function PHPUnit\Framework\isNumeric;

class MenuController extends Controller
{
    use AuthorizesRequests;


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
        if($unit) {
            $query->where('unit', $unit);
        }
        
        //filtered by category-by-id
        $category = $request->input('filter_by_category_id');
        if($category) {
            $query->where('category_id', $category);
        }
        
>>>>>>> 410025d66b642af53bc8acaf6c72ff5119ba05b0
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

    public function store(StoreMenuRequest $request)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $data['user_id'] = Auth::id();

        $menu = Menu::create($data);

        return new MenuResource($menu, 'Menus are created successfully');
    }

    public function show(Menu $menu)
    {
        return new MenuResource($menu->load(['user', 'category']), 'Menus are retrieved successfully');
    }

    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $data = $request->validated();

        // image optional for update
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }
        $menu->update($data);

        return new MenuResource($menu, 'Menus are updated successfully');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json([
            'success' => $menu,
            'message' => 'Menus are deleted successfully'
        ]);
    }
}