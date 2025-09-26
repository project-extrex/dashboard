<?php

use App\Database\Entities\Products;
use App\Database\Entities\User;

$router->get("/shop", 
function () use 
($renderer, $entityManager) 
{
  $products = $entityManager->getRepository(Products::class)->findAll();
  $msg = $_GET["msg"] ?? null;
  $error = $_GET["error"] ?? null;
  $renderer->view("shop", ["products" => $products, "msg" => $msg, "error" => $error]);
});

$router->post("/purchase", function () use ($entityManager) {
    if (empty($_SESSION["user_id"])) {
        header("Location: /login?redirect=/shop");
        exit;
    }

    // Validate product ID
    $productId = $_POST["id"] ?? null;
    if (!$productId) {
        header("Location: /shop?msg=Invalid+product+selected");
        exit;
    }

    // Get user + product
    $user = $entityManager->find(User::class, $_SESSION["user_id"]);
    $product = $entityManager->find(Products::class, $productId);

    if (!$user || !$product) {
        header("Location: /shop?msg=Invalid+user+or+product");
        exit;
    }

    $price = $product->getPrice();

    // Check balance
    if ($user->getResources()->getCoins() < $price) {
        header("Location: /shop?msg=Not+enough+coins");
        exit;
    }

    // Deduct coins
    $user->getResources()->setCoins($user->getResources()->getCoins() - $price);

    // Persist changes
    $entityManager->persist($user);
    $entityManager->flush();

    header("Location: /shop?msg=Purchase+successful");
    exit;
});