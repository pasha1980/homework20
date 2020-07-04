<?php


namespace App\Controller;


use App\Entity\Post;
use App\Entity\ShortLink;
use App\Entity\User;
use App\Form\PostType;
use App\Service\ShortLinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\Form\FormInterface;

class WallController extends AbstractController
{
    private ShortLinkService $shortLinkService;

    /**
     * WallController constructor.
     * @param ShortLinkService|null $shortLinkService
     */
    public function __construct(ShortLinkService $shortLinkService)
    {
        $this->shortLinkService = $shortLinkService;
    }

    /**
     * @Route("/wall", name="posts_list")
     */
    public function postsList(Request $request)
    {
        $form = $this->createForm(PostType::class, new Post());

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            return $this->processPostFormAndRedirectToList($form);
        }

        return $this->render('wall/posts-list.html.twig', [
            'posts' => $this->getUser()->getPosts(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param FormInterface $form
     * @param User $user
     * @return RedirectResponse
     */
    private function processPostFormAndRedirectToList(FormInterface $form): RedirectResponse
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        /** @var Post $post */
        $post = $form->getData();
        $post->setUser($user);
        $this->shortLinkService->replaceLinks($post);

        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('posts_list');
    }

}