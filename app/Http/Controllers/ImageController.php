<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store(StoreImageRequest $request)
    {
        $file = $request->file('image');
        $path = Storage::putFile('/', $file);
        return response()->json([
            'message' => 'Medias are upload successfully',
            'data' => [
                'path' => $path,
                'url' => asset(Storage::url($path)),
            ],
        ]);

    }

    public function destroy($path)
    {
        Storage::delete($path);

        return response()->json(
        [
            'data' => [
                'success' => null,
                'message' => 'Media upload successfully',
            ],
        ]);
    }
}
