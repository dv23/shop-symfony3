<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
/** classe response pout mico test **/
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Form\ProductType;
use AppBundle\Entity\Product;

class ProductController extends Controller
    {
    /**
     * @Route("/admin/products")
     */
    public function indexAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        $test = "get";
        if ($form->isSubmitted()) {
            $test = "post";
            // si requete POST alors enreg donnees postees
            // evite d'avoir a remplir 1 a 1 les propriétés
            $product = $form->getData();
            //var_dump($product);
            $category_id = $product->getCategory()->getId();
            $product->setCategory($category_id);
            var_dump($product);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
        }

        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAll();

// on souhaite récupérer tous les produits ainsi que les media associés

        // getEntityManager lorsqu'on effectue la requête dans le repository
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('
            SELECT p FROM AppBundle:Product p
            JOIN AppBundle:Media m
            WHERE p.id = m.product
        ');
        $products2 = $query->getResult();
        var_dump($products2);

        return $this->render('product/index.html.twig', array('form' => $form->createView(),
            'products' => $products,
        // 'test' => $test,
            ));
    }

    /**
    * @Route("/admin/products/new")
    */
    public function newAction(Request $request)
    {
        // replace this example code with whatever you need
        return new Response('new action');
    }
}