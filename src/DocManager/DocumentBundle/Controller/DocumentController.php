<?php

namespace DocManager\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DocumentController extends Controller
{

    public function indexAction()
    {
        return $this->render('@DocManagerDocument/Document/index.html.twig');
    }

}
