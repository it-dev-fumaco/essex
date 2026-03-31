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
            'name' => 'ERPNext',
            'label' => 'Enterprise Resource Planning',
            'url' => 'http://10.0.0.83',
            'icon' => 'fa-building',
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
            'label' => 'Logistic Management System',
            'url' => 'http://lms.fumaco.com',
            'icon' => 'fa-truck',
        ],
    ],
];
