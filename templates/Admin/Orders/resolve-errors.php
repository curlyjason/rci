<?php

use \App\View\AppView;

/**
 * @var AppView $this
 * @var \App\Forms\OrderNowForm $executedForm
 */

$order = $executedForm->getData('result');
?>

<style>
    pre {
        padding-left: 2rem;
    }
</style>

<p>Errors were found when saving the order</p>
<pre>
<?= var_export($order->getErrors(), true) ?>
</pre>
<p>These are the elements of the order which could be interpreted</p>
<pre>
<?= var_export($order->toArray(), true) ?>
</pre>
