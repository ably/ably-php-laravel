<?php

/*
|--------------------------------------------------------------------------
| Ably configuration file
|--------------------------------------------------------------------------
|
| You may use any of the ClientOptions here.
| See the complete list here: https://www.ably.com/documentation/rest/usage#client-options
|
| A key or any other valid means of authenticating is required.
*/

return [
    'key' => env('ABLY_KEY', 'replaceThis.with:yourAblyKey'),
];
