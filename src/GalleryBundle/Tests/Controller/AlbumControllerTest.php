<?php

namespace GalleryBundle\Tests\Controller;

use Doctrine\Tests\DoctrineTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlbumControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Image Gallery")')->count()
        );
    }

    public function testImageListPages()
    {
        $client = static::createClient();
        $client->request('GET', '/imageList'); // Throws the error
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testHome()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }


    public function testApiGetAction()
    {
        $method = 'GET';
        $uri = '/api/v1/albums';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'albums' => 'album',
        ));

        $client = static::createClient();
        $client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    public function testApiGetImageAction()
    {
        $method = 'GET';
        $uri = '/api/v1/5/images';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'images' => 'image',
        ));

        $client = static::createClient();
        $client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    public function testAlbumsApi()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/v1/albums',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"titleTwo","body":"bodyTwo"}'
        );
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testApiImagePage()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/v1/5/images',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"title1","body":"body1"}'
        );
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }
}
