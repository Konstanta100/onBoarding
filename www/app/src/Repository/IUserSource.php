<?php


namespace App\Repository;


use App\Entity\User;

interface IUserSource
{
    public function findByEmail(string $email);

    public function findById(int $userId);

    public function save(User $user);

    public function delete(User $user);
}