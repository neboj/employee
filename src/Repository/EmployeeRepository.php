<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    // /**
    //  * @return Employee[] Returns an array of Employee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    /*
    public function findOneBySomeField($value): ?Employee
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getAllEmployees($filter = null){
        $RAW_QUERY_FILTER = '';
        if ($filter != null) {
            if ($filter === 'active') {
                $RAW_QUERY_FILTER = ' WHERE e.active = 1 ';
            }else if ($filter === 'inactive') {
                $RAW_QUERY_FILTER = ' WHERE e.active = 0 ';
            }
        }
        $em=$this->getEntityManager();

        $RAW_QUERY = "
            SELECT e.first_name as firstName,e.last_name as lastName, e.title,e.active,e.birthday,g.type as gender FROM `employee` e LEFT JOIN `gender` g on e.gender=g.id" . $RAW_QUERY_FILTER;
        $statement=$em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $result=$statement->fetchAll();
//        var_dump($result);
//        exit;

        return $result;
    }
}
