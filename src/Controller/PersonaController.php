<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Persona;
use App\Entity\Actividad;

class PersonaController extends AbstractController
{
    /**
     * @Route("/persona", name="persona_list")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función encargada de la vista principal de Personas
     */
    public function index(Request $request)
    {
        $personas = $this->getDoctrine()->getRepository(Persona::class)->findAll();
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
        return $this->render('persona/index.html.twig', [
            'controller_name' => 'PersonaController',
            'personas' => $personas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/persona/new", name="new_persona")
     * Method({"GET", "POST"})
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para crear un nuevo registro de persona.
     */
    public function new_persona(Request $request) {
        $persona = new Persona();

        $form = $this->createFormBuilder($persona)
            ->add(
                'nombres', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )   
                )
            )
            ->add(
                'apellidos', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'email', TextType::class, array(
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
                $persona = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($persona);
                $entityManager->flush();

                return $this->redirectToRoute('persona_list');
            }

            return $this->render('persona/new.html.twig', array(
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/persona/edit/{id}", name="edit_persona")
     * Method({"GET", "POST"})
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para editar un registro de persona.
     */
    public function edit_persona(Request $request, $id) {
        $persona = new Persona();
        $persona = $this->getDoctrine()->getRepository(Persona::class)->find($id);

        $form = $this->createFormBuilder($persona)
            ->add(
                'nombres', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )   
                )
            )
            ->add(
                'apellidos', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'email', TextType::class, array(
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

                return $this->redirectToRoute('persona_list');
            }

            return $this->render('persona/edit.html.twig', array(
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/persona/{id}", name="ver_persona")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para ver el detalle de un registro.
     */
    public function ver_persona($id) {
        $persona = $this->getDoctrine()->getRepository(Persona::class)->find($id);

        return $this->render('persona/ver.html.twig', array('persona' => $persona));
    }

    /**
     * @Route("/persona/delete_persona/{id}")
     * @Method({"DELETE"})
     */
    public function delete_persona(Request $request, $id) {
        $persona = $this->getDoctrine()->getRepository(Persona::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($persona);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
    
}
