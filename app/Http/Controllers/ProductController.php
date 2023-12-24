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
                'product_img1' => 'required|string',
                'product_img2' => 'required|string',
                'product_img3' => 'required|string',
                'product_img4' => 'required|string',
                'product_img5' => 'nullable|string',
                'weight' => 'numeric|min:0',
                'quantity_in_stock' => 'required|integer|min:0',
                'tags' => 'nullable|string',
                'refundable' => 'boolean',
                'status' => 'required|in:active,inactive',
                'sales_price' => 'required|numeric|min:0',
                'meta_title' => 'required|string|max:255',
                'meta_description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'=>'error',
                    'message'=> implode(", ", $validator->errors()->all())
                ],422);
         
            }

            // Create a new product
            Product::create($request->all());

            return response()->json([
                'status'=>'success',
                'message'=>'Product added successfully'
            ],200);
        } catch (QueryException $e) {
            return response()->json([
                'status'=>'error',
                'message', 'Error creating product: ' . $e->getMessage()
                ]
            );
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
               return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => implode($validator->errors()->all()),
            ], 422);
        
            }
    
            // Update the product
            $product = Product::findOrFail($id);
            $product->update($request->all());
    
            return response()->json([
                'status'=>'success',
                'message'=>'Product updated successfully'
            ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'product does not exist ',
                'errors' => $e->getMessage(),
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating product ',
                'errors' => $e->getMessage(),
            ], 404);
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

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }
    }


    // for uploading image 
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->file('image')->isValid()) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('storage/product_img'), $imageName);

                return response()->json([
                    'data' => ['image_url' => url('storage/product_img/' . $imageName)],
                    'message' => 'Image uploaded successfully',
                    'status' => 'success'
                ], 200);
            } else {
                return response()->json([
                    'data' => null,
                    'message' => 'Invalid image file',
                    'status' => 'error'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Error uploading image: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
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