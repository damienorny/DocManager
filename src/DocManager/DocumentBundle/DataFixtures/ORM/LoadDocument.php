<?php

namespace DocManager\DocumentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DocManager\DocumentBundle\Entity\Document;
use Symfony\Component\Validator\Constraints\DateTime;

class LoadDocument implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        // Liste des noms de catégorie à ajouter

        $document = new Document();
        $document->setName("Document1")
            ->setDescription("Description du premier document")
            ->setDocumentDate(new \DateTime())
            ->setUploadDate(new \DateTime());

        $document2 = new Document();
        $document2->setName("Document2")
            ->setDescription("Description du deuxième document")
            ->setDocumentDate(new \DateTime())
            ->setUploadDate(new \DateTime());

        $manager->persist($document);
        $manager->persist($document2);

        $manager->flush();
    }
}