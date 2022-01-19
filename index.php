<?php
define("BASEPATH", __DIR__);
require_once BASEPATH ."/helper/myHelper.php";
require_once BASEPATH . "/vendor/autoload.php";
require_once BASEPATH."/library/upload.php";

$upload = new Upload();
$url = $_SERVER["REQUEST_URI"];
$uri = explode("/", parse_url($url, PHP_URL_PATH));
$base_url = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . preg_replace('@/+$@', '', dirname($_SERVER['SCRIPT_NAME'])) . '/';
$whitelist = whiteListIp();

if (isset($uri[3])) {
    switch ($uri[3]) {
        case "upload_file":
            if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
                $upload->singleFile("image");
            }else{
                forbidden();
            }
            break;
        case "images":
            if (isset($uri[4]) && strlen($uri[4]) > 0) {
                $file = __DIR__."/images/".$uri[4];
                if (file_exists($file)) {
                    // header("location:".$base_url."/index.php/images/".$uri[4]);
                    echo '<img src="'.$base_url."images/".$uri[4].'" />';
                }
            }else{
                notFound();
            }

            break;
        default:
            notFound();
            break;
    }
}else{
    notFound();
}
