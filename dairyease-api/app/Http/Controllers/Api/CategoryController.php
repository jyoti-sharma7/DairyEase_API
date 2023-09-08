<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function createCategory(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'category_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
    
            $filename = ""; // Initialize the filename variable
    
            if ($request->file('category_photo')) {
                $image = $request->file('category_photo');
                $path = public_path('/uploads/category_images/');
                !is_dir($path) &&
                    mkdir($path, 0777, true);
                $filename = time() . '.' . $image->extension();
                $image->move($path, $filename);
            }
    
            // Create the product record with the 'product_photo' field
            $category = Category::create([
                'category_photo' => $filename, // Assign the filename to the 'product_photo' field
                'category_name' => $request->category_name,
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Category Added Successfully',
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
        $categories = Category::all(); // Retrieve all products from the database
        
        return response()->json([
            'status' => true,
            'message' => 'Categories Found Successfully',
            'data' => $categories
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}
// Delete a Category
public function destroy($id)
{
    try {
        $category = Category::findOrFail($id); // Find the product by its ID
        
        // Delete the product image file from storage
        if (!empty($category->category_photo)) {
            $path = public_path('/uploads/category_images/') . $category->category_photo;
            if (file_exists($path)) {
                unlink($path); // Delete the image file
            }
        }

        // Delete the category
        $category->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}
}
