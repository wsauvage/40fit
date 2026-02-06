<?php

namespace App\Repository;

use App\Entity\Challenge;
use App\Entity\ChallengeCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Challenge>
 */
class ChallengeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Challenge::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ["title" => "ASC"]);
    }

    public function findByCategory(?ChallengeCategory $category = null): array
    {
        $qb = $this->createQueryBuilder("challenge")->orderBy(
            "challenge.title",
            "ASC",
        );

        if ($category !== null) {
            $qb->where("challenge.category = :category")->setParameter(
                "category",
                $category,
            );
        }

        return $qb->getQuery()->getResult();
    }

    public function searchChallengeByTitle(string $query): array
    {
        $qb = $this->createQueryBuilder("c");

        $qb->select("c.title, cat.title AS categoryTitle")
            ->leftJoin("c.category", "cat")
            ->where("c.title LIKE :query")
            ->setParameter("query", "%" . $query . "%")
            ->setMaxResults(1)
            ->orderBy("c.title", "DESC");

        return $qb->getQuery()->getOneOrNullResult();
    }

    //    /**
    //     * @return Challenge[] Returns an array of Challenge objects
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

    //    public function findOneBySomeField($value): ?Challenge
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
