<?php

namespace Invit\DataAnalysisBundle\Entity;

use Doctrine\ORM\EntityRepository;

class DataAnalysisQuerySubscriptionRepository extends EntityRepository {

    public function getScheduledSubscriptions(){
        $qb = $this->_em->getRepository($this->_entityName)
            ->createQueryBuilder('s')
        ;

        $now = new \DateTime('now');

        $qb
            ->where($qb->expr()->orx($qb->expr()->eq('s.minute', ':minute'), $qb->expr()->isNull('s.minute')))
            ->andWhere($qb->expr()->orx($qb->expr()->eq('s.hour', ':hour'), $qb->expr()->isNull('s.hour')))
            ->andWhere($qb->expr()->orx($qb->expr()->eq('s.day', ':day'), $qb->expr()->isNull('s.day')))
            ->andWhere($qb->expr()->orx($qb->expr()->eq('s.month', ':month'), $qb->expr()->isNull('s.month')))
            ->setParameter('minute', $now->format('i'))
            ->setParameter('hour', $now->format('H'))
            ->setParameter('day', $now->format('N'))
            ->setParameter('month', $now->format('m'))
        ;

        return $qb->getQuery()->getResult();
    }
}