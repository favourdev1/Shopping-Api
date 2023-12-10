<?php

// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class ProductController extends Controller {
    public function index() {
        // Retrieve all products
        $products = Product::all();

        return response()->json( [
            'status' => 'success',
            'data' => $products,
        ] );
    }

  

    public function show($productId)
    {
        try {
            // Find the product by ID
            $product = Product::findOrFail($productId);
    
            return response()->json([
                'status' => 'success',
                'data' => $product,
            ]);
    
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    
    

    public function store( Request $request ) {

        try {
            // // Validate the request data
            $request->validate( [
                'name' => 'required|string',
                'category' => 'required|string',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'brand' => 'required|string',
                'image1' => 'nullable|string',
                'image2' => 'nullable|string',
                'image3' => 'nullable|string',
                'image4' => 'nullable|string',
                'image5' => 'nullable|string',
                'weight' => 'required|numeric',
                'quantity_in_stock' => 'required|integer',
                'tags' => 'nullable|string',
                'refundable' => 'required|boolean',
            ] );

            // // Create a new product
            $product = Product::create( $request->all() );

            return response()->json( [
                'status' => 'success',
                'message' => 'Product created successfully',
                'data' => $product,
            ], 201 );
            // 201 Created status code

        } catch ( \Illuminate\Validation\ValidationException $e ) {
            return response()->json( [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422 );
            // 422 Unprocessable Entity status code
        }
    }

    public function update(Request $request, $productId)
    {
        // Validate the request data
        try {
            $request->validate([
                'name' => 'string',
                'category' => 'string',
                'description' => 'nullable|string',
                'price' => 'numeric',
                'brand' => 'string',
                'image1' => 'nullable|string',
                'image2' => 'nullable|string',
                'image3' => 'nullable|string',
                'image4' => 'nullable|string',
                'image5' => 'nullable|string',
                'weight' => 'numeric',
                'quantity_in_stock' => 'integer',
                'tags' => 'nullable|string',
                'refundable' => 'boolean',
            ]);
    
            // Find the product by ID
            $product = Product::where('id', $productId)->firstOrFail();
    
            // Update the product with the provided data
            $product->update($request->all());
    
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully',
                'data' => $product,
            ]);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    

    public function destroy($productId)
    {
        try {
            // Find the product by ID
            $product = Product::where('id', $productId)->firstOrFail();
    
            // Delete the product
            $product->delete();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully',
            ]);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }
    }
    

// TODO fix bug with serarch method




  


public function search(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:3',
        ]);

        $validator->validate();

        $query = $request->input('query');

        $results = Product::where('name', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Search results retrieved successfully',
            'data' => $results,
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors(),
        ], 422);
    }
}

    
}