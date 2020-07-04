<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('short_links_list');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        //        Client ID
        //597984a0e83f563d832d
        //
        //Client Secret
        //00026b0739272712b7273ddf0e16bfe1c7f677c0
        //
//        $clientId = '597984a0e83f563d832d';
//        $githubUrl = 'https://github.com/login/oauth/authorize';
//
//        $params = [
//            'client_id' => $clientId,
//            'redirect_uri' => 'http://localhost:8000/github-callback',
//            'scope' => 'read:user user:email',
//        ];
//
//        $githubUrl .= '?' . http_build_query($params);
        $socialAuthConfig = new SocialAuthConfigController();

        $githubUrl = $socialAuthConfig -> getGithubUrl();
        $googleUrl = $socialAuthConfig -> getGoogleUrl();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'github_url' => $githubUrl,
            'google_url' => $googleUrl,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
