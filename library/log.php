<?php

class Log
{
    function __construct()
    {
        $this->_path_log = BASEPATH . "/logs";
    }

    function create(string $message)
    {
        if (!file_exists($this->_path_log)) {
            mkdir($this->_path_log, 0755);
        }

        $today = date('Y-m-d');
        $pathfile = $today . '.json';
        $json = [];
        if (!file_exists($this->_path_log . "/" . $pathfile)) {
            $json[] = array(
                'path' => $_SERVER["REQUEST_URI"],
                'request' => $_SERVER,
                'created_at' => date('Y-m-d H:i:s'),
                'message' => $message
            );

            $fp = fopen($this->_path_log . "/" . $pathfile, 'w');
            fwrite($fp, json_encode($json, JSON_PRETTY_PRINT));
            fclose($fp);
        } else {
            $json = file_get_contents($this->_path_log . "/" . $pathfile);
            $array = json_decode($json, true);
            $array[] = array(
                'path' => $_SERVER["REQUEST_URI"],
                'request' => $_SERVER,
                'created_at' => date('Y-m-d H:i:s'),
                'message' => $message
            );
            $fp = fopen($this->_path_log . "/" . $pathfile, 'w');
            fwrite($fp, json_encode($array, JSON_PRETTY_PRINT));
            fclose($fp);
        }
    }
}
