<?php

// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Models\Category;



class ProductController extends Controller
{
    public function index()
    {
        // Retrieve all products
        $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.*', 'categories.category_name as category')
        // ->paginate(10);
        ->get();
        return response()->json([
            'status' => 'success',
            'data' => ['products'=>$products],
        ]);
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



    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'regular_price' => 'required|numeric|min:0',
                'brand' => 'required|string|max:255',
                'product_img1' => 'nullable|string',
                'product_img2' => 'nullable|string',
                'product_img3' => 'nullable|string',
                'product_img4' => 'nullable|string',
                'product_img5' => 'nullable|string',
                'weight' => 'required|numeric|min:0',
                'quantity_in_stock' => 'required|integer|min:0',
                'tags' => 'nullable|string',
                'refundable' => 'required|boolean',
                'status' => 'required|in:active,disabled',
                'sales_price' => 'required|numeric|min:0',
                'meta_title' => 'required|string|max:255',
                'meta_description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Create a new product
            Product::create($request->all());

            return redirect()->route('products.index')->with('success', 'Product created successfully');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage())->withInput();
        }
    }


    public function update(Request $request, $id)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'regular_price' => 'required|numeric|min:0',
                'brand' => 'required|string|max:255',
                'product_img1' => 'nullable|string',
                'product_img2' => 'nullable|string',
                'product_img3' => 'nullable|string',
                'product_img4' => 'nullable|string',
                'product_img5' => 'nullable|string',
                'weight' => 'required|numeric|min:0',
                'quantity_in_stock' => 'required|integer|min:0',
                'tags' => 'nullable|string',
                'refundable' => 'required|boolean',
                'status' => 'required|in:active,disabled',
                'sales_price' => 'required|numeric|min:0',
                'meta_title' => 'required|string|max:255',
                'meta_description' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            // Update the product
            $product = Product::findOrFail($id);
            $product->update($request->all());
    
            return redirect()->route('products.index')->with('success', 'Product updated successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('products.index')->with('error', 'Product not found');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage())->withInput();
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