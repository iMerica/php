<?php

use \PHP\LoadLab;
use PHPUnit\Framework\TestCase;

class LoadLabTest extends TestCase
{

    public function setUp()
    {
        $env_file_path = __DIR__ . '/../';

        if (file_exists($env_file_path . '.env')) {
            $dotenv = new Dotenv\Dotenv($env_file_path);
            $dotenv->load();
        }

    }

    public function testInvalidAPIKey()
    {
        $this->expectException('\Exception');
        $LoadLab = new LoadLab\LoadLab('abc');
    }

    public function testTestEnvironment()
    {
        $MC_API_KEY = getenv('MC_API_KEY');
        $message    = 'No environment variables! Copy .env.example -> .env and fill out your MailChimp account details.';
        $this->assertNotEmpty($MC_API_KEY, $message);
    }

    public function testInstantiation()
    {
        $MC_API_KEY = getenv('MC_API_KEY');

        if (!$MC_API_KEY) {
            $this->markTestSkipped('No API key in ENV');
        }

        $MailChimp = new LoadLab\LoadLab($MC_API_KEY);
        $this->assertInstanceOf('\DrewM\MailChimp\MailChimp', $MailChimp);
    }

    public function testSubscriberHash()
    {
        $MC_API_KEY = getenv('MC_API_KEY');

        if (!$MC_API_KEY) {
            $this->markTestSkipped('No API key in ENV');
        }

        $LoadLab = new LoadLab\LoadLab($MC_API_KEY);

        $email    = 'Foo@Example.Com';
        $expected = md5(strtolower($email));
        $result   = $LoadLab->subscriberHash($email);

        $this->assertEquals($expected, $result);
    }

    public function testResponseState()
    {
        $MC_API_KEY = getenv('MC_API_KEY');

        if (!$MC_API_KEY) {
            $this->markTestSkipped('No API key in ENV');
        }

        $LoadLab = new LoadLab\LoadLab($MC_API_KEY);

        $LoadLab->get('lists');

        $this->assertTrue($LoadLab->success());
    }

}