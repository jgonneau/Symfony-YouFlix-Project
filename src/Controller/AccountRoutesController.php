<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountRoutesController extends Controller
{
    /**
     * @Route("/compte", name="dashboard")
     */
    public function index()
    {
        dump($this->getUser());
        return $this->render('account_routes/index.html.twig', [
            'username' => $this->getUser()->getEmail(),
        ]);
    }
}
