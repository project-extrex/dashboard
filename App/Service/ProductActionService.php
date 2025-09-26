<?php

namespace App\Services;

use App\Database\Entities\Products;

class ProductActionService
{
    public function execute(Products $product, $userId): void
    {
        $action = $product->getAction();
        $params = $product->getActionParams();

        switch ($action) {
            case 'CreateServer':
                $this->createServer($userId, $params);
                break;

            case 'AddResources':
                $this->addResources($userId, $params);
                break;

            default:
                throw new \Exception("Unknown product action: $action");
        }
    }

    private function createServer($userId, $params)
    {
        // Call your server creation logic here
        // e.g., $params could contain server type, template, etc.
    }

    private function addResources($userId, $params)
    {
        // Add resources to the user's account
        // $params['resource'] = 'RAM', $params['amount'] = 2GB, etc.
    }
}