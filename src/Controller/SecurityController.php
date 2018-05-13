<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;




class SecurityController extends Controller
{

    /**
     * @Route("/admin")
     */
    public function admin()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

    /**
     * @Route("/login", name="login_form")
     * @Template()
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                Security::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'App:auth:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(Security::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }
    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }

}
