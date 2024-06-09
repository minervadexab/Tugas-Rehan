<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\AddToCart;
use App\Models\Product;
use Illuminate\Http\Request;
 
class AddToCartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $response = $this->default_response;
 
        // get data cart berdasarkan customer id
        $add_to_card = AddToCart::where('customer_id', $request->user()->id)
                            ->whereNull('checkout_id')
                            ->with('product')
                            ->get();
 
        $response['success'] = true;
        $response['data'] = $add_to_card;
 
        return response()->json($response);
    }

 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
 
        // validasi product id ada di table product
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|numeric|min:1',
        ]);
 
        // validasi qty tak lebih dari stok
        $product = Product::find($request->product_id);
 
        if ($request->qty > $product->stock) {
            $response['success'] = false;
            $response['message'] = 'Stock not enough';
            return response()->json($response);
        }
 
        // simpan ke table cart
        $add_to_cart = AddToCart::where('product_id', $request->product_id)
            ->where('customer_id', $request->user()->id)
            ->whereNull('checkout_id')
            ->first();
 
        if ($add_to_cart) {
            $add_to_cart->qty += $request->qty;
            $add_to_cart->harga_satuan = (int)$product->price;
            $add_to_cart->total_harga = (int)$add_to_cart->harga_satuan * $add_to_cart->qty;
            $add_to_cart->save();
        } else {
            $add_to_cart = new AddToCart();
            $add_to_cart->product_id = $request->product_id;
            $add_to_cart->paket_id = $request->product_id;
            $add_to_cart->customer_id = $request->user()->id;
            $add_to_cart->harga_satuan = (int)$product->price;
            $add_to_cart->qty = $request->qty;
            $add_to_cart->total_harga = (int)$add_to_cart->harga_satuan * $add_to_cart->qty;
            $add_to_cart->save();
        }
 
        $response['success'] = true;
        $response['message'] = 'Product and Paket added to cart';
        $response['data'] = $add_to_cart;
        return response()->json($response);
    }
 
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
 
        // validasi product id ada di table product
        $request->validate([
            'qty' => 'required|numeric|min:1',
        ]);


 
        // simpan ke table cart
        $add_to_cart = AddToCart::where('customer_id', $request->user()->id)
                                ->whereNull('checkout_id')
                                ->with('product')
                                ->find($id);
 
        if (empty($add_to_cart)) {
            $response['success'] = false;
            $response['message'] = 'Cart not found';
            return response()->json($response);
        }

        if ($request->qty > $add_to_cart->product->stock) {
            $response['success'] = false;
            $response['message'] = 'Stock not enough';
            return response()->json($response);
        }
 
        $add_to_cart->harga_satuan = (int)$add_to_cart->product->price;
        $add_to_cart->qty = $request->qty;
        $add_to_cart->total_harga = (int)$add_to_cart->harga_satuan * $add_to_cart->qty;
        $add_to_cart->save();
 
 
        $response['success'] = true;
        $response['message'] = 'Cart updated';
        $response['data'] = $add_to_cart;
        return response()->json($response);
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $response = $this->default_response;
 
        $add_to_card = $this->default_response;
 
        $add_to_card = AddToCart::where('customer_id', $request->user()->id)
                                ->whereNull('checkout_id')->find($id);
 
        if (empty($add_to_card))
        {
            $response['success'] = false;
            $response['message'] = 'Cart not found';
            return response()->json($response);
        }
 
        $add_to_card->delete();
        $response['success'] = true;
        $response['message'] = 'Product and Paket deleted success';
        return response()->json($response);
    }
}