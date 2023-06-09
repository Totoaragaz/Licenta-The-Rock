<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 *
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ManagerRegistry        $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function save(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    /**
//     * @return Conversation[] Returns an array of Conversation objects
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

//    public function findOneBySomeField($value): ?Conversation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findConversationByParticipants(int $otherUserId, int $myId): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->select($qb->expr()->count('p.conversation'), 'c.id')
            ->innerJoin('c.participants', 'p')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('p.user', ':myId'),
                    $qb->expr()->eq('p.user', ':otherUserId')
                )
            )
            ->groupBy('p.conversation')
            ->having(
                $qb->expr()->eq(
                    $qb->expr()->count('p.conversation'),
                    2
                )
            )
            ->setParameters([
                'myId' => $myId,
                'otherUserId' => $otherUserId
            ])
            ->getQuery()
            ->getResult();
    }

    public function createNewConversation(Conversation $conversation)
    {
        $this->entityManager->persist($conversation);
        $this->entityManager->flush();
    }

    public function getConversations(int $userId): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->
        select('otherUser.username', 'otherUser.image', 'c.id as conversationId', 'lm.content', 'lm.createdAt')
            ->innerJoin('c.participants', 'p', Join::WITH, $qb->expr()->neq('p.user', ':user'))
            ->innerJoin('c.participants', 'me', Join::WITH, $qb->expr()->eq('me.user', ':user'))
            ->leftJoin('c.lastMessage', 'lm')
            ->innerJoin('me.user', 'meUser')
            ->innerJoin('p.user', 'otherUser')
            ->where('meUser.id = :user')
            ->setParameter('user', $userId)
            ->orderBy('lm.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function checkIfUserIsParticipant(int $conversationId, int $userId): ?Conversation
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->innerJoin('c.participants', 'p')
            ->where('c.id = :conversationId')
            ->andWhere(
                $qb->expr()->eq('p.user', ':userId')
            )
            ->setParameters([
                'conversationId' => $conversationId,
                'userId' => $userId,
            ]);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
