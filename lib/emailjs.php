<?php

/** Sends an EmailJS template through the REST API. */
function sendEmailJsTemplate(string $serviceId, string $templateId, string $publicKey, array $templateParams): bool
{
    $payload = json_encode(array(
        'service_id' => $serviceId,
        'template_id' => $templateId,
        'user_id' => $publicKey,
        'template_params' => $templateParams,
    ));

    if ($payload === false) {
        return false;
    }

    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => $payload,
            'timeout' => 15,
            'ignore_errors' => true,
        ),
    ));

    $response = @file_get_contents('https://api.emailjs.com/api/v1.0/email/send', false, $context);
    $statusLine = $http_response_header[0] ?? '';

    return $response !== false && strpos($statusLine, ' 200 ') !== false;
}
