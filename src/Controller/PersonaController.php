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

use App\Entity\Persona;

class PersonaController extends AbstractController
{
    /**
     * @Route("/persona", name="persona_list")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función encargada de la vista principal de Personas
     */
    public function index()
    {
        $personas = $this->getDoctrine()->getRepository(Persona::class)->findAll();
        return $this->render('persona/index.html.twig', [
            'controller_name' => 'PersonaController',
            'personas' => $personas
        ]);
    }

    /**
     * @Route("/persona/save")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para guardar información estatica en la BD.
     */
    public function save() {
        $entityManager = $this->getDoctrine()->getManager();
        $persona = new Persona();
        $persona->setNombres('DIEGO FERNANDO');
        $persona->setApellidos('MOLANO CEBALLOS');
        $persona->setEmail('diego.molano26@hotmail.com');

        $entityManager->persist($persona);
        $entityManager->flush();

        return new Response('Guardada la persona. Su ID es: '.$persona->getId());
    }

    /**
     * @Route("/persona/new", name="new_persona")
     * Method({"GET", "POST"})
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para crear un nuevo registro de persona.
     */
    public function new(Request $request) {
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
    public function edit(Request $request, $id) {
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
     * @Route("/persona/{id}", name="ver")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para ver el detalle de un registro.
     */
    public function ver($id) {
        $persona = $this->getDoctrine()->getRepository(Persona::class)->find($id);

        return $this->render('persona/ver.html.twig', array('persona' => $persona));
    }

    /**
     * @Route("/persona/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
        $persona = $this->getDoctrine()->getRepository(Persona::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($persona);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
    
}
