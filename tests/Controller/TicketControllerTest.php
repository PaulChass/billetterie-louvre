<?php 
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketControllerTest extends WebTestCase
{
    public function testhomePage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Bienvenue")')->count()
        );
    }

    public function testBooking()
    {
        $client = static::createClient();

        $crawler = $client->request('GET','/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Envoyer');

// select the form that contains this button
        $form = $buttonCrawlerNode->form();
// gets the raw values
        $values = $form->getPhpValues();
        $values['reservation']['reservationDate']='2019-02-15 10:00:00';
        $values['reservation']['emailAddress'] = 'p@p.p';
        $values['reservation']['ticket'][0]['firstName']='D';
        $values['reservation']['ticket'][0]['lastName']="Rose";
        $values['reservation']['ticket'][0]['birthDay']="1996-04-10";
        $values['reservation']['ticket'][0]['country']='France';
        $values['reservation']['ticket'][0]['checkbox']='false';

        $values['reservation']['ticket'][1]['firstName']='D';
        $values['reservation']['ticket'][1]['lastName']="Wade";
        $values['reservation']['ticket'][1]['birthDay']="1996-04-10";
        $values['reservation']['ticket'][1]['country']='France';
        $values['reservation']['ticket'][1]['checkbox']='false';

// submits the form with the existing and new values
        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());
        $crawler = $client->submit($form);
// the 2 tags have been added to the collection

        $this->assertEquals(1,$crawler->filter('html:contains("Wade")')->count());
    }
}