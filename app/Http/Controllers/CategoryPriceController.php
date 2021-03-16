<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryPrice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class CategoryPriceController extends Controller
{
    public function index() {

        $user = JWTAuth::parseToken()->authenticate();

        $category_price = DB::table('$category_price')
        ->where('user_id', '=', $user->id)
        ->get();

        if (empty($category_price)) {

            return response()->json([ 'status' => "Data doesn't exist"]); 

        }

        $status = "Data exist";

        return response()->json(compact('category_price', 'status'));

    }

    public function store(Request $request, $id) {

        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request,[
            'name' => 'required|string|max:255'
        ]);

        try {

            $category_price = CategoryPrice::create([
                'user_id' => $user->id,
                'name' => $request->get('name')
            ]);

            $room_category_price = RoomCategoryPrice::create([
                'room_id' => $id,
                'user_id' => $user->id,
                'category_price' => $category_price->id
            ]);

        }
        catch(\Exception $e){
            return response()->json(['status'=>$e->getMessage()]);
        }

        $status = "Data created successfully";
        
        return response()->json(compact('category_price', 'status'));

    }

    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $category_price = DB::table('category_price')
        ->where('user_id', '=', $user->id)
        ->where('id', '=', $id)
        ->first();

        if(empty($category_price)){

            return response()->json([ 'status' => "Data doesn't exist"]); 
        }

        if($request->get('name')==NULL){

            $name = $category_price->name;

        } else{

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255'
            ]);

            if($validator->fails()){
                return response()->json(['status' => $validator->errors()->toJson()], 400);
            }
            $name = $request->get('name');

        }

        $category_price->update([
            'name'=>$name
        ]);

        return response()->json([ 'status' => "Update Successfully"]); 

    }

    public function destroy($id) {

        $user = JWTAuth::parseToken()->authenticate();

        $category_price = DB::table('category_price')
        ->where('user_id', '=', $user->id)
        ->where('id', '=', $id)
        ->first();

        if(empty($category_price)){

            return response()->json([ 'status' => "Data doesn't exist"]); 
        }

        $category_price->delete();

        return response()->json([ 'status' => "delete successfully"]);

    }
}
