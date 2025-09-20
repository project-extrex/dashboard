<?php
namespace App\Pterodactyl\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Pterodactyl\Exceptions\ApiException;

class ServerService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $data): array
    {
        try {
            $res = $this->client->post('application/servers', ['json' => $data]);
            return json_decode($res->getBody(), true);
        } catch (RequestException $e) {
            throw new ApiException('Create server failed: ' . $e->getMessage());
        }
    }

    public function delete(string $id): bool
    {
        try {
            $this->client->delete("application/servers/{$id}");
            return true;
        } catch (RequestException $e) {
            throw new ApiException('Delete server failed: ' . $e->getMessage());
        }
    }

    public function suspend(string $id): array
    {
        try {
            $res = $this->client->post("application/servers/{$id}/suspend");
            return json_decode($res->getBody(), true);
        } catch (RequestException $e) {
            throw new ApiException('Suspend server failed: ' . $e->getMessage());
        }
    }

    public function update(string $id, array $data): array
    {
        try {
            $res = $this->client->patch("application/servers/{$id}", ['json' => $data]);
            return json_decode($res->getBody(), true);
        } catch (RequestException $e) {
            throw new ApiException('Update server failed: ' . $e->getMessage());
        }
    }
}
