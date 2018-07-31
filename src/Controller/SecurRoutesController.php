<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Zend\Code\Scanner\Util;

class SecurRoutesController extends Controller
{
    /**
     * @Route("/", name="secur_inscription")
     */
    public function secur_inscription_route(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = new Utilisateur();

        $form = $this->createForm(InscriptionType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash_password = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());

            $utilisateur->setPassword($hash_password);

            $manager->persist($utilisateur);
            $manager->flush();

            return $this->redirectToRoute('secur_connexion');
        }

        return $this->render('secur_routes/index.html.twig', [
            'controller_name' => 'SecurRoutesController',
            'form_enreg' => $form->createView()
        ]);
    }


    /**
     * @Route("/connexion", name="secur_connexion")
     */
    public function secur_connexion_route (Request $request, ObjectManager $manager)
    {
        //$form = $this->createForm();
        /*$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // perform some action...

            return $this->redirectToRoute('secur_connexion');
        }*/
        return $this->render( 'secur_routes/login.html.twig',[
            'vide' => 'test',
        ]);
    }
}
