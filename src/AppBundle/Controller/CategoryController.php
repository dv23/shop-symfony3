<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Category;

class CategoryController extends Controller
{
    /**
     * @Route("/admin/categories", name="category_list")
     * @Method("GET")
     */
    public function indexAction()
    {    // recup categories
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        /*return $this->render('category/index.html.twig', array('title' => 'Liste desCategories','categories' => $categories,));**/

        return $this->render('category/index.html.twig', array('categories' => $categories,));
    }

    /**
     * @Route("/admin/categories", name="category_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $category = new Category();

        $params = $request->request->all();

        //$category->setName ('Boussoles');
        $category->setName ($params['name']);
        // ajouter url 24 08 2017
         $category->setUrl ($params['url']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();
        /*
        return $this->render('category/index.html.twig', 
            array(
            // .'category/index.html.twig', array si niveau autre..
                ));
        */
            /* rafraichir liste*/
        return $this->redirectToRoute('category_list');
    }
//* @Method("POST")
    /**
     * @Route("/admin/categories/{id}/edit", name="category_edit")
     */
    public function editAction($id)
        {
          $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
          return $this->render('category/edit.html.twig', array('category' => $category,));
        }

    /**
     * @Route("/admin/categories/{id}/update", name="category_update")
     * @Method("POST")
     */
    //public function updateAction($id, Request $request)
    public function updateAction(Category $category , Request $request)

        { // on recupere l'objet qu'on doit maj
            /* variante 1; recup manuelle objet category grace  a lid 
            $category = this->getDoctrine()
            ->getRepository('AppBundle:Category')->find($id);
            */

        // var 2 on founit en entree fct un object de la categorie;
            // . symfony se charge de recuperation
        $params = $request->request->all();
        $category->setName($params['name']);
        $category->setUrl($params['url']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return $this->redirectToRoute('category_list');
        }

    /**
     * @Route("/admin/categories/{id}/delete", name="category_delete")
     */
    public function deleteAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('category_list');
    }
}