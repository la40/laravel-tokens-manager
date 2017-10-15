<?php namespace Lachezargrigorov\TokensManager\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Database\ConnectionInterface;
use Lachezargrigorov\TokensManager\Contracts\TokensRepositoryInterface;

class TokensRepository implements TokensRepositoryInterface
{
    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $connection;

    /**
     * The token database table.
     *
     * @var string
     */
    protected $table;

    /**
     * The hashing key.
     *
     * @var string
     */
    protected $hashKey;

    /**
     * The manager.
     *
     * @var int
     */
    protected $manager;

    /**
     * Create a new token repository instance.
     *
     * @param  string                                   $manager
     * @param  \Illuminate\Database\ConnectionInterface $connection
     * @param  string                                   $table
     * @param  string                                   $hashKey
     */

    public function __construct( $manager, ConnectionInterface $connection, $table, $hashKey )
    {
        $this->manager    = $manager;
        $this->connection = $connection;
        $this->table      = $table;
        $this->hashKey    = $hashKey;

        $this->deleteExpired();
    }

    /**
     * Delete expired tokens.
     *
     * @return void
     */

    protected function deleteExpired()
    {
        $managers = config( 'tokens_manager.managers', [ ] );
        foreach ( $managers as $manager => $config )
        {
            $expiredAt = Carbon::now()->subSeconds($config[ 'expire' ] );

            $this->getTable()->where( 'created_at', '<', $expiredAt )->where( 'manager', $manager )->delete();
        }
    }

    public function create( array $payload = [] )
    {
        $token = $this->createToken();
        $insert = [
            'token'      => $token,
            'manager'    => $this->manager,
            'payload'    => $this->encryptPayload( $payload ),
            'created_at' => new Carbon
        ];

        $this->getTable()->insert( $insert );

        return $token;
    }

    public function get( $token, $delete = true )
    {
        $row = $this->getTable()->where( 'manager', $this->manager )->where( 'token', $token )->first();

        if ( null === $row )
        {
            return null;
        }

        if ( $delete )
        {
            $this->delete( $token );
        }

        return $this->decryptPayload( $row->payload );
    }

    public function delete( $token )
    {
        $this->getTable()->where( 'token', $token )->where( 'manager', $this->manager )->delete();
    }

    /** Encrypt payload.
     *
     * @param array $payload
     * @return bool
     */

    protected function encryptPayload( array $payload )
    {
        return Crypt::encrypt( json_encode( $payload ) );
    }

    /** Decrypt payload
     *
     * @param      $payload - crypted payload
     * @return array
     */

    protected function decryptPayload( $payload )
    {
        return json_decode( Crypt::decrypt( $payload ), true );
    }

    /**
     * Create a new token.
     *
     * @return string
     */
    protected function createToken()
    {
        return hash_hmac( 'sha256', Str::random( 40 ), $this->hashKey );
    }

    /**
     * Begin a new database query against the table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getTable()
    {
        return $this->connection->table( $this->table );
    }
}


