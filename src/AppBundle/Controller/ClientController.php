<?php

namespace AppBundle\Controller;

/**use AppBundle\Entity\Client;**/
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Client;

use AppBundle\Form\ClientType;
    /**
     * @Route("/users")
     */
    class ClientController extends Controller
    {
		/**
		 * @Route("/clients", name="clients")
		 */
		public function indexAction()
		{
			$clients1 = [
			['name'=>'Client 1','age'=>78],
			['name'=>'Client 2','age'=>89],
			];

        // interrogation db
        $clients = $this->getDoctrine()->getRepository('AppBundle:Client')->findAllSorted();
        //$clients = $this->getDoctrine()->getRepository('AppBundle:Client')->findAll();

        return $this->render('client/index.html.twig', array('title' => 'Faites vous connaitre',
        	'clients' => $clients,
        	));
    }

    /**
     * @Route("/clients/new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        // recuperation des données postées
        //$test = $request.get('name');
        //var_dump($request);
        $params = $request->request->all(); // recupere tous param requete post
        // cf api
        //var_dump($test['name']);

        // creation client
        $client = new Client();
        $client->setName($params['name']);
        //$client.setName ($params['name']);
        $client->setAge ($params['age']);

        //var_dump($client);
        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();
        //return new Response($params["name"]);
        // render vers template possible 
        return $this->redirectToRoute('clients');
    }

    /**
     * @Route("/clients/{id}/delete", name="client_delete")
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        // recuperation client grace id
        $client = $em->getRepository('AppBundle:Client')->find($id);
        //var_dump()
        // supr
        $em->remove($client);
        $em->flush();
        return $this->redirectToRoute('clients');
    }

    /**
     * @Route("/clients/home", name="client_home")
     *
     */
    public function homeAction()
    {
        return $this->redirectToRoute('clients');
    }
    
    /**
     * @Route("/clients/form")
     *
     */
    public function formAction()
    {
        // générer formulaire automatique  à partir entity client et transmettre au template
        return $this->render('client/form.html.twig', array());
    }
    
    //* @Method("POST")
    /**
     * @Route("/clients/{id}/edit", name="client_edit")
     *
     */
    public function editAction($id)
        {
        $client = new Client();  
        $form = $this->createForm(ClientType::class, $client);
        $cliente = $this->getDoctrine()->getRepository('AppBundle:Client')->find($id);
        //var_dump($client);
        //'cliente' => $cliente,
          return $this->render('client/edit.html.twig', array('cliente' => $cliente,'form' => $form->createView() ));
          //  ));
        }

}