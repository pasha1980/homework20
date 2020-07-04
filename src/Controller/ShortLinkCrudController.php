<?php


namespace App\Controller;


use App\Entity\ShortLink;
use App\Form\ShortLinkType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShortLinkCrudController extends AbstractController
{
    /**
     * @Route(path="/short-links-list", name="short_links_list", methods={"GET"})
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $shortLinkRepository = $em->getRepository(ShortLink::class);

        $shortLinks = $shortLinkRepository->findAll();

        return $this->render('short-link/index.html.twig', ['shortLinks' => $shortLinks]);
    }

    /**
     * @Route(path="/short-link/new", methods={"GET","POST"})
     */
    public function create(Request $request)
    {
        $form = $this->createForm(ShortLinkType::class, new ShortLink());

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $shortLink = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($shortLink);
            $em->flush();

            return $this->redirectToRoute('short_links_list');
        }

        return $this->render('short-link/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(name="short_link_edit", path="/short-link/{shortLink}/edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ShortLink $shortLink)
    {
        $form = $this->createForm(ShortLinkType::class, $shortLink);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $shortLink = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($shortLink);
            $em->flush();

            return $this->redirectToRoute('short_links_list');
        }

        return $this->render('short-link/edit.html.twig', ['form' => $form->createView()]);
    }
}