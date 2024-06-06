<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
class ManagerController extends Controller
{
   // here the manager places the order
   public function placeOrder(Request $request) {
    $request->validate([
        'recipe_id' => 'required|exists:recipes,id',
        'quantity' => 'required|numeric|min:1'
    ]);

    $order = Order::create([
        'manager_id' => auth()->id(),
        'recipe_id' => $request->recipe_id,
        'quantity' => $request->quantity
    ]);
}

public function viewStockReport(Request $request) {
                
}

}
