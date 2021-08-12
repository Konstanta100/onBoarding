<?php


namespace App\Repository;


use App\Entity\User;

interface UserSourceInterface
{
    public function findByEmail(string $email);

    public function findById(string $userId);

    public function save(User $user);

    public function delete(User $user);
}