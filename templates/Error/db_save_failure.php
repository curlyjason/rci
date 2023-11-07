<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Database\StatementInterface $error
 * @var string $message
 * @var string $url
 */
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->extend('e500');

if (Configure::read('debug')) :
    $this->append('file');
    $error_set = [
        new \App\Model\Entity\Graph([]),
        'a string',
        new stdClass(),
    ];
    $entity_errors = [
        'field' => [
            'rule' => 'message',
            'other rule' => [
                'field' => [
                    'rule' => 'message',
                    'other rule' => 'other message',
                ],
            ],
        ],
        'other field' => [
            'rule' => 'message',
            'other rule' => 'other message',
        ],
    ];
    ?>
    <strong>Entity errors: </strong>
<?php
    echo $this->element('array_content_type', ['error_set' => $error_set]);
    Debugger::dump($entity_errors,4);
    ?>
    <h1>hello world</h1>
<!--    <h2>val: --><?php //= $val ?><!-- </h2>-->
<!--    <h2>th: --><?php //= $th ?><!-- </h2>-->
<!--    --><?php //= $this->element('error/array_content_type', ['error_set' => $error_set]) ?>
<!--    --><?php
    $this->end();
endif;
?>
