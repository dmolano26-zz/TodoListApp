<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

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
     * Descripción: Función usada para guardar información estatica en la BD
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
     * @Route("persona/{id}", name="ver")
     */
    public function ver($id) {
        $persona = $this->getDoctrine()->getRepository(Persona::class)->find($id);

        return $this->render('persona/ver.html.twig', array('persona' => $persona));
    }
}
