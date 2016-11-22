<?php

class Information extends Eloquent {

    public static function get_value($key) {
        $data = Information::select('value')
            ->where('name', '=', $key)
            ->get();

        return $data;
    }

    public static function update_value($key, $value) {
        return Information::where('name', '=', $key)->update(['value' => $value]);
    }

}
