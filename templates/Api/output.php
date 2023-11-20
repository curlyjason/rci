<?php
/**
 * Universal JSON output template
 *
 * If one variable is provided, it will be output as json
 *
 * If multiple variables are provided,
 * the variable names will become properties of a single json object
 * and the values will become the values of those properties.
 *
 * @var \Cake\View\View $this
 */

$output = null;

if (count($this->getVars()) > 1) {
    $output = new stdClass();
    foreach ($this->getVars() as $var) {
        $output->$var = $$var;
    }
} else {
    $var = $this->getVars()[0];
    $output = $$var;
}

echo json_encode($output);
