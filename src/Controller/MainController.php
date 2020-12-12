<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package App\Controller
 */
class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request) {

        return $this->render('homepage.html.twig', []
        );
    }

    /**
     * @Route("/employess/{name}", name="profile")
     * @param Request $request
     * @param string $name
     * @return Response
     */
    public function profileAction(Request $request, string $name) {
        $employeeName = $name;

        return $this->render('profile.html.twig',
            [
                'name' => $employeeName
            ]
        );
    }

}