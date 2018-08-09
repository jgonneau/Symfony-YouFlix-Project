<?php

namespace App\Controller;

use App\Entity\Videos;
use App\Form\AjoutVideoType;
use App\Repository\VideosRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountRoutesController extends Controller
{
    /**
     * @Route("/espace_videos", name="dashboard")
     */
    public function index(VideosRepository $videosRepository)
    {

        dump($this->getUser());

        $videos = $videosRepository->findBy([
            'iduser' => $this->getUser()->getId(),
        ]);
        dump($videos);

        return $this->render('account_routes/index.html.twig', [
            'username' => $this->getUser()->getEmail(),
            'videos_user' => $videos,
        ]);
    }

    /**
     * @Route("/espace_videos/ajout_video", name="add_video_user")
     */
    public function add_video (Request $request, ObjectManager $manager)
    {
        $video = new Videos();

        $form = $this->createForm(AjoutVideoType::class, $video);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $video->setIdUser($this->getUser());
            $video->setByUser($this->getUser()->getNickname());
            $video->setCreatedAt( new \DateTime() );
            $manager->persist($video);
            $manager->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render( 'account_routes/add_video.html.twig', [
            'username' => $this->getUser()->getNickname(),
            'form_video' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace_videos/edition_video", name="edit_video_user")
     * @Route("/admin/edition_video/{uuid_user}/{uuid_video}", name="edit_video_user_by_admin")
     */
    public function edit_video (Request $request, ObjectManager $manager, VideosRepository $videosRepository, $uuid_user = null, $uuid_video = null)
    {
        $id_video = "";

        if ($request->get("v_id")) {
            $id_video = $request->get("v_id");
        }
        else {
            $id_video = $uuid_video;
        }

        $video = $videosRepository->find($id_video);

        //Si video non existante ou non appartenante à un utilisateur
        if (!$video)
        {
            //Alors redirection vers le dashboard, menu principal
            return $this->redirectToRoute('dashboard');
        }
        else
        {
            //Redirection vers le dashboard si video non appartenante à un utilisateur
            if ($video->getIdUser() !== $uuid_user && $uuid_user)
            {
                return $this->redirectToRoute('dashboard');
            }
        }

        $form = $this->createForm(AjoutVideoType::class, $video); //todo mode edit

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($video);
            $manager->flush();

            //Flash message indication
            $this->addFlash('mess', 'Les infos de la video ont été changés.');

            if (!$uuid_video)
                return $this->redirectToRoute('dashboard');
            else
                return $this->redirectToRoute('secur_admin_dashboard');
        }

        return $this->render ( 'account_routes/edit_video.html.twig', [
            'form_video' => $form->createView(),
            'video_to_edit' => $video
        ]);
    }

    /**
     * @Route("/espace_videos/suppression_video", name="delete_video_user")
     * @Route("/admin/suppression_video/{uuid_user}/{uuid_video}", name="delete_video_user_by_admin")
     */
    public function delete_video (Request $request, ObjectManager $manager, VideosRepository $videosRepository, $uuid_user = null, $uuid_video = null)
    {
        if ($request->get("v_id")) {
            $id_video = $request->get("v_id");
        }
        else {
            $id_video = $uuid_video;
        }

        $video = $videosRepository->find($id_video);

        //Si video non existante ou non appartenante à un utilisateur
        if (!$video)
        {
            //Alors redirection vers le dashboard, menu principal
            return $this->redirectToRoute('dashboard');
        }
        else
        {
            //Redirection vers le dashboard si video non appartenante à un utilisateur
            if ($video->getIdUser() !== $uuid_user && $uuid_user)
            {
                return $this->redirectToRoute('dashboard');
            }
        }

        if ($video->getIdUser()->getId() === $this->getUser()->getId() || $uuid_user) {

            $manager->remove($video);
            $manager->flush();
        }

        //add flash message here!
        if (!$uuid_video)
            return $this->redirectToRoute('dashboard');
        else
            return $this->redirectToRoute('secur_admin_dashboard');
    }

}
