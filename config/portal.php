<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Homepage helpful articles (max rows under quick links)
    |--------------------------------------------------------------------------
    */
    'homepage_article_limit' => 8,

    /*
    |--------------------------------------------------------------------------
    | Homepage "Systems Access" shortcuts
    |--------------------------------------------------------------------------
    | Mirrors copy from resources/views/portal/system.blade.php for consistency.
    */
    'systems' => [
        [
            'name' => 'NextERP',
            'label' => 'Enterprise Resource Planning',
            'url' => '10.0.0.83:8000',
            'icon' => 'fa-building',
        ],
        [
            'name' => 'Essex',
            'label' => 'Employee Portal',
            'url' => 'http://essex.fumaco.com',
            'icon' => 'fa-user',
        ],
        [
            'name' => 'Athena ERP',
            'label' => 'Inventory',
            'url' => 'http://athena.fumaco.com',
            'icon' => 'fa-boxes',
        ],
        [
            'name' => 'MES',
            'label' => 'Manufacturing Execution System',
            'url' => 'http://mes.fumaco.com',
            'icon' => 'fa-industry',
        ],
        [
            'name' => 'Learning Portal',
            'label' => 'Learning Portal',
            'url' => 'http://learning.fumaco.com',
            'icon' => 'fa-book',
        ],
        [
            'name' => 'LMS',
            'label' => 'Learning Portal',
            'url' => 'http://lms.fumaco.com',
            'icon' => 'fa-book',
        ],
    ],
];
