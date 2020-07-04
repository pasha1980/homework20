<?php


namespace App\Controller;


use App\Entity\ShortLink;
use App\Entity\Statistic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ShortLinkRedirectController extends AbstractController
{
    /**
     * @Route(path="/r/{code}")
     */
    public function shortLinkRedirect(Request $request, string $code)
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $shortLinkRepository = $doctrine->getRepository(ShortLink::class);

        /** @var ShortLink|null $shortLink */
        $shortLink = $shortLinkRepository->findOneBy(['shortCode' => $code]);

        if (!$shortLink) {
            throw new NotFoundHttpException('No link for given code');
        }

        $statistic = new Statistic();
        $statistic->setLink($shortLink);
        $statistic->setUserAgent($request->server->get('HTTP_USER_AGENT'));
        $statistic->setIp($request->server->get('REMOTE_ADDR'));

        $manager->persist($statistic);
        $manager->flush();


        // return new RedirectResponse($shortLink->getFullUrl());
        return $this->redirect($shortLink->getFullUrl());
    }
}