<?php

namespace App\Repository;
// namespace : Définit l’espace de noms App\Repository, indiquant que cette classe fait partie des dépôts de l’application.

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
    /*
    use : Importe les classes nécessaires pour le fonctionnement du dépôt.
        - Category : Entité Category associée à ce dépôt.
        - ServiceEntityRepository : Classe de base pour tous les dépôts d’entités, offrant des méthodes pratiques pour interagir avec la base de données.
        - ManagerRegistry : Fournit l’accès au gestionnaire d’entités de Doctrine.
    */
/**
 * @extends ServiceEntityRepository<Category>
 */
    /*
    @extends ServiceEntityRepository<Category> : Documentation pour indiquer que cette classe étend ServiceEntityRepository et est spécifique à l’entité Category.
    */
class CategoryRepository extends ServiceEntityRepository
    /*
    class CategoryRepository : Nom de la classe, définissant le dépôt pour l’entité Category.
    extends ServiceEntityRepository : Hérite de ServiceEntityRepository, qui fournit des fonctionnalités de base pour les dépôts d’entités (comme find, findAll, findOneBy, etc.).
    */
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
        /*
        __construct() : Méthode de construction qui initialise le dépôt.
        ManagerRegistry $registry : Paramètre qui fournit le gestionnaire d’entités, permettant de manipuler les objets Category dans la base de données.
        parent::__construct($registry, Category::class); : Appelle le constructeur de ServiceEntityRepository, en spécifiant l’entité Category. Cela lie ce dépôt à la table Category dans la base de données.
        */

    //    /**
    //     * @return Category[] Returns an array of Category objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
