<?php

use App\Exception\Base\ExceptionProcess;
use App\Process\Options;
use GuzzleHttp\Psr7\ServerRequest;
use Suolong\Psr15\Handle;
use App\Process\WithConf;
use App\Process\ParseBody;
use Suolong\Psr15Router\RouteNotFound;
use Suolong\Validator\ValidateFail;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../include.php';
require_once __DIR__ . '/../route.php';

$handle = new Handle([
    new WithConf,
    new ExceptionProcess,
    new RouteNotFound,
    new ValidateFail,
    new Options, 
    new ParseBody, 
    $router
]);

function clearUpException($e)
{
    if (CONF['app']['debug'] === true)
    {
        http_response_code(500);
        throw $e;
    }

    http_response_code(500);
    exit('服务器请求异常');
}

function clearUpError($errno, $errstr, $errfile, $errline)
{
    if (CONF['app']['debug'] === true)
    {
        $template = <<<EOF
        出错文件: %s
        对应行号: %s
        错误编号: %s
        错误信息: %s
        EOF;
        
        http_response_code(500);
        exit(sprintf(
            $template,
            $errfile,
            $errline,
            $errno,
            $errstr
        ));
    }
}

set_error_handler('clearUpError');
set_exception_handler('clearUpException');

$request = ServerRequest::fromGlobals();
$result  = $handle->handle($request);
$code    = $result->getStatusCode();
$content = $result->getBody()->getContents();
$headers = $result->getHeaders();

foreach($headers as $header => $values)
{
    header(sprintf("%s: %s", $header, implode(',', $values)));
}

http_response_code($code);
exit($content);