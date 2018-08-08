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
     * @Route("/compte", name="dashboard")
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
     * @Route("/ajout_video", name="add_video_user")
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
     * @Route("/edition_video", name="edit_video_user")
     */
    public function edit_video (Request $request, ObjectManager $manager, VideosRepository $videosRepository)
    {
        $id_video = $request->get("v_id");
        //$id_video = $id;

        $video = $videosRepository->find($id_video);

        $form = $this->createForm( AjoutVideoType::class, $video); //todo mode edit

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($video);
            $manager->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render ( 'account_routes/edit_video.html.twig', [
            'form_video' => $form->createView(),
            'video_to_edit' => $video
        ]);
    }

    /**
     * @Route("/suppression_video", name="delete_video_user")
     */
    public function delete_video (Request $request, ObjectManager $manager, VideosRepository $videosRepository)
    {
        $id_video = $request->get("v_id");
        //$id_video = $id;

        $video = $videosRepository->find($id_video);

        if ($video->getIdUser()->getId() === $this->getUser()->getId()) {

            $manager->remove($video);
            $manager->flush();
        }

        //add flash message here!

        return $this->redirectToRoute('dashboard');
    }

}
