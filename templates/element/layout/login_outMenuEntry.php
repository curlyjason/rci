<?php
$email = $this->getIdentity()?->email;

if (is_null($email)):
?>
    <a class="section-break" href="/users/login">Login</a>
<?php else: ?>
    <a class="section-break" href="/users/logout">Logout</a>
<?php
endif;
