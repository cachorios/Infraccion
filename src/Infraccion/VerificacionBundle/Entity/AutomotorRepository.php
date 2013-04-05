<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cacho
 * Date: 26/03/13
 * Time: 10:04
 * To change this template use File | Settings | File Templates.
 */

namespace Infraccion\VerificacionBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AutomotorRepository extends EntityRepository
{

    public function getAutomotoresQuery()
    {
        $a = $this->createQueryBuilder("a")
            ->orderBy('a.dominio');
        return $a;
    }

    public function countAutomotoresQuery()
    {
        $qm = $this->getEntityManager();
        $qb =$qm->createQueryBuilder();
        $qb->select('count(auto.id)');
        $qb->from('VerificacionBundle:Automotor','auto');

        //$count = $qb->getQuery()->getSingleScalarResult();
        return $qb;
    }
}