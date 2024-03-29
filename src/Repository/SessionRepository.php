<?php

namespace App\Repository;

use App\Data\SearchDataSessions;
use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function save(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getSessionFormSearchData(SearchDataSessions $data){
        $query = $this->createQueryBuilder('session')
            ->leftJoin('session.game', 'game')
            ->select('session', 'game');
        if(!empty($data->game)){
            $query = $query
                ->orWhere('game.id = :title')
                ->setParameter('title', $data->game->getId());
        }
        if(!empty($data->sessionDateBetweenStart)){
            $query = $query
                ->orWhere('session.date > :startDate')
                ->setParameter(':startDate', $data->sessionDateBetweenStart);
        }
        if(!empty($data->sessionDateBetweenEnd)){
            $query = $query
                ->orWhere('session.date < :endDate')
                ->setParameter(':endDate', $data->sessionDateBetweenEnd);
        }
        if(!empty($data->durationMin)){
            $query = $query
                ->orWhere('session.sessionTime > :durationMin')
                ->setParameter('durationMin', $data->durationMin);
        }
        if(!empty($data->durationMax)){
            $query = $query
                ->orWhere('session.sessionTime > :durationMax')
                ->setParameter('durationMax', $data->durationMax);
        }
        $query = $query->orderBy('session.date', 'ASC');
        return $query->getQuery()->getResult();
    }

    /**
     * Return the last session of the game
     * @param int Identifier of the game
     */
    public function getLastSessionOfAGame(int $id){


        return $this->createQueryBuilder('session')
            ->leftJoin('session.game', 'game')
            ->select('session', 'game')
            ->andWhere('game.id = :id')
            ->setParameter(':id', $id)
            ->orderBy('session.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()->getResult();
    }

    /**
     * Return the sessionTime of the last 7 days.
     * @return array array with the sum of the last session's time
     */
    public function getSessionTimeForLastSevenDays(){
        $startDate = strtotime("-7 day");  
        $startDate = date("y-m-d", $startDate);
        
        return $this->createQueryBuilder('session')
            ->leftJoin('session.game', 'game')
            ->select('sum(session.sessionTime) as time')
            ->andWhere('session.date >= :startDate')
            ->setParameter(':startDate', $startDate)
            ->getQuery()->getResult();  
    }

    /**
     * Return the sessions of the last 7 days.
     * @return array array with the sessions of the last seven days
     */
    public function getSessionForLastSevenDays(){
        $startDate = strtotime("-7 day");  
        $startDate = date("y-m-d", $startDate);
        
        return $this->createQueryBuilder('session')
            ->leftJoin('session.game', 'game')
            ->select('session', 'game')
            ->andWhere('session.date >= :startDate')
            ->setParameter(':startDate', $startDate)
            ->getQuery()->getResult();
    }

    public function getSessionTimeForSevenDaysBefore(){
        $startDate = strtotime("-14 day");  
        $startDate = date("y-m-d", $startDate);
        $endDate = strtotime("-7 day");
        $endDate = date("y-m-d", $endDate);
        
        return $this->createQueryBuilder('session')
            ->leftJoin('session.game', 'game')
            ->select('sum(session.sessionTime) as time')
            ->andWhere('session.date >= :startDate')
            ->setParameter(':startDate', $startDate)
            ->andWhere('session.date < :endDate')
            ->setParameter(':endDate', $endDate)
            ->getQuery()->getResult();
    }


//    /**
//     * @return Session[] Returns an array of Session objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
