<?php
$email = $this->getIdentity()?->email;

if (!is_null($email)):
?>

<br/><span style="font-size: x-small">Welcome <?= $this->getIdentity()?->email ?></span>

<?php
endif;
