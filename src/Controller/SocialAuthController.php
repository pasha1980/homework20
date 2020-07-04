<?php


namespace App\Controller;


use App\Entity\User;
use App\Security\LoginFormAuthenicator;
use http\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SocialAuthController extends AbstractController
{
    /**
     * @Route("/github-callback")
     * @param Request $request
     */

    public function githubCallback(Request $request, GuardAuthenticatorHandler $handler, LoginFormAuthenicator $authenticator)
    {
        //        Client ID
        //597984a0e83f563d832d
        //
        //Client Secret
        //00026b0739272712b7273ddf0e16bfe1c7f677c0
        //

        if($code = $request->get('code')) {
            $client = HttpClient::create();
            $response = $client->request(
                'POST',
                'https://github.com/login/oauth/access_token',
                [
                    'body' => [
                        'client_id' => '597984a0e83f563d832d',
                        'client_secret' => '00026b0739272712b7273ddf0e16bfe1c7f677c0',
                        'code' => $code,
                    ],
                ]
            );

            parse_str($response->getContent(), $data);

            $token = $data['access_token'];

            $response = $client->request('GET', 'https://api.github.com/user',
                [
                    'headers' => [
                        'Authorization' => 'token ' . $token,
                    ]
                ]
                );

            $name = json_decode($response->getContent(), true)['name'];

            $response = $client->request('GET', 'https://api.github.com/user/emails',
                [
                    'headers' => [
                        'Authorization' => 'token ' . $token,
                    ]
                ]
            );

            foreach (json_decode($response->getContent(), true) as $email) {
                if ($email['primary']) {
                    $userEmail = $email['email'];
                }
            }

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(User::class)->findOneBy(['email' => $userEmail]);

            if (!$user) {
                $user = new User();
                $user->setEmail($userEmail);
                $user->setName($name);
                $user->setPassword(password_hash('jdf3kjfodi33453', PASSWORD_ARGON2ID));
                $user->setPhone('00000000000');
                $em->persist($user);
                $em->flush();
            }

            $handler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main');

            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/google-callback")
     * @param Request $request
     * @param GuardAuthenticatorHandler $handler
     * @param LoginFormAuthenicator $authenticator
     */
    public function googleCallback(Request $request, GuardAuthenticatorHandler $handler, LoginFormAuthenicator $authenticator)
    {
        $socialAuthConfig = new SocialAuthConfigController();
        $client = $socialAuthConfig -> getGoogleClient();

        $code = $request ->get('code');

        try {
            $token = $client -> fetchAccessTokenWithAuthCode($code);
            $client -> setAccessToken($token);
        } catch (\Exception $e) {
            echo $e -> getMessage();
        }

        $userData = $client -> verifyIdToken();

        $em = $this->getDoctrine()->getManager();

        $email = $userData['email'];
        $name = $userData['name'];

        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setName($name);
            $user->setPassword(password_hash('jdf3kjhtsg$TEfWH#ifodi33453', PASSWORD_ARGON2ID));
            $user->setPhone('00000000000');
            $em->persist($user);
            $em->flush();
        }

        $handler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main');


        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/facebook-callback")
     * @param Request $request
     * @param GuardAuthenticatorHandler $handler
     * @param LoginFormAuthenicator $authenticator
     */
    public function facebookCallback(Request $request, GuardAuthenticatorHandler $handler, LoginFormAuthenicator $authenticator)
    {

    }

}