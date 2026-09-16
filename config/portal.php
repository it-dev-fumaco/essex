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
            'url' => 'https://erp.fumaco.net',
            'icon' => 'fa-building',
        ],
        [
            'name' => 'Athena ERP',
            'label' => 'Inventory',
            'url' => 'https://athena.fumaco.net',
            'icon' => 'fa-boxes',
        ],
        [
            'name' => 'MES',
            'label' => 'Manufacturing Execution System',
            'url' => 'https://mes.fumaco.net',
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
            'url' => 'https://lms.fumaco.net',
            'icon' => 'fa-truck',
        ],
    ],
];
