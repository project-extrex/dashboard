<?php

$router->get("/checkout", function() use ($renderer) {
  global $stripe_publicable_key;
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST']; // e.g., localhost:8000
$successPath = "/success.php";

$returnUrl = $protocol . $host . $successPath; // http://localhost:8000/success.php
  $renderer->view("checkout", ["stripe_publicable_key" => $stripe_publicable_key, "redr" => $returnUrl]);
});

$router->post("/payment/create", function() use ($stripe_secret_key) {
    header('Content-Type: application/json');

    \Stripe\Stripe::setApiKey($stripe_secret_key);

    $amount = 2000; // $20.00 in cents, change dynamically if needed

    try {
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        echo json_encode([
            'clientSecret' => $paymentIntent->client_secret
        ]);

    } catch (\Stripe\Exception\ApiErrorException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
});