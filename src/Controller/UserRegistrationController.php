<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserRegistrationType;
use App\Security\LoginFormAuthenicator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class UserRegistrationController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param GuardAuthenticatorHandler $handler
     * @param LoginFormAuthenicator $authenticator
     * @return Response
     * @Route("/register", name="user_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        GuardAuthenticatorHandler $handler,
        LoginFormAuthenicator $authenticator
    ) {
        $form = $this->createForm(UserRegistrationType::class, new User());

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $form->getData();

            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $handler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main');

            return $this->redirectToRoute('home');
        }

        $socialAuthConfig = new SocialAuthConfigController();

        $githubUrl = $socialAuthConfig -> getGithubUrl();
        $googleUrl = $socialAuthConfig -> getGoogleUrl();

        return $this->render('/user-registration/register.html.twig', [
            'form' => $form->createView(),
            'github_url' => $githubUrl,
            'google_url' => $googleUrl,
        ]);
    }
}