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
                $user->setPassword(password_hash('jdf3kjhtsg$TEfWH.github.ifodi33453', PASSWORD_ARGON2ID));
                $user->setPhone('no');
                $em->persist($user);
                $em->flush();
            }

            $handler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main');
        }

        return $this->redirectToRoute('homepage');
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

        if (!isset($code))
        {
            echo 'Problem with login';
        }

        try {
            $token = $client -> fetchAccessTokenWithAuthCode($code);
            $client -> setAccessToken($token);
        } catch (\Exception $e) {
            echo $e -> getMessage(); die;
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
            $user->setPassword(password_hash('jdf3kjhtsg$TEfWH.google.ifodi33453', PASSWORD_ARGON2ID));
            $user->setPhone('no');
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
        $code = $request -> get('code');

        if (!isset($code))
        {
            echo 'Problem with login';
            die;
        }

        $params = SocialAuthConfigController::getFacebookParams($code);

        try {
            $token = file_get_contents('https://graph.facebook.com/oauth/access_token?' . urldecode(http_build_query($params)));
            $token = json_decode($token, true);
        } catch (\Exception $e) {
            $e -> getMessage(); die;
        }

        if (isset($token['access_token'])) {
            $params = array(
                'access_token' => $token['access_token'],
                'fields'       => 'email,name'
            );

            try {
                $userData = file_get_contents('https://graph.facebook.com/me?' . urldecode(http_build_query($params)));
                $userData = json_decode($userData, true);
            } catch (\Exception $e) {
                $e -> getMessage(); die;
            }

        }

        $em = $this -> getDoctrine() -> getManager();

        $name = $userData['name'];

        if (isset($userData['email']))
        {
            $email = $userData['email'];
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        } else {
            $email = 'no';
            $user = $em->getRepository(User::class)->findOneBy(['name' => $name]);
        }

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setName($name);
            $user->setPassword(password_hash('jdf3kjhtsg$TEfWH.facebook.ifodi33453', PASSWORD_ARGON2ID));
            $user->setPhone('no');
            $em->persist($user);
            $em->flush();
        }

        $handler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main');

        return $this->redirectToRoute('homepage');

    }

}