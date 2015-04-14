<?php

namespace DocManager\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends Controller
{

    public function grantAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if($user->hasRole('ROLE_PREMIUM'))
        {
            $user->removeRole('ROLE_PREMIUM');
        }
        else
        {
            $user->addRole('ROLE_PREMIUM');
        }
        $em->persist($user);
        $em->flush();

        $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken(
            $user,
            null,
            'main',
            $user->getRoles()
        );
        $this->container->get('security.context')->setToken($token);

        // faire un refresh du user a l'aide du user manager
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->refreshUser($user);
        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }
}
