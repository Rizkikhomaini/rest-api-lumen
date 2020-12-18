<?php



namespace App\Http\Controllers;



use App\Admin;

use App\Helper;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;



class AdminController extends Controller

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



    var $key_response = "admin";



    public function login(Request $request)

    {

        $data = Admin::where('email', $request->email)->first();

        if ($data) {

            if (Hash::check($request->password, $data->password)) {

                return Helper::response(array($this->key_response => $data));
            } else {

                return Helper::response(array($this->key_response => array("error" => "Password salah.")), 401, "Password salah.");
            }
        } else {

            return Helper::response(array($this->key_response => array("error" => "Email tidak ada.")), 401, "Email tidak ada.");
        }
    }



    public function store(Request $request)

    {

        $data = Admin::where('email', $request->email)->first();

        if (!$data) {

            $data = new Admin();

            $data->email = $request->email;

            $data->nama_admin = $request->nama_admin;

            $data->password = Hash::make($request->password);

            $data->type = $request->type;

            $data->save();



            $data = Admin::where('id', $data->id)->first();



            return Helper::response(array($this->key_response => $data));
        } else {
            return Helper::response(array($this->key_response => array("error" => "Email sudah terpakai.")), 401, "Email sudah terpakai.");
        }
    }
}
