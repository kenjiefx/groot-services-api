<?php

declare(strict_types=1);
# Common libraries
use \core\http\Request;
use \core\http\Response;
use \core\exceptions\UnauthorizedAccessException;
use \core\exceptions\BadRequestException;
use \core\exceptions\AlreadyExistsException;
use \core\exceptions\ConfigurationErrorException;
use \core\exceptions\RecordNotFoundException;
use \core\exceptions\ResourceAccessForbiddenException;
use \jwt\Token;
use \glyphic\RequireApiEndpoint;
use \glyphic\TimeStamp;
use \glyphic\TypeOf;
use \glyphic\UniqueId;

require $_SERVER['DOCUMENT_ROOT'].'/imports.php';
$request = new Request;
$response = new Response;

try {

    # Require headers
    RequireApiEndpoint::header();

    # Require API Method endpoint
    RequireApiEndpoint::method('GET');

    # Require API payload
    RequireApiEndpoint::query([
        'token',
        'app_key'
    ]);

    $appKey = TypeOf::alphanum(
        'App Key',
        $request->query()->app_key
    );

    header('Content-type: application/json');
    echo file_get_contents(__dir__.'/tmp.json');

    exit();

    # Requester validation
    $jwt = new Token($request->query()->token);

    if (!$jwt->isValid()) {
        throw new UnauthorizedAccessException(
            'Token provided is either expired or invalid'
        );
    }

    $payload = $jwt->payload();
    $reqId = $payload['reqid'] ?? 'invalid';
    $uTokenPath = ROOT.'/data/yotpo/'.$reqId;

    if (!file_exists($uTokenPath)) {
        throw new UnauthorizedAccessException(
            'Request is unauthorized'
        );
    }

    $uToken = file_get_contents($uTokenPath);

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api.yotpo.com/core/v3/stores/'.$appKey.'/products',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_AUTOREFERER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Yotpo-Token: '.$uToken
        ]
    ]);

    $success = curl_exec($ch);
    $error = curl_error($ch);

    $result = json_decode($success,TRUE);

    if (!isset($result['products'])) {
        throw new ConfigurationErrorException(
            'Internal Server error'
        );
        exit();
    }

    Response::transmit([
        'payload' => [
            'status'=>'200 OK',
            'products' => $result['products']
        ]
    ]);



} catch (\Exception $e) {
    if ($e instanceof \core\exceptions\RocketExceptionsInterface) {
        Response::transmit([
            'code' => $e->code(),

            # Provides only generic error message
            //'exception' => 'RocketExceptionsInterface::'.$e->exception(),

            # Allows you to see the exact error message passed on the throw statement
            'exception'=>$e->getMessage()
        ]);
        exit();
    }
    Response::transmit([
        'code' => 400,
        'exception' => 'Unhandled Exception'
    ]);
}
