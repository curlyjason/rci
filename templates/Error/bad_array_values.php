<?php

use App\Constants\E500Con;
use Cake\Core\Configure;
use Cake\Utility\Text;

/**
 * @var \Cake\View\View $this
 * @var $message
 * @var $expected_value
 * @var $error_set
 * @var $source_count
 */
$this->extend('e500');

if (Configure::read('debug')) :
    $this->append('file');

    $expected_list = Text::toList($expected_value);
    $caption = count($error_set) . ' of ' . $source_count . " values were not allowed.
    Allowed values are $expected_list.
    The disallowed value types and their array index numbers are:";
//    debug($expected_value);
//    debug($error_set);
//    debug($source_count);

    echo $this->element('array_content_type', ['error_set' => $error_set, 'caption' => $caption]);

    $this->end();
endif;



