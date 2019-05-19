<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface; 

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * Autor: Diego Molano
 * Fecha: 17 Mayo 2019
 * Descripción: Modelo para gestionar los usuarios del sistema
 * Atributos: id, usuario, contrasena, persona
 */
class Usuario implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $contrasena;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Persona")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $persona;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getusuario(): ?string
    {
        return $this->usuario;
    }

    public function setusuario(string $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getcontrasena(): ?string
    {
        return $this->contrasena;
    }

    public function setcontrasena(string $contrasena): self
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(Persona $persona): self
    {
        $this->persona = $persona;

        return $this;
    }

    public function getRoles()
    {
        return [
            'ROLE_USER'
        ];
    }

    public function getSalt() {}

    public function eraseCredentials() {}
}
