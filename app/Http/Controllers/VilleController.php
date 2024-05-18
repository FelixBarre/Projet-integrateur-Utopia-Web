<?php

namespace App\Http\Controllers;

use App\Http\Resources\VilleResource;
use App\Models\Ville;

use Illuminate\Http\Request;

class VilleController extends Controller
{
    public function index(Request $request) {
        if ($request->routeIs('villesApi')) {
            return VilleResource::collection(Ville::All());
        }
    }

    public function show(Request $request, int $id) {
        if ($request->routeIs('villeApi')) {
            return VilleResource::collection(Ville::where('id', $id)->get());
        }
    }
}
