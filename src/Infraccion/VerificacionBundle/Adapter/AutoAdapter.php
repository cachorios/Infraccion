<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cacho
 * Date: 25/03/13
 * Time: 10:24
 * To change this template use File | Settings | File Templates.
 */

namespace Infraccion\VerificacionBundle\Adapter;

//use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Adapter\AdapterInterface;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Pagerfanta\Adapter\DoctrineORM\Paginator as LegacyPaginator;



/**
 * DoctrineORMAdapter.
 *
 * @author Christophe Coevoet <stof@notk.org>
 *
 * @api
 */
class AutoAdapter implements AdapterInterface
{


    private $session;

    /**
     * @var \Doctrine\ORM\Query
     */
    private $queryCount;
    /**
     * @var \Doctrine\ORM\Tools\Pagination\Paginator|\Pagerfanta\Adapter\DoctrineORM\Paginator
     */
    private $paginator;

    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder $query               A Doctrine ORM query or query builder.
     * @param Boolean            $fetchJoinCollection Whether the query joins a collection (true by default).
     *
     * @api
     */

   // public function __construct($em, $s,QueryBuilder $query, $fetchJoinCollection = true)
    public function __construct($Session,QueryBuilder $query, Query $queryCount, $fetchJoinCollection = true)
    {

        $this->queryCount = $queryCount;
        $this->session = $Session;

        if (class_exists('Doctrine\ORM\Tools\Pagination\Paginator')) {
            $this->paginator = new DoctrinePaginator($query, $fetchJoinCollection);
        } else {
            $this->paginator = new LegacyPaginator($query, $fetchJoinCollection);
        }
    }

    /**
     * Returns the query
     *
     * @return Query
     *
     * @api
     */
    public function getQuery()
    {
        return $this->paginator->getQuery();
    }

    /**
     * Returns whether the query joins a collection.
     *
     * @return Boolean Whether the query joins a collection.
     */
    public function getFetchJoinCollection()
    {
        return $this->paginator->getFetchJoinCollection();
    }

    /**
     * {@inheritdoc}
     */


    public function getNbResults()
    {

        $count = $this->session->get("automotor_registros",0);
        if($count == 0){
            $count = $this->queryCount->getSingleScalarResult();
            $this->session->set("automotor_registros",$count);
        }

        //return count($this->paginator);
        //ld($queryCount->getQuery()->getSingleScalarResult());


        return  $count;
    }
    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        $this->paginator->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($length);

        return $this->paginator->getIterator();
    }
}
