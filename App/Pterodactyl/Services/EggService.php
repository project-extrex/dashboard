<?php
namespace App\Pterodactyl\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Pterodactyl\Exceptions\ApiException;

class EggService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list(): array
    {
        try {
            $res = $this->client->get('application/eggs');
            return json_decode($res->getBody(), true);
        } catch (RequestException $e) {
            throw new ApiException('List eggs failed: ' . $e->getMessage());
        }
    }

    public function get(string $id): array
    {
        try {
            $res = $this->client->get("application/eggs/{$id}");
            return json_decode($res->getBody(), true);
        } catch (RequestException $e) {
            throw new ApiException('Get egg failed: ' . $e->getMessage());
        }
    }
}
