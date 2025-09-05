<?php
namespace App\Admin\Controllers;

class UserController extends AdminBaseController {
    public function index(): void {
        $users = $this->container->getUserRepository()->findAll();
        $this->render('users/index', [
            'title' => 'Users',
            'users' => $users
        ]);
    }

    public function create(): void {
        $this->render('users/create', [
            'title' => 'Create User'
        ]);
    }

    public function store(): void {
        // TODO: Implement user creation
    }

    public function edit(int $id): void {
        $user = $this->container->getUserRepository()->find($id);
        if (!$user) {
            // Handle 404
            return;
        }

        $this->render('users/edit', [
            'title' => 'Edit User',
            'user' => $user
        ]);
    }

    public function update(int $id): void {
        // TODO: Implement user update
    }

    public function delete(int $id): void {
        // TODO: Implement user deletion
    }
}
