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
    RequireApiEndpoint::method('POST');

    # Require API payload
    RequireApiEndpoint::payload([
        'app_key',
        'product_ids',
        'token'
    ]);

    /**
     * First, we make sure that the correct data types
     * are being requested
     */
    $appKey = TypeOf::alphanum(
        'App Key',
        $request->payload()->app_key
    );

    if (trim($request->payload()->product_ids)==='') {
        throw new BadRequestException(
            'Product IDs field cannot be empty'
        );
    }

    $product_ids = explode(',',$request->payload()->product_ids);
    $external_ids = [];

    foreach ($product_ids as $product_id) {
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $product_id)) {
            throw new BadRequestException (
                'Invalid Product Id: '.$product_id
            );
        }
        array_push($external_ids,trim($product_id));
    }


    # Requester validation
    $jwt = new Token($request->payload()->token);

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

    $apiUrl = 'https://api.yotpo.com/core/v3/stores/'.$appKey.'/products?external_ids='.implode(',',$external_ids);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
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
            'products' => $result['products'],
            'next' => $result['pagination']['next_page_info'],
            'cached' => false
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
