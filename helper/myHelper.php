<?php

validIp($_SERVER["REMOTE_ADDR"]);

function whiteListIp(): array
{
    return $list = array(
        "::1",
        "localhost",
        "127.0.0.1",
        "be-klasterkuhidupku.ddb.dev.bri.co.id"
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
