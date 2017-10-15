<?php namespace Lachezargrigorov\TokensManager\Tests\Unit;

use Illuminate\Database\QueryException;
use Lachezargrigorov\TokensManager\Exceptions\TokensManagerException;
use Lachezargrigorov\TokensManager\TokensManager;
use Tests\TestCase;


class TokensManagerTest extends TestCase
{
    protected $tokensManager;

    public function setUp()
    {
        parent::setUp();

        $unitConfig = require __DIR__.'/../config/unit_config.php';
        config(['tokens_manager.managers.test_manager1' => $unitConfig[ 'test_manager1']]);
        config(['tokens_manager.managers.test_manager2' => $unitConfig[ 'test_manager2']]);
        config(['tokens_manager.managers.test_wrong_connection' => $unitConfig[ 'test_wrong_connection']]);
        config(['tokens_manager.managers.test_wrong_table' => $unitConfig[ 'test_wrong_table']]);

        $this->tokensManager = app('tokens-manager');
    }

    public function testMakeManagerNotFound()
    {
        $this->expectException(TokensManagerException::class);

        $this->tokensManager->use("wrong_manager");
    }

    public function testMake()
    {
        $this->assertInstanceOf(TokensManager::class,$this->tokensManager->use("test_manager1"));
    }

    public function testManagerConnection()
    {
        $token = $this->tokensManager->use("test_manager2")->create();
        $this->assertTrue(is_string($token));

        $this->expectException(\InvalidArgumentException::class);
        $this->tokensManager->use("test_wrong_connection")->create();
    }

    public function testManagerTable()
    {
        $token = $this->tokensManager->use("test_manager2")->create();
        $this->assertTrue(is_string($token));

        $this->expectException(QueryException::class);
        $this->tokensManager->use("test_wrong_table")->create();
    }

    public function testCreate()
    {
        $token = $this->tokensManager->use("test_manager1")->create();
        $this->assertTrue(is_string($token));
    }

    public function testGet()
    {
        $token = $this->tokensManager->use("test_manager1")->create();

        //get and delete the token
        $payload = $this->tokensManager->get($token);
        $this->assertTrue(is_array($payload));
        $tokenNotFound = $this->tokensManager->get($token);
        $this->assertNull($tokenNotFound);

        $token = $this->tokensManager->use("test_manager1")->create(["email" => "email@to.validate"]);

        //get payload without deleting the token
        $this->tokensManager->get($token,false);

        //get it again
        $payload = $this->tokensManager->get($token);
        $this->assertTrue(is_array($payload));
        $this->assertEquals("email@to.validate",$payload["email"]);
    }

    public function testDelete()
    {
        $token = $this->tokensManager->use("test_manager1")->create();
        $this->tokensManager->delete($token);
        $tokenNotFound = $this->tokensManager->get($token);
        $this->assertNull($tokenNotFound);
    }

    public function tearDown()
    {
        $this->app["db"]->table("tokens")->truncate();
    }
}
