<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return response()->json([
//         'message' => 'API is running'
//     ]);
// });


Route::get('/', function () {
    return redirect('/api/v1');
});
