<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::guard('api')->user();
        return response()->json(['user' => $user]);
    }
    public function getDashboardData()
    {
        try {
            // Fetch all products with their related orders and customers
            $products = Product::with(['orders.customer'])->get();

            // Return the data as JSON
            return response()->json($products);
        } catch (\Exception $e) {
            // Return error if something goes wrong
            return response()->json(['error' => 'Failed to fetch data.'], 500);
        }
    }
    
}
