<?php
namespace AppBundle\Model;


class VrrManager {
    Private $em;
    public function __construct(\Doctrine\ORM\EntityManager $entityManager){
        $this->em = $entityManager;
    }
}
