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
      $valid_request = true;
      // setup the missing fields array
      $missing_fields = array();
      // setup default response array
      $response = array();
      $response['result'] = "success";
      // setup an array for the input paramter key names
      $input_keys = array();
      // iterate through the input data and put the key names into a string array
      foreach ($input as $key => $value) {
        //error_log('key: '.$key.' value: '.$value);
        array_push($input_keys, $key);
        // check state value is less than 2 characters
        if ($key == 'state' && strlen($value) > 2) {
          $valid_request = false;
          $response['city_length_error'] = "The State field only allows 2 characters.";
        }
        // check the zip value is numeric
        if ($key == 'zip' && !is_numeric($value)) {
          $valid_request = false;
          $response['zip_numeric_error'] = "The Zip Code field must be numeric.";
        }
        // check the zip value is less than 5 characters
        if ($key == 'zip' && strlen((string)$value) > 5) {
          $valid_request = false;
          $response['zip_length_error'] = "The Zip Code field only allows 5 digits.";
        }
      }
      // iteragte through the required fields, check if the input keys are in the required fields
      foreach ($required_fields as $req_field) {
        //error_log('field: '.$req_field);
        if(!in_array($req_field,$input_keys)) {
          $valid_request=false;
          array_push($missing_fields,$req_field);
          //error_log('not found: '.$req_field);
        }
      }
      if (!$valid_request) {
        // change the result value
        if (in_array('result', $response))
        {
          unset($array[array_search('result',$response)]);
        }
        $response['result'] = "error";
        // add the missing fields
        if (count($missing_fields) > 0) {
          $response['missing_field_error'] = "The following fields are required, but not provided: ". implode(",",$missing_fields);
        }
      }
      // return the response array
      return $response;
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
      //error_log('---------');
      // get all the request parameters
      $input = $request->all();
      // validate the input parameters
      $validate_array = $this->validate_input($input);
      // iterate through the validation response
      foreach ($validate_array as $item => $value) {
        //error_log("item: ".$item." value: ".$value);
        // get the result value
        if ($item == "result") {
          $result=$value;
        }
      }
      //error_log("result: ". $result);
      // if it's not a success, return the error
      if ($result != "success"){
        $messages = array();
        foreach ($validate_array as $item => $message) {
          if (strpos($item, '_error') !== false) {
            error_log("key: ".$message);
            array_push($messages,$message);
          }
        }
        // return the error to the user
        return response()->json(['status'=>'error','message'=>"There were errors processing this request",'data'=>$messages],422);
      }

      // create the new address using the supplied parameters
      try {
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
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

      // get all the request parameters
      $input = $request->all();
      // validate the input parameters
      $validate_array = $this->validate_input($input);
      // iterate through the validation response
      foreach ($validate_array as $item => $value) {
        //error_log("item: ".$item." value: ".$value);
        // get the result value
        if ($item == "result") {
          $result=$value;
        }
      }
      //error_log("result: ". $result);
      // if it's not a success, return the error
      if ($result != "success"){
        $messages = array();
        foreach ($validate_array as $item => $message) {
          if (strpos($item, '_error') !== false) {
            error_log("key: ".$message);
            array_push($messages,$message);
          }
        }
        // return the error to the user
        return response()->json(['status'=>'error','message'=>"There were errors processing this request",'data'=>$messages],422);
      }

      // update the address using the supplied parameters
      try {
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
      }
    }
}
