<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ThreadConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ThreadConversation>
 *
 * @method ThreadConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThreadConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThreadConversation[]    findAll()
 * @method ThreadConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThreadConversation::class);
    }

    public function save(ThreadConversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ThreadConversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
