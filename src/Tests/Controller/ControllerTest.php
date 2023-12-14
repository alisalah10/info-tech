<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControllerTest extends WebTestCase
{
    public function testAjouterLivre()
    {
        $client = static::createClient();

        // Replace '/app_book_add' with the actual route for adding a book
        $client->request('POST', '/app_book_add');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Add more assertions based on your controller logic and expected behavior
    }

    public function testShowCart()
    {
        $client = static::createClient();

        // Replace '/cart' with the actual route for showing the cart
        $client->request('GET', '/cart');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Add more assertions based on your controller logic and expected behavior
    }

    // Add more test methods for other controllers as needed
}
