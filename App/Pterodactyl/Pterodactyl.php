<?php
namespace App\Pterodactyl;

use GuzzleHttp\Client;
use App\Pterodactyl\Services\ServerService;
use App\Pterodactyl\Services\UserService;
use App\Pterodactyl\Services\NodeService;
use App\Pterodactyl\Services\EggService;

class Pterodactyl
{
    private Client $client;
    public ServerService $servers;
    public UserService $users;
    public NodeService $nodes;
    public EggService $eggs;

    public function __construct(string $apiKey, string $baseUrl)
    {
        $this->client = new Client([
            'base_uri' => rtrim($baseUrl, '/') . '/api/',
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'Application/json',
            ],
            'timeout' => 10,
        ]);

        $this->servers = new ServerService($this->client);
        $this->users   = new UserService($this->client);
        $this->nodes   = new NodeService($this->client);
        $this->eggs    = new EggService($this->client);
    }
}
