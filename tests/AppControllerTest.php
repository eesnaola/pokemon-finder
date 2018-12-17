<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppControllerTest extends WebTestCase
{
    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Buscar')->form();
        $form['form[name]']  = 'pika';
        $client->submit($form);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('pikachu', $client->getResponse()->getContent());
    }
}
