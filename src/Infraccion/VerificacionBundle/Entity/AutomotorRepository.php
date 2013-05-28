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
        $qb = $qm->createQueryBuilder();
        $qb->select('count(auto.id)');
        $qb->from('VerificacionBundle:Automotor', 'auto');

        //$count = $qb->getQuery()->getSingleScalarResult();
        return $qb;
    }

    public function getAutomotoresExportar()
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder("c")
            ->select('c.id,c.dominio')
            ->add('from', 'VerificacionBundle:Automotor c');
        $query->Where($query->expr()->isNull('c.fechaPedido'));
        $query->andWhere($query->expr()->isNull('c.ultimaActualizacion'));

        return $query;
    }

    public function setUltimaActualizacion($ids)
    {
        $fecha = new \DateTime('now');
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();
        $q = $qb->update('VerificacionBundle:Automotor', 'u')
            ->set('u.fechaPedido', '?1')
            ->where('u.id IN (:ids)')
            ->setParameter(1, $fecha)
            ->setParameter('ids', $ids)
            ->getQuery();
        $p = $q->execute();

        return $q;
    }
}