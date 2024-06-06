<?php

namespace App\Http\Controllers;

use App\Models\RecipeIngredient;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class AdminController extends Controller
{
 public function addVendor(Request $request) {
   $result=$request->validate([
        'name' => 'required|string',
        'contact_details' => 'required|string',
    ]);

    if(!$result){
        return response()->json(['message' =>"failed to add vendor "],400);
    }
        $vendor = new Vendor();
        $vendor->name = $request->input('name');
        $vendor->contact_details = $request->input('contact_details');
        $vendor->save();
        return response()->json(['message' => 'Vendor added successfully'],200);
    }

    //adding igngredients
    public function addIngredient(Request $request) {
        //dd("lksdjflksd");
       $result=$request->validate([
            'name' => 'required|string',
            'unit' => 'required|string',
            'quantity'=>'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'vendor_id' => 'required|exists:vendors,id'
        ]);

        if(!$result){
            return response()->json(['message' =>"failed to add ingredient "],400);
        }

        Ingredient::create([
            'name' => $request->name,
            'unit' => $request->unit,
            'quantity'=>$request->quantity,
            'price' => $request->price,
            'vendor_id' => $request->vendor_id
        ]);
        return response()->json(['message' => 'Ingredient added successfully']);
    }

    //admin updating the ingredient quantity.
    public function updateIngredient(Request $request) {

        $ingredient = Ingredient::findOrFail($request->ingredient_id);

        $result=$request->validate([
            'name' => 'required|string',
            'unit' => 'required|string',
            'quantity'=>'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'vendor_id' => 'required|exists:vendors,id'
        ]);

        if(!$result){
            return response()->json(['message' =>"failed to add vendor "],400);
        }

        $ingredient->update([
            'name' => $request->name,
            'unit' => $request->unit,
            'price' => $request->price,
            'vendor_id' => $request->vendor_id
        ]);

        return response()->json(['message' => 'Ingredient updated successfully']);
    }
//this recipe will be added by the admin
public function addRecipe(Request $request){
   $result= $request->validate([
        'name' => 'required|string',
    ]);
    if(!$result){
        return response()->json(['message' =>"failed to add recipe "],400);
    }
    $recipe =Recipe::create([
        'name' => $request->name
    ]);
    return response()->json(['message' => 'Recipe added successfully']);
}
//recipe ingredients getting added to the recipeindregient table
public function recipeIngredients(Request $request) {
    $result=$request->validate([
        'recipe_id' => 'required|integer|exists:recipes,id',
        'ingredients' => 'required|array',
        'ingredients.*.ingredient_id' => 'required|integer|exists:ingredients,id',
        'ingredients.*.quantity' => 'required|integer|min:0',
        'ingredients.*.unit' => 'required|string',
    ]);
    if(!$result){
        return response()->json(['message' =>"failed to add recipe "],400);
    }

    foreach ($request->ingredients as $ingredient) {
        RecipeIngredient::create([
            'recipe_id' => $request->recipe_id,
            'ingredient_id' => $ingredient['ingredient_id'],
            'quantity' => $ingredient['quantity'],
            'unit' => $ingredient['unit']
        ]);
    }

    return response()->json(['message' => 'Recipe ingredients added successfully']);
}

//this stock will be added by the admin
public function addStockMovement(Request $request) {
    $result=$request->validate([
        'ingredient_id' => 'required|exists:ingredients,id',
        'vendor_id' => 'required|exists:vendors,id',
        'quantity' => 'required|numeric|min:0',
        'price' => 'required|numeric|min:0',
        'type' => 'required|in:inward,outward',
        'date' => 'required|date_format:Y-m-d'
    ]);

    if(!$result){
        return response()->json(['message' =>"failed to add recipe ingredeents"],400);
    }

    Stock::create([
        'ingredient_id' => $request->ingredient_id,
        'vendor_id' => $request->vendor_id,
        'quantity' => $request->quantity,
        'price' => $request->price,
        'type' => $request->type,
        'date' => Carbon::parse($request->date)->toDateString()
    ]);

    return response()->json(['message' => 'Stock movement added successfully']);
}

//funciton which is used to genearte the report
public function generateStockReport(Request $request) {
    $request->validate([
        'start_date' => 'required|date_format:Y-m-d',
        'end_date' => 'required|date_format:Y-m-d',
    ]);

    $startDate = $request->start_date;
    $endDate = $request->end_date;

    $stocks = DB::table('stocks')
        ->join('ingredients', 'stocks.ingredient_id', '=', 'ingredients.id')
        ->join('vendors', 'stocks.vendor_id', '=', 'vendors.id')
        ->select('stocks.*', 'ingredients.name as ingredient_name', 'vendors.name as vendor_name')
        ->whereBetween('stocks.date', [$startDate, $endDate])
        ->get();

    return response()->json(['message' => 'Stock report generated successfully', 'data' => $stocks]);
}

}
