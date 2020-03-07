<?php

use App\Exception\Base\ExceptionProcess;
use App\Process\Options;
use App\Process\ParseBody;
use App\Process\WithConf;
use GuzzleHttp\Psr7\ServerRequest;
use Suolong\Psr15\Handle;
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

try {
    $request = ServerRequest::fromGlobals();
    $result = $handle->handle($request);
    $code = $result->getStatusCode();
    $content = $result->getBody()->getContents();
    $headers = $result->getHeaders();

    foreach ($headers as $header => $values) {
        header(sprintf("%s: %s", $header, implode(',', $values)));
    }

    http_response_code($code);
    exit($content);
} catch (Exception $e) {
    if (CONF['app']['debug'] === true) {
        throw $e;
    }

    http_response_code(500);
    exit('服务器请求异常');
}