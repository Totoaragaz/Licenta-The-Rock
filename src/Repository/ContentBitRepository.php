<?php

namespace App\Repository;

use App\Entity\ContentBit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ContentBit>
 *
 * @method ContentBit|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentBit|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentBit[]    findAll()
 * @method ContentBit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentBitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentBit::class);
    }

    public function save(ContentBit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ContentBit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ContentBit[] Returns an array of ContentBit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ContentBit
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function removeThreadTextAndImages(int $threadId): void
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->delete('c')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('c.thread', ':thread'),
                    $qb->expr()->neq('c.type', 'conversation')
                )
            )
            ->setParameter(':thread', $threadId);
    }
}
