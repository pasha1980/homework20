<?php

namespace App\Controller;

use App\Entity\Statistic;
use App\Form\StatisticType;
use App\Repository\StatisticRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/statistic")
 */
class StatisticController extends AbstractController
{
    /**
     * @Route("/", name="statistic_index", methods={"GET"})
     */
    public function index(StatisticRepository $statisticRepository): Response
    {
        return $this->render('statistic/index.html.twig', [
            'statistics' => $statisticRepository->findAll(),
        ]);
    }

//    /**
//     * @Route("/new", name="statistic_new", methods={"GET","POST"})
//     */
//    public function new(Request $request): Response
//    {
//        $statistic = new Statistic();
//        $form = $this->createForm(StatisticType::class, $statistic);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($statistic);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('statistic_index');
//        }
//
//        return $this->render('statistic/new.html.twig', [
//            'statistic' => $statistic,
//            'form' => $form->createView(),
//        ]);
//    }

//    /**
//     * @Route("/{id}", name="statistic_show", methods={"GET"})
//     */
//    public function show(Statistic $statistic): Response
//    {
//        return $this->render('statistic/show.html.twig', [
//            'statistic' => $statistic,
//        ]);
//    }

//    /**
//     * @Route("/{id}/edit", name="statistic_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, Statistic $statistic): Response
//    {
//        $form = $this->createForm(StatisticType::class, $statistic);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('statistic_index');
//        }
//
//        return $this->render('statistic/edit.html.twig', [
//            'statistic' => $statistic,
//            'form' => $form->createView(),
//        ]);
//    }

//    /**
//     * @Route("/{id}", name="statistic_delete", methods={"DELETE"})
//     */
//    public function delete(Request $request, Statistic $statistic): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$statistic->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($statistic);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('statistic_index');
//    }
}
