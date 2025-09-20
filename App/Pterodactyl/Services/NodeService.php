<?php
namespace App\Pterodactyl\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Pterodactyl\Exceptions\ApiException;

class NodeService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list(): array
    {
        try {
            $res = $this->client->get('application/nodes');
            return json_decode($res->getBody(), true);
        } catch (RequestException $e) {
            throw new ApiException('List nodes failed: ' . $e->getMessage());
        }
    }

    public function get(string $id): array
    {
        try {
            $res = $this->client->get("application/nodes/{$id}");
            return json_decode($res->getBody(), true);
        } catch (RequestException $e) {
            throw new ApiException('Get node failed: ' . $e->getMessage());
        }
    }
}
