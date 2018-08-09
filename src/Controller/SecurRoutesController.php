<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\EditProfileType;
use App\Form\InscriptionType;
use App\Repository\UtilisateurRepository;
use App\Repository\VideosRepository;
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

        //Redirection si l'utilisateur est déjà connecté.
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute('dashboard');
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
        //Redirection si l'utilisateur est déjà connecté.
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute('dashboard',[
                '_username' => $this->getUser()->getNickname(),
                '_uuid' => $this->getUser()->getId()
            ]);
        }

        return $this->render( 'secur_routes/login.html.twig',[
        ]);
    }

    /**
     * @Route("/admin", name="secur_admin_dashboard")
     *
     */
    public function secur_admin_route (Request $request, ObjectManager $manager, UtilisateurRepository $usersRepository, VideosRepository $videosRepository)
    {
        //if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') )//if ($this->getUser()->getRoles())
        {
            $allUsers = $usersRepository->findAll();
            $allVideos = $videosRepository->findAll();

            dump($allUsers);
            dump($allVideos);

            return $this->render ( 'secur_routes/admin_dashboard.html.twig', [
                'Welcome' => $this->getUser()->getNickname(),
                'users' => $allUsers,
                'videos' => $allVideos,
            ]);
        }
        else {
            return $this->redirectToRoute('dashboard');
        }

    }

    /**
     * @Route("/espace_videos/edition_profile", name="edition_profil_user")
     * @Route("/admin/{uuid_user}", name="edit_info_user_by_admin")
     */
    public function secur_edit_profile (Request $request, ObjectManager $manager, UtilisateurRepository $utilisateurRepository, UserPasswordEncoderInterface $encoder, $uuid_user = null)
    {
        //$user = new Utilisateur();

        //$user->setNickname($this->getUser()->getNickname());
        //$user->setEmail($this->getUser()->getEmail());
        //$user->setBirthday($this->getUser()->getBirthday());

        if (!$uuid_user) {
            $user = $utilisateurRepository->find($this->getUser()->getId());
        }
        else {
            $user = $utilisateurRepository->find($uuid_user);
        }
        //$user = $utilisateurRepository->find($this->getUser()->getId());

        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        dump($user);
        dump($request);

        if ($form->isSubmitted() && $form->isValid())//$request->request->count() > 0) {
        {
            //verif password here
            /*if ($user->getPassword() != "")
            {
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
            }*/

            //dump($user);

         //   $user->setNickname($request->request->get('edit_profile', 'nickname'));
            //$user->setEmail($request->request->get('edit_profile["email"]'));
            //$user->setBirthday($request->request->get('edit_profile["birthday"]'));

            $manager->persist($user);
            $manager->flush();

            if (!$uuid_user)
                return $this->redirectToRoute('dashboard');
            else
                return $this->redirectToRoute('secur_admin_dashboard');
           /* return $this->render('account_routes/edit_user.html.twig', [
                'form_user' => $form->createView(),
                'user' => $user
            ]);*/
        }


        return $this->render('account_routes/edit_user.html.twig', [
            'form_user' => $form->createView(),
            'user' => $user
        ]);
    }



}
