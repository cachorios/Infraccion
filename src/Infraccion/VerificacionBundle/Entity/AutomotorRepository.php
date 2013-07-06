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

use Infraccion\VerificacionBundle\Entity\Automotor;

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


    /**
     * getAutomotoresExportar
     *
     * Registros para pedido de info de dominio
     * @param $exportar
     * @return mixed
     */

    public function getAutomotoresExportar(Exportar $exportar)
    {

        $dact = clone($exportar->getFechaInicio()->setTime(0,0,0));
        $dped = clone($dact);
        $em = $this->getEntityManager();

        return $em->createQuery("
                SELECT i
                FROM   InfraccionBundle:Infraccion i
                JOIN   i.automotor a
                WHERE   i.fecha BETWEEN :d1 AND :d2
                AND    i.etapa = 0
                AND    (a.ultimaActualizacion is null OR a.ultimaActualizacion < :dAct )
                AND    (a.fechaPedido is null OR a.fechaPedido < :dPed )
                ORDER BY i.dominio")

                ->setParameter('d1', $exportar->getFechaInicio())
                ->setParameter('d2', $exportar->getFechaFinal()->setTime(23,59,59))
                ->setParameter('dAct', $dact->modify('-1 year'))
                ->setParameter('dPed', $dped->modify('-1 year'))
                ->getResult()
        ;
        /*
        $query = $em->createQueryBuilder("c")
            ->select('c.id,c.dominio')
            ->add('from', 'VerificacionBundle:Automotor c');
        $query->Where($query->expr()->isNull('c.fechaPedido'));
        $query->andWhere($query->expr()->isNull('c.ultimaActualizacion'));

        */
/*
        SELECT * FROM infraccion i left join automotor a ON a.id = i.automotor_id
        where i.fecha between '2013-05-21' and '2013-05-25 23:59'
    and etapa = 0
    and ( a.ultima_actualizacion is null or a.ultima_actualizacion < '2013-01-01'  )
and ( a.fecha_pedido is null or a.fecha_pedido < '2013-01-01'  )
*/

//        return $query;
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