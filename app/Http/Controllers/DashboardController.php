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
    public function getDashboardData(Request $request)
    {
        try {
            $search = $request->input('search');  // Get search query from request
    
            // Fetch products with their orders and customers
            $query = Product::with(['orders.customer']);
    
            // If a search query is provided, filter by product name (coffee_type)
            if ($search) {
                $query->where('coffee_type', 'like', '%' . $search . '%');
            }
    
            // Execute query and fetch products
            $products = $query->get();
    
            return response()->json($products);  // Return products as JSON
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch data.'], 500);
        }
    }
    
}
