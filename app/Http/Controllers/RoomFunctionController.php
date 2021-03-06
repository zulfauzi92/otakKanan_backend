<?php

namespace App\Http\Controllers;

use App\Models\RoomFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class RoomFunctionController extends Controller
{
    
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $roomFunctions = DB::table('room_functions')
        ->where('user_id', '=', $user->id)
        ->get();

        if(empty($roomFunctions)) {

            return response()->json(['status' => "Data Doesn't exist"]);
        }

        $status = "Data Exist";

        return response()->json(compact('roomFunctions', 'status'));
    }

    
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request,[
            'room_id' => 'required',
            'name' => 'required'
        ]);

        $roomFunction = RoomFunction::create([
            'room_id' => $request->get('room_id'),
            'user_id' => $user->id,
            'name' => $request->get('name')
        ]);
        
        $status = "Data created successfully";

        return response()->json(compact('roomFunction', 'status'));

    }

    
    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $roomFunction = DB::table('room_functions')
        ->where('user_id', '=', $user->id)
        ->where('id', '=', $id)
        ->first();
        
        if (empty($roomFunction)) {

            return response()->json(['status' => "Data Doesn't exist"]);
        } else {

            $status = "Showed successfully";
            return response()->json(compact('roomFunction', 'status'));
        }
    }


    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $roomFunction = DB::table('room_functions')
        ->where('user_id', '=', $user->id)
        ->where('id', '=', $id)
        ->first();

        if(empty($roomFunction)){

            return response()->json(['status' => "Data Doesn't exist"]);
        }

        if ($request->get('room_id') != null) {
            $roomFunction->update([
                'room_id' => $request->get('room_id')
            ]);
        }

        if ($request->get('name') != null) {
            $roomFunction->update([
                'name' => $request->get('name')
            ]);
        }
        
        return response()->json(['status' => "Update successfully"]);
    }

    
    public function destroy($id)
    {        
        $user = JWTAuth::parseToken()->authenticate();
        $roomFunction = DB::table('room_functions')
        ->where('user_id', '=', $user->id)
        ->where('id', '=', $id)
        ->first();


        if(empty($roomFunction)){

            return response()->json(['status' => "Data Doesn't exist"]);
        }

        $roomFunction->delete();

        return response()->json(['status' => "Delete successfully"]);
    }
}
