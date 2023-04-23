<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

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

    public function updateUserRepo(User $user): bool
    {
        try {
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
            return true;
        } catch (\Exception $exception) {
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
            ->where("u.id in (:userIds) and u.roles in ('ROLE_MANAGER','ROLE_EMPLOYEE')")
            ->setParameter('userIds', $users)
            ->getQuery()
            ->execute();
    }

    public function getUserById(int $userId): ?User
    {
        return $this->find($userId);
    }
}
