<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Ignore;

#[Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?string $id = null;

    #[Column(type: "string", length: 255)]
    private string $content;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private DateTime|null $createdAt = null;

    #[ManyToOne(targetEntity: "User", inversedBy: "comments")]
    #[JoinColumn(name: "user_id", referencedColumnName: "id")]
    #[Ignore]
    private User $user;

    #[ManyToOne(targetEntity: "Post", inversedBy: "comments")]
    #[JoinColumn(name: "post_id", referencedColumnName: "id")]
    #[Ignore]
    private Post $post;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public static function of(string $content): Comment
    {
        $comment = new Comment();
        $comment->setContent($content);
        return $comment;
    }


    public function getId(): \Ramsey\Uuid\UuidInterface|string
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface|null $createdAt
     * @return $this
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \App\Entity\Post|null
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param \App\Entity\Post $post
     * @return $this
     */
    public function setPost(Post $post): self
    {
        $this->post = $post;
        return $this;
    }

    /**
     * @return \App\Entity\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param  $user
     * @return $this
     */
    public function setUser($user): self
    {
        $this->user = $user;
        return $this;
    }
}
