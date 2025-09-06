<?php
namespace App\Database\Repositories;

use App\Database\Entities\User;

class UserRepository extends BaseRepository {
    protected string $entityClass = User::class;

    public function findByUsername(string $username): ?User {
        return $this->repository->findOneBy(['username' => $username]);
    }

    public function findByEmail(string $email): ?User {
        return $this->repository->findOneBy(['email' => $email]);
    }

    public function findAdmins(): array {
        return $this->repository->findBy(['admin' => true]);
    }
}
