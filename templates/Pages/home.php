            <a href="<?= $this->Url->build('/take-inventory') ?>">Take Inventory</a>
            <a href="<?= $this->Url->build('/set-trigger-levels') ?>">Set Trigger Levels</a>
            <a href="<?= $this->Url->build('/order-now') ?>">Order Now</a>
            <?php if (Configure::read('debug') && false) : ?>
                <a href="<?= $this->Url->build('api/set-inventory.json') ?>">Set Inventory</a>
                <a href="<?= $this->Url->build('api/set-trigger.json') ?>">Set Trigger</a>
                <a href="<?= $this->Url->build('api/order-item.json') ?>">Order</a>
            <?php endif; ?>
            <a href="users/logout">Logout</a>
