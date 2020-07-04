<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\EndRegistration;
use App\Form\PhoneType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route(path="/", name="homepage")
     */
    public function index(Request $request)
    {
        $user= $this->get('security.token_storage')->getToken()->getUser();

        if ($user != 'anon.')
        {
            $logged = true;
            $phone = $user -> getPhone();
            $email = $user -> getEmail();

            if ($phone == 'no')
            {
                $issetPhone = false;
            } else {
                $issetPhone = true;
            }

            if ($email == 'no')
            {
                $issetEmail = false;
            } else {
                $issetEmail = true;
            }

        } else {
            $logged = false;
            $issetPhone = false;
            $issetEmail = false;
        }

        if ($issetEmail)
        {
            $form = $this -> createForm(PhoneType::class, new User());
        } else {
            $form = $this -> createForm(EndRegistration::class, new User());
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $newPhone = $form->getData()->getPhone();

            if (!$issetEmail)
            {
                $newEmail = $form->getData()->getEmail();
            }
            $em = $this->getDoctrine()->getManager();
            $user -> setPhone($newPhone);

            if (!$issetEmail)
            {
                $user -> setEmail($newEmail);
            }

            $em -> persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'logged' => $logged,
            'issetPhone' => $issetPhone,
        ]);
    }
}