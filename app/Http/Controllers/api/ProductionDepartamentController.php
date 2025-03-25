<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ProductionDepartament;
use Illuminate\Http\Request;

class ProductionDepartamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $production_departaments = ProductionDepartament::all();
        return response()->json($production_departaments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionDepartament $productionDepartament)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductionDepartament $productionDepartament)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionDepartament $productionDepartament)
    {
        //
    }
}
