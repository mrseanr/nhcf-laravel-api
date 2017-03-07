<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\churchModel;

class churchController extends Controller
{
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
      // find the church by id, set the update pg_parameter_status
      try {
        // TODO Check for empty parameters.
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
