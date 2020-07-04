<?php


namespace App\Controller;


use App\Entity\User;
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

            if ($phone == 'no')
            {
                $issetPhone = false;
            } else {
                $issetPhone = true;
            }
        } else {
            $logged = false;
            $issetPhone = false;
        }

        $form = $this -> createForm(PhoneType::class, new User());

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $newPhone = $form->getData()->getPhone();
            $em = $this->getDoctrine()->getManager();
            $user -> setPhone($newPhone);
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