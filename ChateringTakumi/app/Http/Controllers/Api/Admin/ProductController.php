<?php
 
namespace App\Http\Controllers\Api\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Exception;
 
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->default_response;
        try{
        $products = Product::all();
 
        $response['success'] = true;
        $response['data'] = [
            'products' => $products,
        ];
    }catch(\Exception $e){
        $response['message'] = $e->getMessage();
    }
        return response()->json($response);
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $response = $this->default_response;
 
        try{
            $data = $request->validated();
 
            if ($request->hasFile('image')) {
                $file = $request->file('image');
 
                $path = $file->storeAs('project-images', $file->hashName(), 'public');
            }
 
            $product = new Product();
            $product->name = $data['name'];
            $product->description = $data['description'];
            $product->price = $data['price'];
            $product->image = $path ?? null;
            $product->stock = $data['stock'];
            $product->paket_id = $data['paket_id'];
            $product->save();
 
            $response['success'] = true;
            $response['data'] = [
                'paket' => $product,
            ];
 
            $response['message'] = 'Product created successfully';
        }catch(Exception $e){
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }
 
    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $response = $this->default_response;
        try{
        $products = Product::with('paket')->find( $id );
 
        $response['success'] = true;
        $response['data'] = [
            'products' => $products,
        ];
    }catch(\Exception $e){
        $response['message'] = $e->getMessage();
    }
        return response()->json($response);
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, String $id)
    {
        $response = $this->default_response;
 
        try{
            $data = $request->validated();
 
            if ($request->hasFile('image')) {
                $file = $request->file('image');
 
                $path = $file->storeAs('project-images', $file->hashName(), 'public');
            }
 
            $product = Product::find($id);
            $product->name = $data['name'];
            $product->description = $data['description'];
            $product->price = $data['price'];
            if ($request->hasfile('image'))$product->image = $path ?? null;
            $product->stock = $data['stock'];
            $product->paket_id = $data['paket_id'];
            $product->save();
 
            $response['success'] = true;
            $response['data'] = [
                'products' => $product -> with ('paket')->find($product->id),
            ];
 
            $response['message'] = 'product update successfully';
        }catch(Exception $e){
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $response = $this->default_response;
 
        try{
            $paket = Product::find($id);
 
            if(!$paket) {
                throw new Exception('Product not found');
            }
 
            $paket->delete();
 
            $response['success'] = true;
            $response['message'] = 'Product deleted successfully';
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response); 
    }
}