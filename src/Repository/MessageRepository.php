<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ManagerRegistry        $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function save(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getMessages(int $conversationId): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.conversation = :conversationId')
            ->setParameter('conversationId', $conversationId)
            ->orderBy('m.createdAt')
            ->getQuery()
            ->getResult();

    }

    public function getMessagesWithIds(array $messageIds): array
    {
        $qb = $this->createQueryBuilder('m');
        return $qb
            ->select('m.content', 'u.username')
            ->innerJoin('m.user', 'u')
            ->where($qb->expr()->in('m.id', ':messageIds'))
            ->setParameter('messageIds', $messageIds)
            ->orderBy('m.createdAt')
            ->getQuery()
            ->getResult();
    }
}
