<?php namespace Lachezargrigorov\TokensManager\Contracts;

interface TokensRepositoryInterface
{

    /** Create a new token record.
     *
     * @param array $payload
     *
     * @return string - token
     */

    public function create( array $payload = [] );

    /** Get payload for token.
     *
     * @param      $token
     * @param bool $delete
     *
     * @return null | array
     */

    public function get( $token, $delete = true );

    /**
     * Delete a token record by token.
     *
     * @param  string $token
     *
     * @return void
     */

    public function delete($token);

}
