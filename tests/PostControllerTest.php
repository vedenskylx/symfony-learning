<?php

namespace App\Tests;

use App\Exception\PostNotFoundException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testGetAllPosts(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/posts');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $data = $response->getContent();
        $this->assertStringContainsString("Symfony and PHP", $data);
    }

    public function testGetANoneExistingPost(): void
    {
        $client = static::createClient();
        $id = Uuid::uuid4();
        $crawler = $client->request('GET', '/posts/' . $id);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(404);
        $data = $response->getContent();
        $this->assertStringContainsString(sprintf(PostNotFoundException::POST_NOT_FOUND_ERROR_TEMPLATE, $id), $data);
    }
}
