<?php

namespace App\Entity;


use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;

#[Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\Column(type:'uuid', unique:true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?string $id = null;

    #[Column(type: "string", length: 255)]
    private ?string $name;

    #[ManyToMany(targetEntity: Post::class, inversedBy: "tags")]
    #[Ignore]
    private Collection $posts;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->posts = new ArrayCollection();
    }

    public static function of(string $name): Tag
    {
        $tag = new Tag();
        $tag->setName($name);
        return $tag;
    }

    public function getId(): \Ramsey\Uuid\UuidInterface|string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        $this->posts->removeElement($post);

        return $this;
    }
}
