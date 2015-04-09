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

class OutOfDateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('document:notifyOutOfDate')
            ->setDescription('Send an email to all users with out of date documents')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->enterScope('request');
        $this->getContainer()->set('request', new Request(), 'request');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $users = $em->getRepository('DocManagerUserBundle:User')->findAll();
        foreach($users as $user)
        {
            $documents = $em->getRepository('DocManagerDocumentBundle:Document')->getOutOfDateDocuments($user);
            if(count($documents) > 0)
            {
                $output->writeln($user->getEmail());
                $message = \Swift_Message::newInstance()
                    ->setSubject('DocManager - Documents périmés')
                    ->setContentType("text/html")
                    ->setFrom('donotanswer@docmanager.com')
                    ->setTo($user->getEmail())
                    ->setBody($this->getContainer()->get('templating')->render('@DocManagerDocument/Email/documentPerime.html.twig', array('documents' => $documents, 'user' => $user)))
                ;
                $this->getContainer()->get('mailer')->send($message);
            }
        }
    }
}