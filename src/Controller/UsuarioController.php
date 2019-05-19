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
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use App\Entity\Usuario;
use App\Entity\Persona;
use App\Entity\Actividad;

class UsuarioController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @Route("/usuario", name="usuario_list")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Controlador para el componente de Usuario
     */
    public function index(Request $request)
    {
        $usuarios = $this->getDoctrine()->getRepository(Usuario::class)->findAll();
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
        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'usuarioController',
            'usuarios' => $usuarios,
            'form' => $form->createView()
        ]);
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
                    'class' => Persona::class,
                    'placeholder' => 'Escoja una persona',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'choice_label' => function(Persona $persona) {
                        return sprintf('(%d) %s', $persona->getId(), $persona->getNombres());
                    },
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
                try {
                    $username = $form['username']->getData();
                    $password = $form['password']->getData();
                    $persona = $form['persona']->getData();
                    $usuario = new Usuario();
                    $usuario->setUsername($username);
                    $usuario->setPassword($this->passwordEncoder->encodePassword(
                                    $usuario,
                                    $password
                                ));
                    $usuario->setPersona($persona);                   
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($usuario);
                    $entityManager->flush();
                    return $this->redirectToRoute('usuario_list');
                } catch (UniqueConstraintViolationException $e) {
                    echo 'Ya existe un registro con esta información';
                }
                
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
                    'class' => Persona::class,
                    'placeholder' => 'Escoja una persona',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'choice_label' => function(Persona $persona) {
                        return sprintf('(%d) %s', $persona->getId(), $persona->getNombres());
                    },
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
                try {
                    $username = $form['username']->getData();
                    $password = $form['password']->getData();
                    $persona = $form['persona']->getData();
                    $usuario->setUsername($username);
                    $usuario->setPassword($this->passwordEncoder->encodePassword(
                                    $usuario,
                                    $password
                                ));
                    $usuario->setPersona($persona);     
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();    
                    return $this->redirectToRoute('usuario_list');
                } catch (UniqueConstraintViolationException $e) {
                    echo 'Ya existe un registro con esta información';
                }
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
