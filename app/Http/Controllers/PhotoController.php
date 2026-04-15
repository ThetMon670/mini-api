<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhotoRequest $request)
    {
        if (!$request->hasFile('image')) {
            return response([
                "message" => "No file uploaded"
            ], 400);
        }

        $path = Storage::disk('s3')->put('photos', $request->file('image'));

        if (!$path) {
            return response([
                "message" => "Upload failed (check MinIO or config)"
            ], 500);
        }

        $photo = Photo::create([
            "url" => $path
        ]);

        return response([
            "message" => "Photo stored successfully",
            "data" => new PhotoResource($photo)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($url)
    {
        Storage::delete($url);
        Photo::where("url", $url)->delete();
        return response([
            "message" => "Photo deleted successfully",
        ]);
    }
}
