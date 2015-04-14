<?php

namespace DocManager\DocumentBundle\Controller;

use DocManager\DocumentBundle\Entity\Document;
use DocManager\DocumentBundle\Form\DocumentEditType;
use DocManager\DocumentBundle\Form\EditCategoriesType;
use DocManager\DocumentBundle\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DocumentController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $documents = $em->getRepository('DocManagerDocumentBundle:Document')->findByUser($this->getUser()->getId());
        return $this->render('@DocManagerDocument/Document/index.html.twig',
            array('documents' => $documents));
    }

    public function viewAction(Document $document, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($document->getUser() != $this->getUser())
        {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(new EditCategoriesType(), $document);
        $form->handleRequest($request);
        if($form->isValid())
        {
            foreach($document->getCategories() as $categorie)
            {
                $categorie->setUser($this->getUser());
                $dbCategory = $em->getRepository('DocManagerDocumentBundle:Category')->findOneBy(array(
                    'name' => $categorie->getName(),
                    'user' => $this->getUser()
                ));
                if($dbCategory != null)
                {
                    $document->removeCategory($categorie);
                    $document->addCategory($dbCategory);
                }
                $em->flush();
            }
            $em->persist($document);
            $em->flush();
            $unusedCategories = $em->getRepository('DocManagerDocumentBundle:Category')->getUnusedCategories();
            foreach($unusedCategories as $unusedCategory)
            {
                $em->remove($unusedCategory);
            }
            $em->flush();
        }
        $categories = $em->getRepository('DocManagerDocumentBundle:Category')->findByUser($this->getUser()->getId());
        return $this->render('@DocManagerDocument/Document/view.html.twig',array(
            'document' => $document,
            'categories' => $categories,
            'form' => $form->createView()
        ));
    }


    public function editAction(Document $document, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($document->getUser() != $this->getUser())
        {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(new DocumentEditType($this->getUser()), $document);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->persist($document);
            $em->flush();
            return $this->redirect($this->generateUrl('doc_manager_document_view', array('slug' => $document->getSlug())));
        }
        return $this->render('DocManagerDocumentBundle:Document:edit.html.twig',array(
            'form' => $form->createView(),
            'document' => $document));
    }

    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $document = new Document();
        $form = $this->createForm(new DocumentType($this->getUser()), $document);

        $form->handleRequest($request);

        if($form->isValid())
        {
            $document->setUploadDate(new \DateTime());
            $document->setUser($this->getUser());
            $categories = $document->getCategories();
            foreach($categories as $categorie)
            {
                $categorie->setUser($this->getUser());
                $dbCategory = $em->getRepository('DocManagerDocumentBundle:Category')->findOneBy(array(
                    'name' => $categorie->getName(),
                    'user' => $this->getUser()
                ));
                if($dbCategory != null)
                {
                    $document->removeCategory($categorie);
                    $document->addCategory($dbCategory);
                }
                $em->flush();
            }
            $em->persist($document);
            $em->flush();
            return $this->redirect($this->generateUrl('doc_manager_document_index'));
        }
        $categories = $em->getRepository('DocManagerDocumentBundle:Category')->findByUser($this->getUser()->getId());
        return $this->render('DocManagerDocumentBundle:Document:add.html.twig', array(
                'form' => $form->createView(),
                'categories' => $categories
            ));
    }

    public function deleteAction(Document $document, Request $request)
    {
        if($document->getUser() != $this->getUser())
        {
            throw new NotFoundHttpException();
        }
        $formBuilder = $this->createFormBuilder();
        $form = $formBuilder->getForm();

        $form->handleRequest($request);
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($document);
            $em->flush();
            $unusedCategories = $em->getRepository('DocManagerDocumentBundle:Category')->getUnusedCategories();
            foreach($unusedCategories as $unusedCategory)
            {
                $em->remove($unusedCategory);
            }
            $em->flush();
            return $this->redirect($this->generateUrl('doc_manager_document_index'));
        }
        return $this->render('DocManagerDocumentBundle:Document:delete.html.twig', array(
            'form' => $form->createView(),
            'document' => $document
        ));
    }

    public function outofdateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $documents = $em->getRepository('DocManagerDocumentBundle:Document')->getOutOfDateDocuments($this->getUser());
        return $this->render('@DocManagerDocument/Document/outOfDate.html.twig',
            array('documents' => $documents));
    }

    public function countoutofdateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $documents = $em->getRepository('DocManagerDocumentBundle:Document')->getOutOfDateDocuments($this->getUser());
        }
        else
        {
            $documents = null;
        }
        return $this->render('@DocManagerDocument/Document/countOutOfDate.html.twig',
            array('documents' => $documents));
    }

    public function searchAction()
    {
        $em = $this->getDoctrine()->getManager();
        $documents = $em->getRepository('DocManagerDocumentBundle:Document')->findByUser($this->getUser()->getId());
        $categories = $em->getRepository('DocManagerDocumentBundle:Category')->findByUser($this->getUser()->getId());
        return $this->render('@DocManagerDocument/Document/search.html.twig',
            array('documents' => $documents,
                'categories' => $categories));
    }

    public function downloadAction(Document $document)
    {
        if($document->getUser() != $this->getUser())
        {
            throw new NotFoundHttpException();
        }
        $filename = $document->getSlug().".".$document->getImage();
        $filePath = $document->getUploadRootDir()."/".$filename;
        $response = new BinaryFileResponse($filePath);
        $d = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        $response->headers->set('Content-Disposition', $d);

        return $response;
    }
}
