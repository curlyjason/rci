<?php

use Cake\Datasource\EntityInterface;
use Cake\Error\Debugger;

/**
 * Renders the errors for a single entity
 *
 * @var \Cake\View\View $this
 * @var \Cake\Datasource\EntityInterface|array $entity_errors
 */

$errors = $entity_errors instanceof EntityInterface ? $entity_errors->getErrors() : $entity_errors;

Debugger::dump($errors, 4);
