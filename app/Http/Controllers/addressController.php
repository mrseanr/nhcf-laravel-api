<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\addressModel;

class addressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // return all addresses
      $addresses = addressModel::all();
      return response()->json(['status'=>'success','data'=>[$addresses]]);    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // create the new address using the supplied parameters
      try {
        // TODO check for blank/null parameters
        $address = new addressModel;
        $address->address_line_1=$request->address_line_1;
        $address->address_line_2=$request->address_line_2;
        $address->city=$request->city;
        $address->state=$request->state;
        $address->zip=$request->zip;
        $address->notes=$request->notes;
        $address->save();
        $inserted_id = $address->address_id;
        return response()->json(['status'=>'success','data'=>[$address]]);
      } catch (\Illuminate\Database\QueryException $exception) {
        // use php errorInfo class to get the exception
        $errorInfo = $exception->errorInfo;
        // return the response to the client
        return response()->json(['status'=>'error','message'=>$errorInfo[2]]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      // return the address details by id
      try {
        $address = addressModel::findOrFail($id);
        return response()->json(['status'=>'success','data'=>[$address]]);
      } catch (\Exception $e) {
        return response()->json(['status'=>'error','message'=>$e->getMessage(),'error_code'=>$e->getCode()]);
      }    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      // create the new address using the supplied parameters
      try {
        // TODO check for blank/null parameters
        $address = addressModel::find($id);
        $address->address_line_1=$request->address_line_1;
        $address->address_line_2=$request->address_line_2;
        $address->city=$request->city;
        $address->state=$request->state;
        $address->zip=$request->zip;
        $address->notes=$request->notes;
        $address->save();
        return response()->json(['status'=>'success','data'=>[$address]]);
      } catch (\Illuminate\Database\QueryException $exception) {
        // use php errorInfo class to get the exception
        $errorInfo = $exception->errorInfo;
        // return the response to the client
        return response()->json(['status'=>'error','message'=>$errorInfo[2]]);
      }    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      // retrieve the model, then delete it
      try {
        $address = addressModel::findOrFail($id);
        $address->delete();
        return response()->json(['status'=>'success','message'=>$address->address_line_1.','.$address->city.' was removed successfully.']);
      } catch (\Exception $e) {
        return response()->json(['status'=>'error','message'=>$e->getMessage(),'error_code'=>$e->getCode()]);
      }    }
}
