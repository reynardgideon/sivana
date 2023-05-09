<?php
function render_step2($order)
{
    $i = $order - 1;
    $row_num = ($order % 2 == 0) ? 2 : 1;
    $colors = array(
        'sekretariat' => 'link',
        'pengkaji'  => 'success',
        'pembimbing'    => 'warning'
    );

?>
    <div class="row row-<?= $row_num ?> <?= STEP_OWNER[$i] ?>">
        <section class="step">
            <span class="icon"><?= $order ?></span>
            <article class="message">
                <div class="message-header has-background-<?= $colors[STEP_OWNER[$i]] ?>">
                    <div class="details">
                        <span class="title"><?= STEP_TITLE[$i] ?></span>

                    </div>
                </div>
                <div class="message-body has-text-justified">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. <strong>Pellentesque risus mi</strong>, tempus quis placerat ut, porta nec nulla. Vestibulum rhoncus ac ex sit amet fringilla. Nullam gravida purus diam, et dictum <a>felis venenatis</a> efficitur. Aenean ac <em>eleifend lacus</em>, in mollis lectus. Donec sodales, arcu et sollicitudin porttitor, tortor urna tempor ligula, id porttitor mi magna a neque. Donec dui urna, vehicula et sem eget, facilisis sodales sem.
                </div>
            </article>
        </section>
    </div>
<?php
}

function render_progress($params, $data)
{
    $i = $params['order'];
    $row_num = ($params['order'] % 2 == 0) ? 2 : 1;
    $colors = array(
        'sekretariat' => 'link',
        'pengkaji'    => 'success',
        'pembimbing'  => 'warning'
    );
?>
    <div class="row row-<?= $row_num ?> <?= $params['role'] ?>">
        <section class="step">
            <span class="icon <?= $params['role'] ?>">
                <i class="mdi mdi-24px mdi-file-document-outline pt-2"></i>
            </span>
            <article class="message is-medium">
                <div class="message-header has-background-<?= $colors[$params['role']] ?>">
                    <div class="level details container">
                        <div class="level-left">
                            <div class="number-box level-item">
                                <?= strlen($params['order']) > 1 ? $params['order'] : '0' . $params['order'] ?>
                            </div>
                            <div class="level-item"><?= $params['title'] ?></div>
                        </div>
                        <div style="float:right;" class="level-right">
                            <p class="level-item">
                                <?php foreach ($params['owner'] as $pengkaji) : ?>
                                    <a href="<?= get_the_permalink($$pengkaji['id']) ?>">
                                        <figure class="level-item image is-32x32">
                                            <img class="is-rounded" src="<?= $pengkaji['avtar'] ?>">
                                        </figure>
                                    </a>
                                <?php endforeach; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="message-body step has-text-justified py-0">
                    xxx
                    <span class="is-size-7 is-grey"><?= $params['date'] ?></span>

                </div>
            </article>

        </section>
    </div>
<?php
}

?>