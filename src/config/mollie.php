<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application key
    |--------------------------------------------------------------------------
    |
    | The API key given by mollie to use to authenticate yourself
    | This can be also obtained here:
    | https://www.mollie.com/beheer/account/profielen
    |
    */
    'api_key' => '',

    /*
    |--------------------------------------------------------------------------
    | Redirect url
    |--------------------------------------------------------------------------
    |
    | URL where the user will be redirected to when a payment is made
    |
    */
    'redirect_url' => '',

    /*
    |--------------------------------------------------------------------------
    | Webhook url
    |--------------------------------------------------------------------------
    |
    | If you use the Mollie webhook, you can put the full path here.
    | e.g. http://mysite.com/mollie/webhook
    |
    */
    'webhook_url' => '',

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Pagination settings for browsing through the payments history
    |
    */
    // 'limit' => 25,
    // 'offset' => 0
];