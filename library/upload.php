<?php

class Upload
{
    function __construct()
    {
        $this->_max_size = 500;
        $this->_allow_img = array("jpg", "jpeg", "png");
        $this->_path_images = BASEPATH."/images/";
        $this->log = new Log();
        
    }

    function isImageExt(string $val): bool
    {
        $result = false;
        if ($val == "image") {
            $result = true;
        }

        return $result;
    }

    function getExtFile(string $ext)
    {
        $exp = explode("/", $ext);
        if (isset($exp[0]) && $this->isImageExt($exp[0])) {
            return $exp[1];
        } else {
            return false;
        }
    }

    function uploadMsg(int $index): string
    {
        $msg = array(
            "There is no error, the file uploaded with success",
            "The uploaded file exceeds the upload_max_filesize directive in php.ini",
            "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
            "The uploaded file was only partially uploaded",
            "No file was uploaded",
            "Missing a temporary folder",
        );
        return $msg[$index];
    }

    function singleFile(string $value)
    {
        header("Content-Type: application/json");
        $filename = md5(date("YmdHis")) . ".jpg";

        if (!isset($_FILES[$value]["tmp_name"])) {
            http_response_code(400);
            $json = array("status" => false, "message" => "invalid request");
            $this->log->create($json["message"]);
            echo json_encode($json);
            die;
        }

        $tmp = $_FILES[$value]["tmp_name"];

        if (is_array($tmp) && count($tmp) > 0) {
            http_response_code(400);
            $json = array("status" => false, "message" => "multiple upload not permitted");
            $this->log->create($json["message"]);
            echo json_encode($json);
            die;
        }

        $fileSize = $_FILES[$value]["size"] / 1024;
        if ($fileSize > $this->_max_size) {
            http_response_code(400);
            $json = array("status" => false, "message" => "file is too large, maximum file is 500kb");
            $this->log->create($json["message"]);
            echo json_encode($json);
            die;
        }
        $ext = $this->getExtFile($_FILES[$value]["type"]);
        if (!$ext || !in_array($ext, $this->_allow_img)) {
            http_response_code(400);
            $json = array("status" => false, "message" => "file extension not support");
            $this->log->create($json["message"]);
            echo json_encode($json);
            die;
        }

        if (!file_exists($this->_path_images)) {
            mkdir($this->_path_images, 0755);
        }

        $upload = move_uploaded_file($tmp, $this->_path_images . $filename);

        if ($upload) {
            http_response_code(200);
            $json = array("status" => true, "message" => "upload success");
        } else {
            http_response_code(500);
            $json = array("status" => false, "message" => $this->uploadMsg($_FILES[$value]["error"]));
            $this->log->create($json["message"]);
        }

        echo json_encode($json);
    }
}
