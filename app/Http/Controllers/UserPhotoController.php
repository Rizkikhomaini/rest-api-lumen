<?php

namespace App\Http\Controllers;

use App\UserPhoto;
use App\Helper;
use Illuminate\Http\Request;

class UserPhotoController extends Controller
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

    private $key_response = "photo";

    public function myPhoto(Request $request) {
        $data = UserPhoto::where('is_deleted', 0)->where('user_id', $request->auth->id)->get();
        return Helper::response(array($this->key_response => $data));
    }

    public function uploadPhoto(Request $request) {
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $user_id = $request->input('user_id');

            $data = UserPhoto::where('user_id', $user_id)->get();

            if (count($data) == 0) {
                $isPrimary = 1;
            } else {
                $isPrimary = 0;
            }
                
            // $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $milliseconds = round(microtime(true) * 1000);
            $fileName = $user_id . '_' . $milliseconds . '.' . $extension;
            $file->move(storage_path("/app/images/user_photo"), $fileName);

            $data = new UserPhoto();
            $data->user_id = $user_id;
            $data->photo = $fileName;
            $data->is_primary = $isPrimary;
            $data->save();

            $data = UserPhoto::where('id', $data->id)->get();

            return Helper::response(array($this->key_response => $data));
        }
    }

    public function setPrimary(Request $request) {
        $data = UserPhoto::all();
        foreach ($data as $d) {
            $d->is_primary = 0;
            $d->save();
        }

        $photoId = $request->input('id');

        $data = UserPhoto::where('id', $photoId)->first();
        $data->is_primary = 1;
        $data->save();

        $data = UserPhoto::where('id', $data->id)->get();
    
        return Helper::response(array($this->key_response => $data));
    }
    
    public function delete(Request $request) {
        $photoId = $request->input('id');
        $data = UserPhoto::where('id', $photoId)->first();
        $data->is_deleted = 1;
        $data->save();
        // $data->delete();
    
        return Helper::response(array($this->key_response => array()));
    }
}
