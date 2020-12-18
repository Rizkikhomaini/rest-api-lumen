<?php

namespace App\Http\Controllers;

use App\User;
use App\Country;
use App\Helper;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //  
    }

    var $key_response = "user";

    public function myProfile(Request $request) {
        $data = User::where('id', $request->auth->id)->first();
        $countryName = Country::where('id', $data->country_id)->first();
        $data['country'] = $countryName;
        return Helper::response(array($this->key_response => $data));
    }

    public function all() {
        $data = User::where('is_deleted', 0)->get();
        return Helper::response(array($this->key_response => $data));
    }

    public function byId($id) {
        $data = User::where('id', $id)->get();
        return Helper::response(array($this->key_response => $data));
    }
    
    public function byUserId($id) {
        $data = User::where('user_id', $id)->get();
        return Helper::response(array($this->key_response => $data));
    }

    public function store(Request $request) {

        $this->validate($request, [
            'email' => 'required|string',
            'firebase_uuid' => 'required|string'
        ]);

        $data = new User();
        $data->firebase_uuid = $request->firebase_uuid;
        $data->save();

        $data = User::where('id', $data->id)->first();
    
        return Helper::response(array($this->key_response => $data));
    }

    public function update(Request $request, $id) {
        $data = User::where('id', $id)->first();
        $data->content = $request->input('content');
        $data->is_editted = 1;
        $data->save();

        $data = User::where('id', $data->id)->first();
    
        return Helper::response(array($this->key_response => $data));
    }

    public function updatePhotoProfileRegistration(Request $request, $id) {
        $data = User::where('id', $id)->first();
        $data->content = $request->input('content');
        $data->is_editted = 1;
        $data->save();

        $data = User::where('id', $data->id)->first();
    
        return Helper::response(array($this->key_response => $data));
    }
    
    public function destroy($id){
        $data = User::where('id', $id)->first();
        $data->is_deleted = 1;
        $data->save();
        // $data->delete();
    
        return Helper::response(array($this->key_response => array()));
    }
}
