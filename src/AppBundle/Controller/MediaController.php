<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\MediaType;
use AppBundle\Entity\Media;

class MediaController extends Controller
{
    /**
    * @Route("/admin/media/new")
    */
    public function newAction(Request $request)
    {
    	$media = new Media();
    	$form = $this->createForm(MediaType::class, $media);

    	$form->handleRequest($request);
    	if ($form->isSubmitted()){
    		// dossier de reception pr dossier poste
    		$dir = $this->get('kernel')->getRootDir().'/../web/files';
    		// on obtient un objet de type UploadedFile
    		$file = $media->getFile();
    		$filename = $file->getClientOriginalName();
    		//var_dump($filename);

    		// création du fichier sur le serveur
    		$file->move($dir, $filename);
            // modification des propriétés file et product
            // de façon à ce qu'elles correspondent au typage
            // de la table sql associées
            $media->setFile($filename); // string
            $media->setProduct($media->getProduct()->getId()); // integer

            // Enregistrement des données dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($media);
            $em->flush();
    	}

        return $this->render('media/new.html.twig', array('form' => $form->createView(),
        ));
    }

    /**
    * @Route("/admin/media")
    */
    public function listAction()
    {
        // récupération des media
        $media = $this->getDoctrine()
            ->getRepository('AppBundle:Media')
            ->findAll();

        return $this->render('media/list.html.twig', array(
            'media' => $media,
        ));
    }
}