<?php

namespace Viteloge\MailBundle\Tests\Controller {

    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class DefaultControllerTest extends WebTestCase {

        public function testIndex() {
            $client = static::createClient();

            $this->assertTrue(true);
        }

    }

}
