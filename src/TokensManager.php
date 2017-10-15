<?php namespace Lachezargrigorov\TokensManager;

use Illuminate\Support\Str;
use Lachezargrigorov\TokensManager\Exceptions\TokensManagerException;
use Lachezargrigorov\TokensManager\Repositories\TokensRepository;

class TokensManager
{
    protected $app;

    /**
     * @var TokensRepository
     */

    protected $repository;

    /**
     * TokensManager constructor.
     *
     * @param $app
     */

    public function __construct( $app )
    {
        $this->app = $app;
    }

    /** Resolve new TokensManager.
     * @param $manager
     *
     * @return $this
     * @throws TokensManagerException
     */

    public function use( $manager )
    {
        //check if manager exist in config
        if ( config( "tokens_manager.managers.{$manager}", false ) === false )
        {
            throw  new TokensManagerException( "{$manager} manager not found in ...config/tokens_manager.php!" );
        }

        //key
        $key = $this->app[ 'config' ][ 'app.key' ];

        if ( Str::startsWith( $key, 'base64:' ) )
        {
            $key = base64_decode( substr( $key, 7 ) );
        }

        //connection
        $connection = config('tokens_manager.managers.'.$manager.'.connection',config('tokens_manager.connection'));

        //table
        $table = config("tokens_manager.managers.".$manager.".table",config("tokens_manager.table"));

        $this->repository = new TokensRepository(
            $manager,
            $this->app[ 'db' ]->connection( $connection ),
            $table,
            $key
        );

        return $this;
    }

    /** Create a new token record.
     *
     * @param array $payload
     * @return string - token
     */

    public function create( array $payload = [] )
    {
        return $this->repository->create( $payload );
    }

    /** Get payload for token.
     *
     * @param      $token
     * @param bool $delete
     * @return null | array
     */
    public function get( $token, $delete = true )
    {
        return $this->repository->get( $token, $delete );
    }

    /**
     * Delete a token record by token.
     *
     * @param  string $token
     * @return void
     */
    public function delete( $token )
    {
        $this->repository->delete( $token );
    }

}
