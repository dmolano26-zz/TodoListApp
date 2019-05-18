<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Usuario;
use App\Entity\Persona;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/usuario", name="usuario_list")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Controlador para el componente de Usuario
     */
    public function index()
    {
        $usuarios = $this->getDoctrine()->getRepository(Usuario::class)->findAll();
        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'usuarioController',
            'usuarios' => $usuarios
        ]);
    }

    /**
     * @Route("/usuario/save")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para guardar información estatica en la BD.
     */
    public function save() {
        $entityManager = $this->getDoctrine()->getManager();
        $usuario = new Usuario();
        $pass = md5('dmolano123');
        $usuario->setUsername('dmolano');
        $usuario->setPassword($pass);
        $usuario->setPersona(1);

        $entityManager->persist($usuario);
        $entityManager->flush();

        return new Response('Guardado el usuario. Su ID es: '.$usuario->getId());
    }

    /**
     * @Route("/usuario/new", name="new_usuario")
     * Method({"GET", "POST"})
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para crear un nuevo registro de usuario.
     */
    public function new_usuario(Request $request) {
        $usuario = new Usuario();

        $form = $this->createFormBuilder($usuario)
            ->add(
                'username', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )   
                )
            )
            ->add(
                'password', PasswordType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'persona', EntityType::class, [
                    // looks for choices from this entity
                    'class' => Persona::class,
                    'placeholder' => 'Escoja una persona',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                
                    // uses the User.username property as the visible option string
                    'choice_label' => function(Persona $persona) {
                        return sprintf('(%d) %s', $persona->getId(), $persona->getNombres());
                    },
                
                    // used to render a select box, check boxes or radios
                    // 'multiple' => true,
                    // 'expanded' => true,
                ],
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
                $usuario = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($usuario);
                $entityManager->flush();

                return $this->redirectToRoute('usuario_list');
            }

            return $this->render('usuario/new.html.twig', array(
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/usuario/edit/{id}", name="edit_usuario")
     * Method({"GET", "POST"})
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para editar un registro de usuario.
     */
    public function edit_usuario(Request $request, $id) {
        $usuario = new Usuario();
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->find($id);

        $form = $this->createFormBuilder($usuario)
            ->add(
                'username', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )   
                )
            )
            ->add(
                'password', PasswordType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'persona', EntityType::class, [
                    // looks for choices from this entity
                    'class' => Persona::class,
                    'placeholder' => 'Escoja una persona',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                
                    // uses the User.username property as the visible option string
                    'choice_label' => function(Persona $persona) {
                        return sprintf('(%d) %s', $persona->getId(), $persona->getNombres());
                    },
                
                    // used to render a select box, check boxes or radios
                    // 'multiple' => true,
                    // 'expanded' => true,
                ],
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

                return $this->redirectToRoute('usuario_list');
            }

            return $this->render('usuario/edit.html.twig', array(
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/usuario/{id}", name="ver_usuario")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Función usada para ver el detalle de un registro.
     */
    public function ver_usuario($id) {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->find($id);

        return $this->render('usuario/ver.html.twig', array('usuario' => $usuario));
    }

    /**
     * @Route("/usuario/delete_usuario/{id}")
     * @Method({"DELETE"})
     */
    public function delete_usuario(Request $request, $id) {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($usuario);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
}
