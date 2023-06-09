<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ConsentRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConsentRequest>
 *
 * @method ConsentRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsentRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsentRequest[]    findAll()
 * @method ConsentRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsentRequestRepository extends ServiceEntityRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ManagerRegistry        $registry)
    {
        parent::__construct($registry, ConsentRequest::class);
    }

    public function save(ConsentRequest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ConsentRequest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getConsentRequestPreview(int $requestId): array
    {
        $qb = $this->createQueryBuilder('cr');
        return $qb
            ->innerJoin('cr.thread', 't')
            ->addSelect('t.id as threadId')
            ->innerJoin('cr.requester', 'u')
            ->addSelect('u.username as requester')
            ->innerJoin('cr.recipient', 'r')
            ->addSelect('r.username as recipient')
            ->innerJoin('cr.conversation', 'c')
            ->addSelect('c.id as conversationId')
            ->where($qb->expr()->eq('cr.id', ':requestId'))
            ->setParameter('requestId', $requestId)
            ->getQuery()
            ->getResult();
    }
}
