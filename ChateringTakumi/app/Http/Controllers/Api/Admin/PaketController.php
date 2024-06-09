<?php
 
namespace App\Http\Controllers\Api\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Http\Requests\StorePaketRequest;
use App\Http\Requests\UpdatePaketRequest;
// use App\Http\Requests\UpdateProductRequest;
use Exception;
 
class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->default_response;
        try{
        $paket = Paket::all();
 
        $response['success'] = true;
        $response['data'] = [
            'paket' => $paket,
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
    public function store(StorePaketRequest $request)
    {
        $response = $this->default_response;
        try{
            $data = $request->validated();
 
            $paket = new Paket();
            $paket->name = $data['name'];
            $paket->description = $data['description'];
            $paket->save();
 
            $response['success'] = true;
            $response['data'] = [
                'paket' => $paket,
            ];
 
            $response['message'] = 'Paket created successfully';
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
            $paket = Paket::find($id);
 
            $response['success'] = true;
            $response['message'] = "Get Paket Sucsess";
            $response['data'] = [
                'paket' => $paket,
            ];
        }catch(Exception $e){
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paket $paket)
    {
        //
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaketRequest $request, String $id)
    {
        $response = $this->default_response;
 
        try{
            $data = $request->validated();
 
            $paket = Paket::find($id);
            $paket->name = $data['name'];
            $paket->description = $data['description'];
            $paket->save();
 
            $response['success'] = true;
            $response['data'] = [
                'paket' => $paket,
            ];
            $response['message'] = 'Paket updated successfully';
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
            $paket = Paket::find($id);
 
            if(!$paket) {
                throw new Exception('Paket not found');
            }
 
            $paket->delete();
 
            $response['success'] = true;
            $response['message'] = 'Paket deleted successfully';
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }
}