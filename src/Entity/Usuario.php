<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * @UniqueEntity(
 *     fields={"username", "persona"},
 *     errorPath="username",
 *     message="Ya existe un registro con esta información"
 * )
 * Autor: Diego Molano
 * Fecha: 17 Mayo 2019
 * Descripción: Modelo para gestionar los usuarios del sistema
 * Atributos: id, usuario, contrasena, persona
 */
class Usuario implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * 
     */
    public $username;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Persona")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $persona;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function serialize() {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->persona
        ]);
    }

    public function unserialize($string) {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->persona
        ) = unserialize($string, ['allowed_Classes' => false]);
    }
}
