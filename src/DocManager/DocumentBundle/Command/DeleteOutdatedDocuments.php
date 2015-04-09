<?php
namespace DocManager\DocumentBundle\Command;

use DocManager\DocumentBundle\Entity\Document;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class DeleteOutdatedDocuments extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('document:deleteOutdated')
            ->setDescription("Delete outdated documents")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $documents = $em->getRepository('DocManagerDocumentBundle:Document')->getOutOfDateDocuments30days();
        foreach($documents as $document)
        {
            $em->remove($document);
        }
        $em->flush();
    }
}