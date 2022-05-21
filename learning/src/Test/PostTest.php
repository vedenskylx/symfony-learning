<?php

namespace App\Test;

use App\Factory\PostFactory;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{

    public function testPost()
    {
        $p = PostFactory::create("tests title", "tests content");

        $this->assertEquals("tests title", $p->getTitle());
        $this->assertEquals("tests content", $p->getContent());
        $this->assertNotNull($p->getCreatedAt());
    }
}
