<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    public function createUser(User $user): bool
    {
        if (true === $this->activateUserIfInDb($user)) {
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();

            return true;
        }

        return false;
    }

    public function activateUserIfInDb(User $user): bool
    {
        $userEntity = $this->findBy(['username' => $user->getUsername(), 'email' => $user->getEmail()]);
        if (empty($userEntity)) {
            return true;
        }

        return false;
    }

    public function getUserMode(int $userId): bool
    {
        return $this->find($userId)->getDarkMode();
    }

    public function getMainColumn(int $userId): bool
    {
        return $this->find($userId)->getMainColumn();
    }

    public function getFriendColumn(int $userId): bool
    {
        return $this->find($userId)->getFriendColumn();
    }

    public function getChatColumn(int $userId): bool
    {
        return $this->find($userId)->getChatColumn();
    }

    public function getUserByUsername(string $username): ?User
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.username = :username and u.isVerified = true')
            ->setParameter('username', $username)
            ->orderBy('u.username', 'asc')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getAllOtherUsersWithPage(string $username, int $page): array
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.username != :username and u.isVerified = true')
            ->setParameter('username', $username)
            ->orderBy('u.username', 'asc')
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function getSearchedUsersWithPage(string $username, string $query, int $page): array
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('LOWER(u.username) LIKE :query and LOWER(u.username) != :username and u.isVerified = true')
            ->setParameter('query', '%' . $query . '%')
            ->setParameter('username', $username)
            ->orderBy('u.username', 'asc')
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function updateUserRepo(User $user): bool
    {
        try {
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function activateUser(string $email): void
    {
        $queryBuilder = $this->createQueryBuilder('updateUser');
        $query = $queryBuilder->update(User::class, 'u')
            ->set('u.isVerified', ':value')
            ->where('u.email = :email')
            ->setParameter('value', true)
            ->setParameter('email', $email)
            ->getQuery();
        $rows = $query->execute();
    }
}
