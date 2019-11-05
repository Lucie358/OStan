<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
	*/

	/**
     * @return Conversation[] Returns the liste of the user's conversations
     */
	 public function findConversationsByOneUser($user) {
        $qb = $this->createQueryBuilder('c');

		$qb->join('c.users', 'u')
        ->andWhere(':user MEMBER OF c.users')
        ->setParameters(array(
			'user' => $user,
		));

        return $qb->getQuery()->getResult();
	}

	/**
     * @return Conversation Returns the users conversations
     */
	public function findByUsers($users) {
        $qb = $this->createQueryBuilder('c');

		$qb->join('c.users', 'u');

		foreach ($users as $user) {
			$qb->andWhere(':user MEMBER OF c.users')
			->setParameters(array(
				'user' => $user,
			));
		}

        return $qb->getQuery()->getOneOrNullResult();
	}

	
        
}
