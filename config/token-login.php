<?php

return [

    /*
    |--------------------------------------------------------------------------
    | If the token login is enabled
    |--------------------------------------------------------------------------
    */
    'enabled' => env('TOKEN_LOGIN_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | The default model namespace to use for the token authentication
    |--------------------------------------------------------------------------
    */

    'default_model' => 'App\Models\User',

    /*
    |--------------------------------------------------------------------------
    | Disables the generation of tokens for the specific keys. The key
    | contains the model key (example: User->id, where id is the key).
    | The items represent the values.
    |--------------------------------------------------------------------------
    */

    'not_allowed' => [
        'key' => 'id',
        'items' => []
    ],

    /*
    |--------------------------------------------------------------------------
    | The route path to use when logging in with the token
    |--------------------------------------------------------------------------
    */

    'login_path' => '/users/token-login',

    /*
    |--------------------------------------------------------------------------
    | Token Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every token login route, giving you the
    | chance to add your own middleware to this stack or override any of
    | the existing middleware. Or, you can just stick with this stack.
    |
    */

    'middleware' => [
        'web',
        Wefabric\TokenLogin\Http\Middleware\TokenLoginEnabledMiddleware::class
    ],

    /*
    |--------------------------------------------------------------------------
    | The default redirect url or route name
    |--------------------------------------------------------------------------
    */

    'default_redirect' => '/',

    /*
    |--------------------------------------------------------------------------
    | The class to use for the token generation
    |--------------------------------------------------------------------------
    */

    'token_generation_class' => 'Wefabric\TokenLogin\Actions\GenerateTokenAction',

    /*
    |--------------------------------------------------------------------------
    | The token expiration
    |--------------------------------------------------------------------------
    */

    'expires_at' => '+1 week'
];
