<?php

namespace App\Repository;

use App\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Participant>
 *
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ManagerRegistry        $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    public function save(Participant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Participant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Participant[] Returns an array of Participant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Participant
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function createParticipants(Participant $participant1, Participant $participant2)
    {
        $this->entityManager->persist($participant1);
        $this->entityManager->flush();

        $this->entityManager->persist($participant2);
        $this->entityManager->flush();
    }

    public function getOtherParticipant(int $conversationId, int $userId): ?Participant
    {
        $qb = $this->createQueryBuilder('p');
        return $qb
            ->innerJoin('p.conversation', 'c')
            ->where(
                $qb->expr()->eq('p.conversation', ':conversationId'),
                $qb->expr()->neq('p.user', ':userId')
            )
            ->setParameters([
                'conversationId' => $conversationId,
                'userId' => $userId
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
