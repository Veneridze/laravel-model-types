<?php

use Farsh4d\Bank\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Veneridze\ModelTypes\TypeCollection;

Route::prefix('types')->name('types.')->group(function () {
    Route::get('/{type}/{item}/fields', function (Request $request, string $type, string $item) {
        // return $request->query();
        abort_if(!Arr::has(Config::get("model-types"), $type), 404);
        $itemObj = (new TypeCollection($type))->$item;
        abort_if(!method_exists($itemObj, 'fields'), $type, 400);
        return array_map(fn($row) => array_map(fn($field) => $field->toArray(), $row), $itemObj::fields(...$request->query()));
    })->name('item.fields');

    Route::get('/{type}', function (string $type) {
        abort_if(!Arr::has(Config::get("model-types"), $type), 404);
        return (new TypeCollection($type))->toSelect();
    })->name('item.show');
});
