<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EntityInterface
{
    const ROLE_USER = ["ROLE_USER"];

    /**
     * @param string $email
     * @param string $password
     * @param UserPasswordHasherInterface $hash
     * @param JWTTokenManagerInterface $jwtManager
     */
    public function __construct(
        string $email,
        string $password,
        UserPasswordHasherInterface $hash,
        private readonly JWTTokenManagerInterface $jwtManager
    ) {
        $this->setEmail($email);
        $this->setRoles(User::ROLE_USER);
        $this->setPassword($hash->hashPassword($this, $password));
    }

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups(['public'])]
    private string $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    #[Groups(['public'])]
    private string $email;

    #[ORM\Column(type: 'json')]
    #[Assert\NotBlank]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private string $password;

    /** @var string */
    private string $token;

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    #[Groups(['registration'])]
    public function getToken(): ?string
    {
        if (!empty($this->jwtManager)) {
            return $this->jwtManager->create($this);
        } else {
            return null;
        }
    }

}
