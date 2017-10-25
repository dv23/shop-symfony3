<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
/** classe response pout mico test **/
use Symfony\Component\HttpFoundation\Response;

/**use AppBundle\Entity\Product;**/
use AppBundle\Entity\Category;

class DefaultController extends Controller
{
    /**
     * @Route("/shop", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array('categories' => $categories,));
    }
    /**
     * @Route("/test")
     */
    public function testAction()
    {
        return new Response('test reussi');
    }

    /**
    * @Route("/admin")
    */
    public function adminAction()
    {
        return new Response('test admin');
    }

    /**
     * @Route("/{url}")
     */
    public function urlAction($url)
    {
        // verif url correspond categories
        // si oui : on recupere les produits categorie
        //$category = $this->getDoctrine('AppBundle.Category')->findByUrl($url);
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findByUrl($url);
        // si categorie trouve
        if ($category){
            var_dump($category);
            // recup produits appartenant categorie
            $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findByCategory($category[0]->getId());
        }

        return new Response($url);
    }
}
