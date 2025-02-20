<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hasher
    |--------------------------------------------------------------------------
    |
    | This option controls the default hasher used by the framework when
    | hashing passwords for your application. By default, the bcrypt
    | algorithm is used; however, you remain free to modify this option
    | if you wish.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options that should be used
    | when passwords are hashed using the Bcrypt algorithm. This will
    | allow you to tweak the amount of time it takes to hash the given
    | password.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options that should be used
    | when passwords are hashed using the Argon algorithm. These will
    | allow you to control the memory cost and time cost of the hashing
    | process.
    |
    */

    'argon' => [
        'memory' => 1024,
        'threads' => 2,
        'time' => 2,
    ],

];
