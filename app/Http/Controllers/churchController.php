<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\churchModel;

class churchController extends Controller
{
    private function validate_input($input)
    {
      // sert the required fields
      $required_fields = array('church_name','address_id');
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
        // return all churches
        $churches = churchModel::all();
        return response()->json(['status'=>'success','data'=>[$churches]]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // get all the churches
        $churches = churchModel::all();
        // setup an array for church names
        $church_names = array();
        foreach ($churches as $church) {
          array_push($church_names,$church->church_name);
        }
        if (in_array($request->church_name,$church_names)) {
          $response = array("A church with that name already exists.");
          return response()->json(['status'=>'error','message'=>"There were errors processing this request",'data'=>$response],422);
        }
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
        // create the new church using the supplied parameters
        try {
          // TODO check for blank/null parameters
          $church = new churchModel;
          $church->church_name = $request->church_name;
          $church->address_id = $request->address_id;
          $church->notes = $request->notes;
          $church->save();
          $inserted_id = $church->church_id;
          return response()->json(['status'=>'success','data'=>[$church]]);
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
        // return the church details by id
        try {
          $church = churchModel::findOrFail($id);
          return response()->json(['status'=>'success','data'=>[$church]]);
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

      // update the church using the supplied parameters
      try {
        $church = churchModel::find($id);
        $church->church_name = $request->church_name;
        $church->address_id = $request->address_id;
        $church->notes = $request->notes;
        $church->save();
        return response()->json(['status'=>'success','data'=>[$church]]);
      } catch (\Illuminate\Database\QueryException $exception) {
        // use php errorInfo class to get the exception
        $errorInfo = $exception->errorInfo;
        // return the response to the client
        return response()->json(['status'=>'error','message'=>$errorInfo[2]]);
      } catch (\Exception $e) {
        return response()->json(['status'=>'error','message'=>$e->getMessage(),'error_code'=>$e->getCode()]);
      }
    }

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
          $church = churchModel::findOrFail($id);
          $church->delete();
          return response()->json(['status'=>'success','message'=>$church->church_name.' was removed successfully.']);
        } catch (\Exception $e) {
          return response()->json(['status'=>'error','message'=>$e->getMessage(),'error_code'=>$e->getCode()]);
        }
    }
}
