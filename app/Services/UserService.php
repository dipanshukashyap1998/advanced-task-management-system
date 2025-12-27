<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users.
     *
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return Cache::remember('users', 3600, function () {
            return $this->userRepository->all();
        });
    }

    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getUserById(int $id): User
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("User not found");
        }
        return $user;
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        $user = $this->userRepository->create($data);
        Cache::forget('users');
        return $user;
    }

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function updateUser(int $id, array $data): User
    {
        $user = $this->getUserById($id); // This will throw if not found
        $this->userRepository->update($id, $data);
        Cache::forget('users');
        return $user->fresh();
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function deleteUser(int $id): void
    {
        if (!$this->userRepository->delete($id)) {
            throw new \Exception('User not found');
        }
        Cache::forget('users');
    }
}