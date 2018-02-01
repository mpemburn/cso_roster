<?php

return array(

    /*
    |--------------------------------------------------------------------------
    |   Your auth0 domain
    |--------------------------------------------------------------------------
    |   As set in the auth0 administration page
    |
    */

    'domain'        => getenv('chesapeakespokes.auth0.com'),
    /*
    |--------------------------------------------------------------------------
    |   Your APP id
    |--------------------------------------------------------------------------
    |   As set in the auth0 administration page
    |
    */

    'client_id'     => getenv('PPePRFcQuyQALrt5u51PdwTQqOnlY7Xl'),

    /*
    |--------------------------------------------------------------------------
    |   Your APP secret
    |--------------------------------------------------------------------------
    |   As set in the auth0 administration page
    |
    */
    'client_secret' => getenv('fYBiVI-fhMe3_XP8SDSShvNY4v12B64BeMC9LKfeBg005wiT7NtT6ojJ5VLQxTga'),


    /*
     |--------------------------------------------------------------------------
     |   The redirect URI
     |--------------------------------------------------------------------------
     |   Should be the same that the one configure in the route to handle the
     |   'Auth0\Login\Auth0Controller@callback'
     |
     */

    'redirect_uri'  => getenv('/auth0/callback'),

    /*
    |--------------------------------------------------------------------------
    |   Persistence Configuration
    |--------------------------------------------------------------------------
    |   persist_user            (Boolean) Optional. Indicates if you want to persist the user info, default true
    |   persist_access_token    (Boolean) Optional. Indicates if you want to persist the access token, default false
    |   persist_id_token        (Boolean) Optional. Indicates if you want to persist the id token, default false
    |
    */

    'persist_user' => true,
    'persist_access_token' => true,
    'persist_id_token' => true

    /*
    |--------------------------------------------------------------------------
    |   The authorized token issuers
    |--------------------------------------------------------------------------
    |   This is used to verify the decoded tokens when using RS256
    |
    */
    // 'authorized_issuers'  => [ 'https://XXXX.auth0.com/' ],

    /*
    |--------------------------------------------------------------------------
    |   The authorized token issuers
    |--------------------------------------------------------------------------
    |   This is used to verify the decoded tokens when using RS256
    |
    */
    // 'api_identifier'  => [ ],

);
