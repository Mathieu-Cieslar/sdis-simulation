<?php

namespace App\Repository;

use App\Entity\Capteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Capteur>
 */
class CapteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Capteur::class);
    }

    /**
     * @return array Returns an array of Capteur objects with their last value and coordinates
     */
    public function findCapteursWithLastValue(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.id , c.coorX as coorX, c.coorY as coorY,c.typeCapteur ,  ic.valeur AS valeur')
            ->leftJoin('c.info', 'ic')
            ->andWhere('ic.dateInfo = (
            SELECT MAX(ic2.dateInfo)
            FROM App\Entity\InfoCapteur ic2
            WHERE ic2.capteur = c
        ) OR ic.dateInfo IS NULL')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findChangedCapteursWithLastValue(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.id , c.coorX as coorX, c.coorY as coorY, c.typeCapteur, icLast.valeur AS lastValue, icPrev.valeur AS prevValue')
            ->leftJoin('c.info', 'icLast', 'WITH', 'icLast.dateInfo = (
            SELECT MAX(ic2.dateInfo)
            FROM App\Entity\InfoCapteur ic2
            WHERE ic2.capteur = c
        )')
            ->leftJoin('c.info', 'icPrev', 'WITH', 'icPrev.dateInfo = (
            SELECT MAX(ic3.dateInfo)
            FROM App\Entity\InfoCapteur ic3
            WHERE ic3.capteur = c AND ic3.dateInfo < (
                SELECT MAX(ic4.dateInfo)
                FROM App\Entity\InfoCapteur ic4
                WHERE ic4.capteur = c
            )
        )')
            ->andWhere('(icLast.valeur = 10 OR (icLast.valeur = 0 AND icPrev.valeur = 10))')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return Capteur[] Returns an array of Capteur objects
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

    //    public function findOneBySomeField($value): ?Capteur
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
