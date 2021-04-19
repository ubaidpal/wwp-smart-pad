<?php

return [

    //'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN')),
    'dsn' => 'https://0ba50985eb5e4a5691f0bf17f7238afc@sentry.io/1487324',

    // capture release as git sha
    // 'release' => trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD')),

    'breadcrumbs' => [

        // Capture bindings on SQL queries logged in breadcrumbs
        'sql_bindings' => true,

    ],

];
