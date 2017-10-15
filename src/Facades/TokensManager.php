<?php namespace Lachezargrigorov\TokensManager\Facades;

use Illuminate\Support\Facades\Facade as LaravelFacade;

class TokensManager extends LaravelFacade
{
    protected static function getFacadeAccessor() { return 'tokens-manager'; }
}
