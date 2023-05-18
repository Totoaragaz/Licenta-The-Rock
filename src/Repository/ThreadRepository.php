<?php

namespace App\Repository;

use App\Entity\Thread;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Thread>
 *
 * @method Thread|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thread|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thread[]    findAll()
 * @method Thread[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadRepository extends ServiceEntityRepository
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $registry)
    {
        parent::__construct($registry, Thread::class);
        $this->entityManager = $entityManager;
    }

    public function createThread(Thread $thread): bool
    {
        $this->getEntityManager()->persist($thread);
        $this->getEntityManager()->flush();

        return true;
    }

    public function getAllThreads(string $user): array
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->innerJoin('t.author', 'u')
            ->where('u.username != :author')
            ->setParameter('author', $user)
            ->orderBy('t.uploadDate')
            ->getQuery()
            ->getResult();
    }

    public function getAllThreadsWithPage(string $user, int $page): array
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->innerJoin('t.author', 'u')
            ->where('u.username != :author')
            ->setParameter('author', $user)
            ->orderBy('t.uploadDate')
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function getSearchedThreadsWithPage(string $username, string $query, array $words, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('t');
        $queryBuilder
            ->select('t')
            ->innerJoin('t.author', 'u')
            ->innerJoin('t.tags', 'tt')
            ->where('u.username != :author and t.title like :query')
            ->setParameter('author', $username)
            ->setParameter('query', '%' . $query .'%');

        for ($i = 0; $i < sizeof($words); $i++) {
            $queryBuilder
                ->addSelect('t')
                ->orWhere('u.username != :author and tt.name = :word' . $i)
                ->setParameter('word' . $i, $words[$i]);
        }

        return $queryBuilder
            ->distinct()
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function save(Thread $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Thread $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getThreadById(string $threadId): Thread
    {
        return $this->findOneBy(['id' => $threadId]);
    }

//    /**
//     * @return Thread[] Returns an array of Thread objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Thread
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
