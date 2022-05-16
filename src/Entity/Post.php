<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[Entity(repositoryClass: PostRepository::class)]
class Post implements EntityInterface
{
    /**
     * @param string $title
     * @param string $content
     */
    public function __construct(
        string $title,
        string $content
    ) {
        $this->createdAt = new DateTime();
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->setTitle($title);
        $this->setContent($content);
    }

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups(['list', 'detail'])]
    private ?string $id = null;

    #[Column(type: "string", length: 255)]
    #[Groups(['list', 'detail'])]
    private string $title;

    #[Column(type: "string", length: 255)]
    #[Groups(['detail'])]
    private string $content;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private DateTime|null $createdAt = null;

    #[Column(name: "published_at", type: "datetime", nullable: true)]
    private DateTime|null $publishedAt = null;

    #[OneToMany(mappedBy: "post", targetEntity: Comment::class, cascade: [
        'persist',
        'merge',
        'remove'
    ], fetch: 'LAZY', orphanRemoval: true)]
    private Collection $comments;

    #[ManyToMany(targetEntity: Tag::class, mappedBy: "posts", cascade: ['persist', 'merge'], fetch: 'EAGER')]
    private Collection $tags;

    /**
     * @return ?string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPost($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removePost($this);
        }

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }


    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            $comment->setPost(null);
        }

        return $this;
    }
}
