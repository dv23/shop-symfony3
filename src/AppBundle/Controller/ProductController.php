<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
            //$category_id = $product->getCategory()->getName();
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

        /* gallery
        return $this->render('product/gallery.html.twig', array('form' => $form->createView(),
            'products' => $products, 'test' => $test,));
        */
        /* OK avec form */
            return $this->render('product/index.html.twig', array('form' => $form->createView(), 'products' => $products, /*'test' => $test*/
            ));
    }

        /* + List */
        /*    return $this->render('product/list.html.twig', array('products' => $products,));
        }*/

        /* edit 
        return $this->render('product/edit.html.twig', array('form' => $form->createView(),
            'products' => $products, 'test' => $test, ));
            }
        */

    /**
     * @Route("/admin/products/new")
     */
    /*public function newAction(Request $request)
    {
        // replace this example code with whatever you need
        return new Response('new action');
    }
    */

    /**
     * @Route("/admin/products/{id}/edit", name="product_edit")
     */
    public function editAction($id, Request $request)
    //public function editAction(Product $product, Request $request)
        {
        $producte = new Product();
        //$form = $this->createForm(ProductType::class, $product);
        $producte = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        if (null === $producte) {
            throw new NotFoundHttpException("L''id ".$id." n'existe pas.");
                                }
        //$producte = $this->getDoctrine()->getRepository('AppBundle:Product')->findOneById($id);
        $form = $this->get('form.factory')->create(ProductType::class, $producte);
        //var_dump($producte);
        return $this->render('product/edit.html.twig', array('producte' => $producte, 'form' => $form->createView() ));
        //return $this->render('product/edit.html.twig', array('product' => $product, 'form' => $form->createView()));
        }
    
    /**
     * @Route("/admin/products/{id}/modifier", name="product_modifier")
     */
    public function modifierAction($id)
        {
          $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
          return $this->render('product/modifier.html.twig', array('product' => $product,));
        }
    
    /**
     * @Route("/admin/products/{id}/update", name="product_update")
     * @Method("POST")
     */
    //public function updateAction($id, Request $request)
    public function updateAction(Product $product , Request $request)
        { // on recupere l'objet qu'on doit maj
            /* variante 1; recup manuelle objet category grace  a lid 
            $category = this->getDoctrine()
            ->getRepository('AppBundle:Category')->find($id);
            */

        // var 2 on founit en entree fct un object de la categorie;
            // . symfony se charge de recuperation
        $params = $request->request->all();
        $product->setName($params['name']);
        $product->setPresentation($params['presentation']);
        $product->setPrice($params['price']);
        $product->setStock($params['stock']);
        $product->setTechs($params['techs']);
        $product->setDetails($params['details']);
        $product->setUrl($params['url']);
        // modif categorie impossible
        $product->setCategory($params['category']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('product_list');
        }

    /**
     * @Route("/admin/products/{id}/delete", name="product_delete")
     */
    /*public function deleteAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('product_list');
    }*/

    /**
     * @Route("/list/products", name="product_list")
     * @Method("GET")
     */  
    public function listAction()
    {   // recup products
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAll();
        return $this->render('product/list.html.twig', array('products' => $products,));
    }

    /**
     * @Route("/admin/product/new")
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $params = $request->request->all(); // cf category controller

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            // modification des propriétés categorie et product
            // de façon à ce qu'elles correspondent au typage
            // de la table sql associées
            $name = $product->getName();
            $product->setName($name); // string
            $product->setPresentation ($product->getPresentation());
            //$product->setPresentation ($params['presentation']); // string
            //$product->setCategory($product->getCategory()->getId()); // integer
            $product->setCategory($product->getCategory()); // integer Correction 10 5 2019
            $product->setPrice($product->getPrice()); // float
            // Enregistrement des données dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
        }

        return $this->render('product/new.html.twig', array('form' => $form->createView(),
        ));
    }

    }