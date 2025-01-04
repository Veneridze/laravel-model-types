<?php

use Farsh4d\Bank\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Veneridze\ModelTypes\TypeCollection;

Route::prefix('types')->name('types.')->group(function () {
    Route::get('/{type}/{item}/fields', function (string $type, string $item) {
        abort_if(!Arr::has(Config::get("model-types"), $type), 404);
        $itemObj = (new TypeCollection($type))->$item;
        abort_if(!method_exists($itemObj, 'fields'), $type, 400);
        return $itemObj::fields();
    })->name('item.fields');

    Route::get('/{type}', function (string $type, string $item) {
        abort_if(!Arr::has(Config::get("model-types"), $type), 404);
        return (new TypeCollection($type))->toSelect();
    })->name('item.show');
});
