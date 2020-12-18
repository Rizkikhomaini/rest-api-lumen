<?php

namespace App;

class Helper {

    public static function response($data, $status = 200, $response_desc = "Sukses") {
        $structure = array();
        $structure["response_code"] = $status;
        $structure["response_desc"] = $response_desc;
        $structure["response_data"] = $data;
        return response($structure, 200);
    }
}