<?php
namespace App\Pterodactyl\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Pterodactyl\Exceptions\ApiException;

class UserService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list(): array
    {
        try {
            $res = $this->client->get('application/users');
            return json_decode($res->getBody(), true);
        } catch (RequestException $e) {
            throw new ApiException('List users failed: ' . $e->getMessage());
        }
    }

    public function get(string $id): array
    {
        try {
            $res = $this->client->get("application/users/{$id}");
            return json_decode($res->getBody(), true);
        } catch (RequestException $e) {
            throw new ApiException('Get user failed: ' . $e->getMessage());
        }
    }

    public function create(array $data): array
    {
        try {
            $res = $this->client->post('application/users', ['json' => $data]);
            return json_decode($res->getBody(), true);
        } catch (RequestException $e) {
            throw new ApiException('Create user failed: ' . $e->getMessage());
        }
    }

    public function update(string $id, array $data): array
    {
        try {
            $res = $this->client->patch("application/users/{$id}", ['json' => $data]);
            return json_decode($res->getBody(), true);
        } catch (RequestException $e) {
            throw new ApiException('Update user failed: ' . $e->getMessage());
        }
    }

    public function delete(string $id): bool
    {
        try {
            $this->client->delete("application/users/{$id}");
            return true;
        } catch (RequestException $e) {
            throw new ApiException('Delete user failed: ' . $e->getMessage());
        }
    }
}
