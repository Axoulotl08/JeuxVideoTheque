<?php

namespace App\Repository;

use App\Data\SearchDataGames;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function save(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countGameByConsole(){
        return $queryBuilder = $this->createQueryBuilder('game')
            ->leftJoin('game.console', 'console')
            ->select('console.name as name', 'count(game) as number')
            ->groupBy('console')
            ->getQuery()->getResult();
    }


    public function searchFilter(SearchDataGames $data){
        $query = $this->createQueryBuilder('game')
            ->leftJoin('game.state', 'state')
            ->leftJoin('game.console', 'console')
            ->select('game', 'state', 'console');
        if(!empty($data->titlesearch)){
            $query = $query
                ->orWhere('game.name LIKE :title')
                ->setParameter(':title', "%{$data->titlesearch}%");
        }
        if(!empty($data->state)){
            $query = $query
                ->orWhere('game.state = :state')
                ->setParameter(':state', $data->state->getId());
        }
        if(!empty($data->console)){
            $query = $query
                ->orWhere('game.console = :console')
                ->setParameter(':console', $data->console->getId());
        }
        if(!empty($data->startSaleDate)){
            $query = $query
                ->orWhere('game.saleDate <= :saleDateStart')
                ->setParameter(':saleDateStart', $data->startSaleDate);
        }
        if(!empty($data->endSaleDate)){
            $query = $query
                ->orWhere('game.saleDate >= :saleDateEnd')
                ->setParameter(':saleDateEnd', $data->endSaleDate);
        }
        $query = $query->orderBy('game.name', 'ASC');
        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
