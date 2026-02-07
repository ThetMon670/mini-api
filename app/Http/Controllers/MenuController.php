<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MenuController extends Controller
{
    use AuthorizesRequests;


    public function index(Request $request)
    {
        $query = Menu::query();

        // restrict by user except admin
        $query->when(Auth::id() != 1, function ($q) {
            $q->where('user_id', Auth::id());
        });

        // eager load
        $query->with(['user','category']);

        // 🔎 search
        if ($request->has('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('slug', 'like', "%$search%");
            });
        }

        // 🎯 filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 💰 price range filter
        if ($request->filled('price_from') && $request->filled('price_to')) {
            $query->whereBetween('price', [
                $request->price_from,
                $request->price_to
            ]);
        }

        // 🔽 sort
        $sort_by = $request->input('sort_by', 'id');
        $sort_order = $request->input('sort_order', 'asc');

        $query->orderBy($sort_by, $sort_order);

        // 📄 paginate or all
        $menus = $request->boolean('all')
            ? $query->get()
            : $query->paginate(10);

        return response()->json([
            'success' => MenuResource::collection($menus),
            'message' => 'Menus are retrieved successfully',
        ]);
    }

    public function store(StoreMenuRequest $request)
    {
        $data = $request->validated();

        // upload image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $data['user_id'] = Auth::id();

        $menu = Menu::create($data);

        return new MenuResource($menu, 'Menus are created successfully');
    }

    public function show(Menu $menu)
    {
        return new MenuResource($menu->load(['user','category']), 'Menus are retrieved successfully');
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
