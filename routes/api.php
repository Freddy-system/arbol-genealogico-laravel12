<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\RelationshipController;
use App\Http\Controllers\GenealogyController;
use App\Http\Controllers\KinshipController;

Route::apiResource('persons', PersonController::class);

Route::post('/relationships/parentage', [RelationshipController::class, 'storeParentage']);
Route::delete('/relationships/parentage', [RelationshipController::class, 'deleteParentage']);
Route::post('/relationships/marriage', [RelationshipController::class, 'storeMarriage']);
Route::patch('/relationships/marriage/end/{id}', [RelationshipController::class, 'endMarriage']);
Route::patch('/relationships/marriage/{id}/end', [RelationshipController::class, 'endMarriage']);
Route::patch('/relationships/move-subtree', [RelationshipController::class, 'moveSubtree']);

Route::get('/genealogy/ancestors/{id}', [GenealogyController::class, 'ancestors']);
Route::get('/genealogy/descendants/{id}', [GenealogyController::class, 'descendants']);
Route::get('/genealogy/tree/{id}', [GenealogyController::class, 'tree']);
Route::get('/genealogy/{id}/ancestors', [GenealogyController::class, 'ancestors']);
Route::get('/genealogy/{id}/descendants', [GenealogyController::class, 'descendants']);
Route::get('/genealogy/{id}/tree', [GenealogyController::class, 'tree']);
Route::get('/genealogy/bfs/{id}', [GenealogyController::class, 'bfs']);
Route::get('/genealogy/dfs/{id}', [GenealogyController::class, 'dfs']);
Route::get('/genealogy/descendants/{id}/count', [GenealogyController::class, 'descendantsCount']);

Route::get('/kinship', [KinshipController::class, 'relation']);
