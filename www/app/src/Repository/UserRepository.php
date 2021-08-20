<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserSourceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param User $user
     */
    public function delete(User $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }

    /**
     * @param string $userId
     * @return User|null
     */
    public function findById(string $userId): ?User
    {
        return $this->find($userId);
    }
}
