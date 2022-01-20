<?php
date_default_timezone_set("Asia/Jakarta");
validIp($_SERVER["REMOTE_ADDR"]);

function whiteListIp(): array
{
    return $list = array(
        "::1",
        "localhost",
        "127.0.0.1",
        "be-klasterkuhidupku.ddb.dev.bri.co.id",

        // FE:
        "172.18.216.72",
        "172.18.216.114",

        // BE:
        "172.18.216.30",
        "172.18.216.83",

        // CDN:
        "172.18.216.53",
        "172.18.216.54",
    );
}

function validIp(string $ip)
{
    if (!in_array($ip, whiteListIp())) {
        forbidden();
    }
}

function forbidden()
{
    $log = new Log();
    $log->create("access forbidden");
    require BASEPATH . "/views/403.php";
    die;
}

function notFound()
{
    $log = new Log();
    $log->create("access forbidden");
    require BASEPATH . "/views/404.php";
    die;
}
