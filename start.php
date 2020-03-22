<?php
error_reporting(0);

use App\Exception\Base\ExceptionProcess;
use App\Process\Options;
use GuzzleHttp\Psr7\ServerRequest;
use Suolong\Psr15\Handle;
use App\Process\WithConf;
use App\Process\ParseBody;
use Suolong\Psr15Router\RouteNotFound;
use Suolong\Validator\ValidateFail;

$handle = new Handle([
    new WithConf,
    new ExceptionProcess,
    new RouteNotFound,
    new ValidateFail,
    new Options, 
    new ParseBody, 
    $router
]);

function clearUpException(Exception $e)
{
    http_response_code(500);
    exit($e->getMessage());
}

function clearUpError($errno, $errstr, $errfile, $errline)
{
    http_response_code(500);
    if (CONF['app']['debug'] === true)
    {
        $template = <<<EOF
        出错文件: %s
        对应行号: %s
        错误编号: %s
        错误信息: %s
        EOF;
        exit(sprintf(
            $template,
            $errfile,
            $errline,
            $errno,
            $errstr
        ));
    }
    exit('服务器请求异常');
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