<?php

if (!function_exists('arr_val')) {
    function arr_val($array, $key, $default = null)
    {
        if (!$key || !is_array($array)) {
            return $default;
        }
        
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        $arr  =& $array;

        foreach(explode('.', $key) as $segment) {
            
            if (!array_key_exists($segment, $arr)) {
                return $default;
            }

            $arr =& $arr[$segment];
        
        }

        return $arr;
    }
}


if (!function_exists('arr_val_set')) {
    function arr_val_set(array &$array, $key, $value)
    {
        $arr =& $array;
        $segments = explode('.', $key);
        while (count($segments) > 1)
        {
            $segment = array_shift($segments);
            if ( ! isset($arr[$segment]) || ! is_array($arr[$segment])) {
                $arr[$segment] = array();
            }
            $arr =& $arr[$segment];
        }
        $arr[array_shift($segments)] = $value;

        return $array;
    }
}


if (!function_exists('arr_val_unset')) {
    function arr_val_unset(array &$array, $key)
    {
        if (!$key || !is_array($array)) {
            return false;
        }
        
        if (array_key_exists($key, $array)) {
            unset($array[$key]);
            return $array;
        }

        $parts = explode(".", $key);
        $arr   =& $array;

        while (count($parts) > 1) {
            $part = array_shift($parts);

            if (isset($arr[$part]) and is_array($arr[$part])) {
                $arr =& $arr[$part];
            }
        }

        unset($arr[array_shift($parts)]);
        return $array;
    }
}


if (!function_exists('dd')) {
    function dd()
    {
        echo "<pre>";
        foreach(func_get_args() as $obj) {
            var_dump($obj);
        }
        exit;
    }
}


if (!function_exists('get_file_type')) {
    
    function get_file_type($filename) 
    {
        $ext  = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $exts = [
            'image'      => ['jpg', 'gif', 'tiff', 'png', 'bmp', 'svg'],
            'text'       => ['doc', 'docx', 'txt', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx', 'xml'],
            'video'      => ['mpg', 'mp4', 'avi', 'divx', 'mkv', '3gp', 'm4v', 'ogg', 'asf'],
            'audio'      => ['wav', 'mp3', 'm4a'],
            'executable' => ['exe'],
            'archive'    => ['gz', 'tar', 'zip', 'lhz'],
        ];

        foreach($exts as $name => $list) {
            if (in_array($ext, $list)) {
                return $name;
            }
        }

        return 'misc';
    }

}


if (!function_exists('human_filesize')) {
    
    function human_filesize($bytes, $decimals = 2)
    {
        $sz     = ['','K','M','G','T','P'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $sz[$factor] . 'b';
    }

}