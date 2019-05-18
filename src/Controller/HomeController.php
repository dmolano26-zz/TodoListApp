<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Actividad;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Controlador para la página inicial
     */
    public function index(Request $request)
    {

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
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form->createView()
        ]);
    }
}
