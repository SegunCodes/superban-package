<?php 

use Superban\Middleware\SuperbanMiddleware;

Route::middleware(['superban:200,2,1440'])->group(function () {
    Route::post('/thisroute', function () {
        return response()->json(['message' => 'hello world']);
    });

    Route::post('/anotherroute', function () {
        return response()->json(['message' => 'hello zaddy']);
    });
});
