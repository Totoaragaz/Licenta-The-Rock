<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ContentBit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
