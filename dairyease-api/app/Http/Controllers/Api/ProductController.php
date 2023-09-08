<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function createProduct(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'name' => 'required',
                'quantity' => 'required|numeric',
                'price' => 'required|numeric',
                'brand' => 'required',
                'description' => 'required',
                'product_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
    
            $filename = ""; // Initialize the filename variable
    
            if ($request->file('product_photo')) {
                $image = $request->file('product_photo');
                $path = public_path('/uploads/product_images/');
                !is_dir($path) &&
                    mkdir($path, 0777, true);
                $filename = time() . '.' . $image->extension();
                $image->move($path, $filename);
            }
    
            // Create the product record with the 'product_photo' field
            $product = Product::create([
                'name' => $request->name,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'brand' => $request->brand,
                'description' => $request->description,
                'product_photo' => $filename, // Assign the filename to the 'product_photo' field
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Product Added Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    
   // List the products
public function index()
{
    try {
        $products = Product::all(); // Retrieve all products from the database
        
        return response()->json([
            'status' => true,
            'message' => 'Products Found Successfully',
            'data' => $products
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}


// Delete a product
public function destroy($id)
{
    try {
        $product = Product::findOrFail($id); // Find the product by its ID
        
        // Delete the product image file from storage
        if (!empty($product->product_photo)) {
            $path = public_path('/uploads/product_images/') . $product->product_photo;
            if (file_exists($path)) {
                unlink($path); // Delete the image file
            }
        }

        // Delete the product
        $product->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}

}
