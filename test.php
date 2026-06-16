<?php

$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
]);

$fp = stream_socket_client(
    'tcp://smtp-relay.brevo.com:587',
    $errno,
    $errstr,
    10,
    STREAM_CLIENT_CONNECT,
    $context
);

echo fgets($fp);
fwrite($fp, "EHLO localhost\r\n");
echo fgets($fp);

fwrite($fp, "STARTTLS\r\n");
echo fgets($fp);

$result = stream_socket_enable_crypto(
    $fp,
    true,
    STREAM_CRYPTO_METHOD_TLS_CLIENT
);

var_dump($result);

while ($msg = openssl_error_string()) {
    echo $msg . PHP_EOL;
}