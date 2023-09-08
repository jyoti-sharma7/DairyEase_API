<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MilkDetail;
use App\Models\ExpensesDetail;
use App\Http\Controllers\Api\ExpensesController;

class HomeController extends Controller
{
    public function showDashboard(Request $request)
    {
        if (!auth()->check()) {
    return response()->json([
        "status" => 0,
        "message" => "Unauthorized. User is not logged in."
    ], 401); // 401 Unauthorized status code
}

$user = auth()->user(); // Get the authenticated user

$total_balance = MilkDetail::where("user_id", $user->id)->sum('balance') - ExpensesDetail::where("user_id", $user->id)->sum('total_price');
$total_milk = MilkDetail::where("user_id", $user->id)->sum('liter');
$per_liter_amt = MilkDetail::where("user_id", $user->id)->avg('per_liter_amt');
   // Format per_liter_amt to have only two decimal places using number_format
   $formatted_per_liter_amt = number_format($per_liter_amt, 2);


return response()->json([
    'profile_photo' => $user->profile_photo,
    'total_balance' =>number_format($total_balance,2),
    'total_milk' => number_format($total_milk, 2), // Format total_milk as well
    'per_liter_amt' => $formatted_per_liter_amt,
]);

    }    
}
