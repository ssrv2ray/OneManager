<?php
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include 'vendor/autoload.php';
include 'conststr.php';
include 'common.php';

//echo '<pre>'. json_encode($_SERVER, JSON_PRETTY_PRINT).'</pre>';
if (isset($_SERVER['USER'])&&$_SERVER['USER']==='qcloud') {
    if (getenv('ONEMANAGER_CONFIG_SAVE')=='file') include 'platform/TencentSCF_file.php';
    else include 'platform/TencentSCF_env.php';
} elseif (isset($_SERVER['FC_SERVER_PATH'])&&$_SERVER['FC_SERVER_PATH']==='/var/fc/runtime/php7.2') {
    include 'platform/AliyunFC.php';
} elseif ($_SERVER['_APP_SHARE_DIR']=='/var/share/CFF/processrouter') {
    //if (getenv('ONEMANAGER_CONFIG_SAVE')=='file') include 'platform/HuaweiFG_file.php';
    //else include 'platform/HuaweiFG_env.php';
    echo 'FG' . PHP_EOL;
} elseif ($_SERVER['BCE_CFC_RUNTIME_NAME']=='php7') {
    include 'platform/BaiduCFC.php';
} elseif (isset($_SERVER['HEROKU_APP_DIR'])&&$_SERVER['HEROKU_APP_DIR']==='/app') {
    include 'platform/Heroku.php';
    $path = getpath();
    //echo 'path:'. $path;
    $_GET = getGET();
    //echo '<pre>'. json_encode($_GET, JSON_PRETTY_PRINT).'</pre>';
    $re = main($path);
    $sendHeaders = array();
    foreach ($re['headers'] as $headerName => $headerVal) {
        header($headerName . ': ' . $headerVal, true);
    }
    http_response_code($re['statusCode']);
    echo $re['body'];
} else {
    include 'platform/Normal.php';
    $path = getpath();
    //echo 'path:'. $path;
    $_GET = getGET();
    //echo '<pre>'. json_encode($_GET, JSON_PRETTY_PRINT).'</pre>';

    $re = main($path);
    $sendHeaders = array();
    foreach ($re['headers'] as $headerName => $headerVal) {
        header($headerName . ': ' . $headerVal, true);
    }
    http_response_code($re['statusCode']);
    echo $re['body'];
}

// Tencent SCF
function main_handler($event, $context)
{
    $event = json_decode(json_encode($event), true);
    $context = json_decode(json_encode($context), true);
    printInput($event, $context);
    unset($_POST);
    unset($_GET);
    unset($_COOKIE);
    unset($_SERVER);
    GetGlobalVariable($event);
    //echo '<pre>'. json_encode($_COOKIE, JSON_PRETTY_PRINT).'</pre>';
    $path = GetPathSetting($event, $context);

    return main($path);
}

// Aliyun FC & Huawei FG & Baidu CFC
function handler($event, $context)
{
    if (isset($_SERVER['FC_SERVER_PATH'])&&$_SERVER['FC_SERVER_PATH']==='/var/fc/runtime/php7.2') {
        // Aliyun FC
        set_error_handler("myErrorHandler");
        $tmp = array(
            'method' => $event->getMethod(),
            'clientIP' => $event->getAttribute("clientIP"),
            'requestURI' => $event->getAttribute("requestURI"),
            'path' => spurlencode($event->getAttribute("path"), '/'),
            'queryString' => $event->getQueryParams(),
            'headers' => $event->getHeaders(),
            'body' => $event->getBody()->getContents(),
        );
        $event = $tmp;
        $context = json_decode(json_encode($context), true);
        printInput($event, $context);
        unset($_POST);
        unset($_GET);
        unset($_COOKIE);
        unset($_SERVER);
        GetGlobalVariable($event);
        $path = GetPathSetting($event, $context);

        $re = main($path);

        return new RingCentral\Psr7\Response($re['statusCode'], $re['headers'], $re['body']);

    } elseif ($_SERVER['_APP_SHARE_DIR']=='/var/share/CFF/processrouter') {
        // Huawei FG
        global $contextUserData;
        $contextUserData = $context;
        if ($context->getUserData('ONEMANAGER_CONFIG_SAVE')=='file') include_once 'platform/HuaweiFG_file.php';
        else include_once 'platform/HuaweiFG_env.php';

        $event = json_decode(json_encode($event), true);
        if ($event['isBase64Encoded']) $event['body'] = base64_decode($event['body']);

        printInput($event, $context);
        unset($_POST);
        unset($_GET);
        unset($_COOKIE);
        unset($_SERVER);
        GetGlobalVariable($event);
        //echo '<pre>'. json_encode($_COOKIE, JSON_PRETTY_PRINT).'</pre>';
        $path = GetPathSetting($event, $context);

        return main($path);

    } elseif ($_SERVER['BCE_CFC_RUNTIME_NAME']=='php7') {
        // Baidu CFC
        //$html = '<pre>'. json_encode($event, JSON_PRETTY_PRINT).'</pre>';
        //$html .= '<pre>'. json_encode($context, JSON_PRETTY_PRINT).'</pre>';
        //$html .= '<pre>'. json_encode($_SERVER, JSON_PRETTY_PRINT).'</pre>';
        //$html .= $event['path'];
        //$html .= $context['functionBrn'];
        //return json_encode(output($html), JSON_FORCE_OBJECT);

        printInput($event, $context);
        unset($_POST);
        unset($_GET);
        unset($_COOKIE);
        unset($_SERVER);
        GetGlobalVariable($event);
        //echo '<pre>'. json_encode($_COOKIE, JSON_PRETTY_PRINT).'</pre>';
        $path = GetPathSetting($event, $context);

        return json_encode(main($path), JSON_FORCE_OBJECT);

    }
}