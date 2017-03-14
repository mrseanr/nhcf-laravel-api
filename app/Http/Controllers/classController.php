<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\classModel;

class classController extends Controller
{
    private function validate_input($input)
    {
        // sert the required fields
        $required_fields = array('church_id','teacher_id','class_type_id','active','class_name');
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
          // check the active value is true/false
          if ($key == 'active' && !in_array($value,array("true","false"))) {
            $valid_request = false;
            $response['active_bool_error'] = "The Active field must be boolean.";
          }
          // check the church_id value is numeric
          if ($key == 'church_id' && !is_numeric($value)) {
            $valid_request = false;
            $response['church_id_numeric_error'] = "The Church ID field must be numeric.";
          }
          // check the teacher_id value is numeric
          if ($key == 'teacher_id' && !is_numeric($value)) {
            $valid_request = false;
            $response['teacher_id_numeric_error'] = "The Teacher ID field must be numeric.";
          }
          // check the teacher_id value is numeric
          if ($key == 'class_typ_id' && !is_numeric($value)) {
            $valid_request = false;
            $response['class_type_id_numeric_error'] = "The Class Type ID field must be numeric.";
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
        // return all classes
        $classes = classModel::all();
        return response()->json(['status'=>'success','data'=>[$classes]],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //error_log('-----CREATE-----');
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
        $active_input = $request->active;
        if ($active_input == "true") {
          $active = 1;
        } else {
          $active = 0;
        }
        // create the new class type using the supplied parameters
        try {
          $class = new classModel;
          $class->church_id=$request->church_id;
          $class->teacher_id=$request->teacher_id;
          $class->class_type_id=$request->class_type_id;
          $class->active=$active;
          $class->class_name=$request->class_name;
          $class->notes=$request->notes;
          $class->save();
          $inserted_id = $class->class_id;
          return response()->json(['status'=>'success','data'=>[$class]],200);
        } catch (\Illuminate\Database\QueryException $exception) {
          // use php errorInfo class to get the exception
          $errorInfo = $exception->errorInfo;
          // return the response to the client
          return response()->json(['status'=>'error','message'=>$errorInfo[2]],500);
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
        // return the class type details by id
        try {
          $class = classModel::findOrFail($id);
          return response()->json(['status'=>'success','data'=>[$class]],200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
          return response()->json(['status'=>'error','message'=>'No resource found.'],404);
        } catch (\Exception $e) {
          return response()->json(['status'=>'error','message'=>$e->getMessage(),'error_code'=>$e->getCode()],500);
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
        //error_log('-----UPDATE-----');
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
              //error_log("key: ".$message);
              array_push($messages,$message);
            }
          }
          // return the error to the user
          return response()->json(['status'=>'error','message'=>"There were errors processing this request",'data'=>$messages],422);
        }
        $active_input = $request->active;
        if ($active_input == "true") {
          $active = 1;
        } else {
          $active = 0;
        }
        // create the new class type using the supplied parameters
        try {
          $class = classModel::find($id);
          $class->church_id=$request->church_id;
          $class->teacher_id=$request->teacher_id;
          $class->class_type_id=$request->class_type_id;
          $class->active=$active;
          $class->class_name=$request->class_name;
          $class->notes=$request->notes;
          $class->save();
          return response()->json(['status'=>'success','data'=>[$class]],200);
        } catch (\Illuminate\Database\QueryException $exception) {
          // use php errorInfo class to get the exception
          $errorInfo = $exception->errorInfo;
          // return the response to the client
          return response()->json(['status'=>'error','message'=>$errorInfo[2]],500);
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
          $class = classModel::findOrFail($id);
          $class->delete();
          return response()->json(['status'=>'success','message'=>'The class '.$class->class_name.' was removed successfully.'],200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
          return response()->json(['status'=>'error','message'=>'No resource found to delete.'],404);
        } catch (\Exception $e) {
          return response()->json(['status'=>'error','message'=>$e->getMessage(),'error_code'=>$e->getCode()],500);
        }
    }
}
