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
        'secret_key'
    ]);

    $appKey = TypeOf::alphanum(
        'App Key',
        $request->payload()->app_key
    );

    $secretKey = TypeOf::alphanum(
        'App Key',
        $request->payload()->secret_key
    );

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api.yotpo.com/core/v3/stores/'.$appKey.'/access_tokens',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_AUTOREFERER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode([
            'secret' => $secretKey
        ]),
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json'
        ]
    ]);

    $success = curl_exec($ch);
    $error = curl_error($ch);

    $result = json_decode($success,TRUE);

    if (!isset($result['access_token'])) {
        throw new UnauthorizedAccessException(
            'Unauthorized'
        );
        exit();
    }

    $reqId = UniqueId::create32BitKey(UniqueId::BETANUMERIC);
    $dataPath = ROOT.'/data/yotpo/'.$reqId;
    file_put_contents($dataPath,$result['access_token']);

    $token = new Token();
    $token->payload(['reqid'=>$reqId]);
    $token->create();

    Response::transmit([
        'payload' => [
            'status'=>'200 OK',
            'message' => 'authenticated',
            'token' => $token->get(),
            'exp' => '7min'
        ]
    ]);



} catch (\Exception $e) {
    if ($e instanceof \core\exceptions\RocketExceptionsInterface) {
        Response::transmit([
            'code' => $e->code(),

            # Provides only generic error message
            'exception' => 'RocketExceptionsInterface::'.$e->exception(),

            # Allows you to see the exact error message passed on the throw statement
            // 'exception'=>$e->getMessage()
        ]);
        exit();
    }
    Response::transmit([
        'code' => 400,
        'exception' => 'Unhandled Exception'
    ]);
}
