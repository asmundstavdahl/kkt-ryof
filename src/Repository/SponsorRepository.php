<?php

namespace Repository;

use Entity\Club;
use Doctrine\ORM\EntityRepository;
use Entity\Image;
use Entity\Sponsor;

/**
 * ImageRepository.
 */
class SponsorRepository extends EntityRepository
{
    /**
     * @param Club   $club
     *
     * @return array
     */
    public function findAllByClub(Club $club)
    {
        return $this->createQueryBuilder('sponsor')
            ->select('sponsor')
            ->where('sponsor.club = :club')
            ->setParameter('club', $club)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @return Sponsor
     */
    public function findById($id)
    {
        return $this->createQueryBuilder('sponsor')
            ->select('sponsor')
            ->where('sponsor.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
