<?php

namespace App\Repository;

use App\Entity\Depot;
use App\Entity\Magasin;
use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stock>
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }
    
    /**
     * @param $produit
     * @param $unite
     * @return Stock|null
     */
    public function findOneByProduitAndUniteAndMagasin($produit, $unite, Magasin $magasin): ?Stock
    {
        return $this->createQueryBuilder('s')
            ->where('s.produit = :produit')
            ->andWhere('s.unite = :unite')
            ->andWhere('s.magasin = :magasin')
            ->setParameter('produit', $produit)
            ->setParameter('unite', $unite)
            ->setParameter('magasin', $magasin)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByProduitAndUniteAndDepot($produit, $unite, Depot $depot): ?Stock
    {
        return $this->createQueryBuilder('s')
            ->where('s.produit = :produit')
            ->andWhere('s.unite = :unite')
            ->andWhere('s.depot = :depot')
            ->setParameter('produit', $produit)
            ->setParameter('unite', $unite)
            ->setParameter('depot', $depot)
            ->getQuery()
            ->getOneOrNullResult();
    }


//    /**
//     * @return Stock[] Returns an array of Stock objects
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

//    public function findOneBySomeField($value): ?Stock
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
