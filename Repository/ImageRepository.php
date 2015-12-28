<?php

namespace Wucdbm\Bundle\GalleryBundle\Repository;

use Wucdbm\Bundle\GalleryBundle\Entity\Image;
use Wucdbm\Bundle\GalleryBundle\Filter\Image\ImageFilter;
use Wucdbm\Bundle\WucdbmBundle\Repository\AbstractRepository;

class ImageRepository extends AbstractRepository {

    public function filter(ImageFilter $filter) {
        $builder = $this->createQueryBuilder('i');

        if ($filter->getName()) {
            $builder->andWhere('i.name LIKE :name')
                ->setParameter('name', '%' . $filter->getName() . '%');
        }

        return $this->returnFilteredEntities($builder, $filter, 'i.id');
    }

    public function findOneById($id) {
        $builder = $this->createQueryBuilder('i')
            ->addSelect('c')
            ->andWhere('i.id = :id')
            ->leftJoin('i.config', 'c')
            ->setParameter('id', $id);
        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findByConfigId($configId) {
        $builder = $this->createQueryBuilder('i')
            ->addSelect('c')
            ->leftJoin('i.config', 'c')
            ->andWhere('c.id = :configId')
            ->setParameter('configId', $configId);
        $query = $builder->getQuery();

        return $query->getResult();
    }

    public function findOneByMd5AndConfigId($md5, $configId) {
        $builder = $this->createQueryBuilder('i')
            ->addSelect('c')
            ->leftJoin('i.config', 'c')
            ->andWhere('i.md5 = :md5')
            ->setParameter('md5', $md5)
            ->andWhere('c.id = :configId')
            ->setParameter('configId', $configId);
        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function save(Image $image) {
        $this->_em->persist($image);
        $this->_em->flush($image);
    }

    public function remove(Image $image) {
        $this->_em->remove($image);
        $this->_em->flush($image);
    }

}