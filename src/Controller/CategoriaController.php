<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Categoria;
use App\Entity\Actividad;


class CategoriaController extends AbstractController
{
    /**
     * @Route("/categoria", name="categoria_list")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Controlador para el componente de categoria
     */
    public function index(Request $request)
    {
        $categorias = $this->getDoctrine()->getRepository(Categoria::class)->findAll();
        $form = $this->createFormBuilder()
        ->add(
            'actividad', EntityType::class, [
                'class' => Actividad::class,
                'placeholder' => 'Busqueda de actividad',
                'attr' => array(
                    'class' => 'form-control select2 mr-sm-2'
                ),
                'choice_label' => function(Actividad $actividad) {
                    return sprintf('(%d) %s', $actividad->getId(), $actividad->getNombre());
                },
            ],
        )
        ->add(
            'buscar', ButtonType::class, array(
                'label' => 'Buscar',
                'attr' => array(
                    'class' => 'btn btn-success my-2 my-sm-0',
                    'onclick' => 'buscar_actividad();'
                )
            )
        )
        ->getForm();

        $form->handleRequest($request);
        return $this->render('categoria/index.html.twig', [
            'controller_name' => 'categoriaController',
            'categorias' => $categorias,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/categoria/new", name="new_categoria")
     * Method({"GET", "POST"})
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para crear un nuevo registro de categoria.
     */
    public function new_categoria(Request $request) {
        $categoria = new Categoria();

        $form = $this->createFormBuilder($categoria)
            ->add(
                'nombre', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )   
                )
            )
            ->add(
                'guardar', SubmitType::class, array(
                    'label' => 'Crear',
                    'attr' => array('class' => 'btn btn-primary mt-3')
                )
            )
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $categoria = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($categoria);
                $entityManager->flush();

                return $this->redirectToRoute('categoria_list');
            }

            return $this->render('categoria/new.html.twig', array(
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/categoria/edit/{id}", name="edit_categoria")
     * Method({"GET", "POST"})
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para editar un registro de categoria.
     */
    public function edit_categoria(Request $request, $id) {
        $categoria = new Categoria();
        $categoria = $this->getDoctrine()->getRepository(Categoria::class)->find($id);

        $form = $this->createFormBuilder($categoria)
            ->add(
                'nombre', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )   
                )
            )
            ->add(
                'guardar', SubmitType::class, array(
                    'label' => 'Editar',
                    'attr' => array('class' => 'btn btn-primary mt-3')
                )
            )
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('categoria_list');
            }

            return $this->render('categoria/edit.html.twig', array(
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/categoria/{id}", name="ver_categoria")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para ver el detalle de un registro.
     */
    public function ver_categoria($id) {
        $categoria = $this->getDoctrine()->getRepository(Categoria::class)->find($id);

        return $this->render('categoria/ver.html.twig', array('categoria' => $categoria));
    }

    /**
     * @Route("/categoria/delete_categoria/{id}")
     * @Method({"DELETE"})
     */
    public function delete_categoria(Request $request, $id) {
        $categoria = $this->getDoctrine()->getRepository(Categoria::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categoria);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
}
