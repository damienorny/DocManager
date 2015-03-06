<?php

namespace DocManager\DocumentBundle\Controller;

use DocManager\DocumentBundle\Entity\Document;
use DocManager\DocumentBundle\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DocumentController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $documents = $em->getRepository('DocManagerDocumentBundle:Document')->findAll();
        return $this->render('@DocManagerDocument/Document/index.html.twig',
            array('documents' => $documents));
    }

    public function viewAction(Document $document)
    {
        return $this->render('@DocManagerDocument/Document/view.html.twig',array(
            'document' => $document
        ));
    }


    public function editAction($id)
    {
        return $this->render('DocManagerDocumentBundle:Document:edit.html.twig');
    }

    public function addAction(Request $request)
    {
        $document = new Document();
        $form = $this->createForm(new DocumentType(), $document);

        $form->handleRequest($request);

        if($form->isValid())
        {
            $document->setUploadDate(new \DateTime());
            $document->setDocumentDate(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();
            return $this->redirect($this->generateUrl('doc_manager_document_index'));
        }

        return $this->render('DocManagerDocumentBundle:Document:add.html.twig', array(
                'form' => $form->createView()
            ));
    }
}
