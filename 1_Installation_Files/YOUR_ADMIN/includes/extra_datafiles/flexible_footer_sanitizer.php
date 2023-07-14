<?php

declare(strict_types=1);

/**
 * @package Multi Language Flexible Footer
 * @copyright Copyright 2008-2017 Zen4All
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */
if (class_exists('AdminRequestSanitizer')) {
    $sanitizer = AdminRequestSanitizer::getInstance();
    $group = [
        'col_header' => [
            'sanitizerType' => 'PRODUCT_DESC_REGEX',
            'method' => 'both',
            'pages' => ['flexible_footer_menu'],
            'params' => []
        ],
        'page_title' => [
            'sanitizerType' => 'PRODUCT_DESC_REGEX',
            'method' => 'both',
            'pages' => ['flexible_footer_menu'],
            'params' => []
        ],
        'col_html_text' => [
            'sanitizerType' => 'PRODUCT_DESC_REGEX',
            'method' => 'both',
            'pages' => ['flexible_footer_menu'],
            'params' => []
        ],
        'page_url' => [
            'sanitizerType' => 'PRODUCT_URL_REGEX',
            'method' => 'both',
            'pages' => ['flexible_footer_menu'],
            'params' => []
        ],
    ];
    $sanitizer->addComplexSanitization($group);
}
