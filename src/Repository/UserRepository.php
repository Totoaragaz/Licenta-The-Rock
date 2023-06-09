<?php

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

    public function deleteUser(int $userId): void
    {
        $this->getEntityManager()->remove($this->find($userId));
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

    public function getAllOtherUsers(string $username): ?array
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.username != :username and u.isVerified = true')
            ->setParameter('username', $username)
            ->orderBy('u.username', 'asc')
            ->getQuery()
            ->getResult();
    }

    public function getSearchedUsers(string $username, string $query): ?array
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('LOWER(u.username) LIKE :query and LOWER(u.username) != :username and u.isVerified = true')
            ->setParameter('query', '%' . $query . '%')
            ->setParameter('username', $username)
            ->orderBy('u.username', 'asc')
            ->getQuery()
            ->getResult();
    }

    public function updateUserRepo(User $user): bool
    {
        try {
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
            return true;
        } catch (Exception $exception) {
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

    public function getAllEmployees(string $query): array
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->innerJoin('u.hotels', 'h')
            ->andWhere($query)
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    public function getEmailVerification(string $email): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.isVerified')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
    }

    public function getUserWithEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deleteUsers(array $users): void
    {
        $this->createQueryBuilder('u')
            ->delete()
            ->where("u.id in (:userIds)")
            ->setParameter('userIds', $users)
            ->getQuery()
            ->execute();
    }

    public function getUserById(int $userId): ?User
    {
        return $this->find($userId);
    }
}
