<?php

namespace App\Controller;

use App\Entity\Videos;
use App\Form\AjoutVideoType;
use App\Repository\UtilisateurRepository;
use App\Repository\VideosRepository;
use DateTime;
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
}
