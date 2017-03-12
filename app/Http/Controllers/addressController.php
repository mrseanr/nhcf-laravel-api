<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\addressModel;
use Log;

class addressController extends Controller
{


    private function validate_input($input)
    {
      // sert the required fields
      $required_fields = array('address_line_1','city','state','zip');
      // default the return value to true
      $return_value = true;
      // setup an array for the input paramter key names
      $input_keys = array();
      // iterate through the input data and put the key names into a string array
      foreach ($input as $key => $value) {
        //error_log('key: '.$key.' value: '.$value);
        array_push($input_keys, $key);
      }
      // iteragte through the required fields, check if the input keys are in the required fields
      foreach ($required_fields as $req_field) {
        error_log('field: '.$req_field);
        if(!in_array($req_field,$input_keys)) {
          $return_value=false;
          //error_log('not found: '.$req_field);
        }
      }
      return $return_value;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // return all addresses
      $addresses = addressModel::all();
      return response()->json(['status'=>'success','data'=>[$addresses]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      error_log('---------');
      $input = $request->all();
      $valid = $this->validate_input($input);
      if (!$valid){
        return response()->json(['status'=>'error','message'=>'Not all required fields were supplied.'],422);
      }

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
      } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
        return response()->json(['status'=>'error','message'=>'No resource found.'],404);
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
      } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
        return response()->json(['status'=>'error','message'=>'No resource found.'],404);
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
      } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
        return response()->json(['status'=>'error','message'=>'No resource found to delete.'],404);
      } catch (\Exception $e) {
        return response()->json(['status'=>'error','message'=>$e->getMessage(),'error_code'=>$e->getCode()]);
      }    }
}
