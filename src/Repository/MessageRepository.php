<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    //affichage des messages sur page accueil messagerie
    // public function findByTitleGroup($user)
    // {
    //     $qb = $this->createQueryBuilder('m');
    //     // $qb->andWhere('m.user = :currentUser')
    //     //     ->andWhere('m.userReceiver = :currentUser');

    //     $qb->where($qb->expr()->orX(
    //             $qb->expr()->eq('m.user', ':currentUser'),
    //             $qb->expr()->eq('m.userReceiver', ':currentUser')
    //         ))
    //         ->setParameter('currentUser', $user)
    //         ->groupBy('m.title')
    //         ->orderBy('m.createdAt', 'DESC')
    //     ;

    //     return $qb->getQuery()->getResult();
    // }


    // public function findBySlug($slug)
    // {
    //     return $this->createQueryBuilder('m')
    //         ->andWhere('m.slug = :val')
    //         ->setParameter('val', $slug)
    //         ->orderBy('m.createdAt', 'DESC')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // public function findConversation($user1, $user2) {
    //     $qb = $this->createQueryBuilder('m');

    //     $qb->where($qb->expr()->andX(
    //             $qb->expr()->eq('m.user', ':user1'),
    //             $qb->expr()->eq('m.userReceiver', ':user2')
    //         ))
    //         ->setParameter('user1', $user1)
    //         ->setParameter('user2', $user2)

    //         ->orWhere($qb->expr()->andX(
    //             $qb->expr()->eq('m.user', ':user2'),
    //             $qb->expr()->eq('m.userReceiver', ':user1')
    //         ))
    //         ->setParameter('user1', $user1)
    //         ->setParameter('user2', $user2)

    //         ->orderBy('m.createdAt', 'DESC')
    //     ;

    //     return $qb->getQuery()->getResult();
	// }
	
	/**
     * @return Message[] Returns all messages of a conversation
     */
    public function findByConversation($conversation)
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.conversation', 'c')
            ->andWhere('c = :conversation')
            ->setParameters(array(
                'conversation' => $conversation,
            ))
            ->getQuery()
            ->getResult();
    }
	

}
