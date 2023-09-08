<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpensesDetail;
use App\Models\User;
use Illuminate\Validation\Rule;

class ExpensesDetailsController extends Controller
{
     //create expenses details 
     public function createExpenses(Request $request)
     {
         // Validate the input
         $request->validate([
            'date' => 'required|date',
            'product' => 'required',
            'shift' => ['required', Rule::in(['morning', 'evening'])],
            'quantity' => 'required|integer',
            'unit' => 'required',
            'per_quantity' => 'required|numeric',
         ]);
         //user id + create data
         $user_id = auth()->user()->id;
 
         $expensesDetail = new ExpensesDetail();
 
         $expensesDetail->user_id = $user_id;
         $expensesDetail->date = $request->date;
         $expensesDetail->product = $request->product;
         $expensesDetail->shift = $request->shift;
         $expensesDetail->quantity = $request->quantity;
         $expensesDetail->unit = $request->unit;
         $expensesDetail->per_quantity = $request->per_quantity;
 
       // Calculate total fat and total snf
     $total_price = $request->quantity * $request->per_quantity * $request->unit;
     $expensesDetail->total_price = $total_price;

  
     $expensesDetail->save();
 
     // response
     return response()->json([
         "status" => 1,
         "message" => "expenses details have been created",
         "total_price" => $total_price,
        
     ]);
     }


     //list expenses detail
     public function listExpenses()
     {
         if (!auth()->check()) {
             return response()->json([
                 "status" => 0,
                 "message" => "Unauthorized. User is not logged in."
             ], 401); // 401 Unauthorized status code
         }
     
         $user_id = auth()->user()->id;
     
         $expensesDetails = ExpensesDetail::where("user_id", $user_id)->get();
          // Calculate the total balance
          $totalBalance = $expensesDetails->sum('total_price');
    

         return response()->json([
             "status" => 1,
             "message" => "expenses details",
             "data" => $expensesDetails,
             "total_balance" => number_format($totalBalance, 2)

         ]);
     }
     //delete expensesdetails 
public function deleteExpenses($id)
{
    $user_id = auth()->user()->id;
    
    // Check if the milk detail exists and belongs to the user
    $expensesDetail = ExpensesDetail::where([
        "id" => $id,
        "user_id" => $user_id
    ])->first();

    if ($expensesDetail) {
        $expensesDetail->delete();
        
        return response()->json([
            "status" => 1,
            "message" => "expenses details have been deleted successfully"
        ]);
    } else {
        return response()->json([
            "status" => 0,
            "message" => "expenses details not found"
        ]);
    }
}

//update
public function update(Request $request, $id)
    {
        $expensesDetail = ExpensesDetail::findOrFail($id);

        $validatedData = $request->validate([
            'date' => 'required|date',
            'product' => 'required',
            'shift' => ['required', Rule::in(['morning', 'evening'])],
            'quantity' => 'required|integer',
            'unit' => 'required',
            'per_quantity' => 'required|numeric',
        ]);

        $validatedData['total_price'] = $validatedData['quantity'] * $validatedData['per_quantity']* $validatedData['unit'];

        $expensesDetail->update($validatedData);

        return response()->json(['message' => 'Expense updated successfully', 'expense' => $expensesDetail]);
    }

    public function listShift(Request $request)
{
    // Check if the user is logged in
    if (!auth()->check()) {
        return response()->json([
            "status" => 0,
            "message" => "Unauthenticated. User is not logged in."
        ], 401); // 401 Unauthorized status code
    }

    // Get the authenticated user
    $user_id = auth()->user()->id;

    // Get the selected shift from the query parameter
    $shift = $request->query('shift', 'morning'); // Default to 'morning' if not specified

    // Query the Expense model to filter by the selected shift and the authenticated user's ID
    $expensesDetails = ExpensesDetail::where('shift', $shift)
        ->where("user_id", $user_id)
        ->get();

    // Return the filtered data as a JSON response
    return response()->json([
        "status" => 1,
        "messag" => "{$shift} expenses details",
        "data" => $expensesDetails,
    ]);
}

}
