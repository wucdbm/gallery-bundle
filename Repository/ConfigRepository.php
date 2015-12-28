<?php

namespace Wucdbm\Bundle\GalleryBundle\Repository;

use Wucdbm\Bundle\GalleryBundle\Entity\Config;
use Wucdbm\Bundle\WucdbmBundle\Repository\AbstractRepository;

class ConfigRepository extends AbstractRepository {

    /**
     * @param $key
     * @param $name
     * @return null|Config
     */
    public function saveIfNotExists($key, $name) {
        $config = $this->findOneByKey($key);
        if ($config instanceof Config) {
            return $config;
        }
        $config = new Config();
        $config->setKey($key);
        $config->setName($name);
        $em = $this->getEntityManager();
        $em->persist($config);
        $em->flush($config);

        return $config;
    }

    // TODO: Save if not exists from site config upon image save for the given config

    /**
     * @param $key
     * @return Config|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByKey($key) {
        $builder = $this->createQueryBuilder('c')
            ->andWhere('c.key = :key')
            ->setParameter('key', $key);
        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param $id
     * @return Config|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneById($id) {
        $builder = $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id);
        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }

}