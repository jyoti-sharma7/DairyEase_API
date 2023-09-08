<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MilkDetail;

class MilkDetailsController extends Controller
{
     //create milk details 
     public function create(Request $request)
     {
         // Validate the input
         $request->validate([
             'shift' => 'required|in:morning,evening',
             'date' => 'required|date',
             'per_fat_amt' => 'required|numeric',
             'fat_rate' => 'required|numeric',
             'per_snf_amt' => 'required|numeric',
             'snf_rate' => 'required|numeric',
             'liter' => 'required|numeric',
         ]);
         //user id + create data
         $user_id = auth()->user()->id;
 
         $milkDetail = new MilkDetail();
 
         $milkDetail->user_id = $user_id;
         $milkDetail->shift = $request->shift;
         $milkDetail->date = $request->date;
         $milkDetail->per_fat_amt = $request->per_fat_amt;
         $milkDetail->fat_rate = $request->fat_rate;
         $milkDetail->per_snf_amt = $request->per_snf_amt;
         $milkDetail->snf_rate = $request->snf_rate;
         $milkDetail->liter = $request->liter;
 
       // Calculate total fat and total snf
     $total_fat = $request->per_fat_amt * $request->fat_rate;
     $total_snf = $request->per_snf_amt * $request->snf_rate;
 
     // Calculate balance
     $per_liter_amt = $total_fat + $total_snf;
     $balance = $per_liter_amt * $request->liter;


     $milkDetail->total_fat = $total_fat;
     $milkDetail->total_snf = $total_snf;
     $milkDetail->per_liter_amt=$per_liter_amt;
     $milkDetail->balance = $balance;
      // Format values with two decimal places
      $total_fat = number_format($total_fat, 2);
    $total_snf = number_format($total_snf, 2);
    $per_liter_amt = number_format($per_liter_amt, 2);
    $balance = number_format($balance, 2);

     $milkDetail->save();
 
     // response
     return response()->json([
         "status" => 1,
         "message" => "milk details have been created",
         "total_fat" => $total_fat,
         "total_snf" => $total_snf,
         "per_liter_amt"=>$per_liter_amt,
         "balance" => $balance
     ]);
     }
 //list of milk details 
     public function list()
     {
         if (!auth()->check()) {
             return response()->json([
                 "status" => 0,
                 "message" => "Unauthorized. User is not logged in."
             ], 401); // 401 Unauthorized status code
         }
     
         $user_id = auth()->user()->id;
     
         $milkDetails = MilkDetail::where("user_id", $user_id)->get();
      // Loop through the collection and format the values
    $formattedMilkDetails = $milkDetails->map(function ($milkDetail) {
        return [
            "id" => $milkDetail->id,
            "user_id" => $milkDetail->user_id,
            "shift" => $milkDetail->shift,
            "date" => $milkDetail->date,
            "per_fat_amt" => number_format($milkDetail->per_fat_amt, 2),
            "fat_rate" => number_format($milkDetail->fat_rate, 2),
            "per_snf_amt" => number_format($milkDetail->per_snf_amt, 2),
            "snf_rate" => number_format($milkDetail->snf_rate, 2),
            "liter" => number_format($milkDetail->liter, 2),
            "total_fat" => number_format($milkDetail->total_fat, 2),
            "total_snf" => number_format($milkDetail->total_snf, 2),
            "per_liter_amt" => number_format($milkDetail->per_liter_amt, 2),
            "balance" => number_format($milkDetail->balance, 2),
            "created_at" => $milkDetail->created_at,
            "updated_at" => $milkDetail->updated_at
        ];
    });
     // Calculate the total balance
     $totalBalance = $milkDetails->sum('balance');
         return response()->json([
             "status" => 1,
             "message" => "milk details",
             "data" => $formattedMilkDetails,
             "total_balance" => number_format($totalBalance, 2)
         ]);
     }
 //delete milkdetails 
 public function delete($id)
 {
     $user_id = auth()->user()->id;
     
     // Check if the milk detail exists and belongs to the user
     $milkDetail = MilkDetail::where([
         "id" => $id,
         "user_id" => $user_id
     ])->first();
 
     if ($milkDetail) {
         $milkDetail->delete();
         
         return response()->json([
             "status" => 1,
             "message" => "Milk details have been deleted successfully"
         ]);
     } else {
         return response()->json([
             "status" => 0,
             "message" => "Milk details not found"
         ]);
     }
 }

 public function index(Request $request)
 { // Check if the user is authenticated
    if (!auth()->check()) {
        return response()->json([
            'status' => 0,
            'message' => 'Unauthorized. User is not logged in.',
        ], 401); // 401 Unauthorized status code
    }

    // Get the authenticated user
    $user_id = auth()->user()->id;

     // Get the selected shift from the query parameter
     $shift = $request->query('shift', 'morning'); // Default to 'morning' if not specified

     // Query the Milk model to filter by the selected shift
     $milkDetails = MilkDetail::where('shift', $shift)
     ->where("user_id", $user_id)
     ->get();

  // Loop through the collection and format the values
  $formattedMilkDetails = $milkDetails->map(function ($milkDetail) {
    return [
        "id" => $milkDetail->id,
        "user_id" => $milkDetail->user_id,
        "shift" => $milkDetail->shift,
        "date" => $milkDetail->date,
        "per_fat_amt" => number_format($milkDetail->per_fat_amt, 2),
        "fat_rate" => number_format($milkDetail->fat_rate, 2),
        "per_snf_amt" => number_format($milkDetail->per_snf_amt, 2),
        "snf_rate" => number_format($milkDetail->snf_rate, 2),
        "liter" => number_format($milkDetail->liter, 2),
        "total_fat" => number_format($milkDetail->total_fat, 2),
        "total_snf" => number_format($milkDetail->total_snf, 2),
        "per_liter_amt" => number_format($milkDetail->per_liter_amt, 2),
        "balance" => number_format($milkDetail->balance, 2),
        "created_at" => $milkDetail->created_at,
        "updated_at" => $milkDetail->updated_at
    ];
});
     // Return the filtered data as a JSON response
     return response()->json([
         'status' => 1,
         'message' => "{$shift} milk details",
         'data' => $formattedMilkDetails,
     ]);
 }

}
