<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActividadController extends AbstractController
{
    /**
     * @Route("/actividad", name="actividad")
     * Autor: Diego Molano
     * Fecha: 17 Mayo 2019
     * Descripción: Controlador para el componente de Actividad
     */
    public function index()
    {
        return $this->render('actividad/index.html.twig', [
            'controller_name' => 'ActividadController',
        ]);
    }
}
