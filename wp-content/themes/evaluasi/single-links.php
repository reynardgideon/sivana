<?php
$pod = pods('links', get_the_id());
if ($pod->exists()) {
  $background = $pod->display('background_image');
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= get_the_title() ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
</head>

<body>
  <section class="hero is-fullheight">
    <div class="columns is-centered mt-6">
      <div class="column is-three-quarters-desktop has-text-centered">
        <figure class="image mx-5 mb-10 is-inline-block">
          <img src="<?php echo $background; ?>">
        </figure>
        <br />
        <h4 class="title mt-5 mx-5 is-uppercase is-4">
          <?= $pod->display('judul') ?>
        </h3>
        <p class="subtitle mx-5 mt-3 is-uppercase">
          <?= $pod->display('subtitle'); ?>
        </p>
        <?php foreach ($pod->field('urls') as $url) : ?>
          <?php $label = explode('||', $url); ?>
          <div class="mx-5 column is-half-desktop is-inline-block"><a href="<?= $label[1] ?>?>" target="_blank"><button class="button is-fullwidth is-info is-rounded"><?= $label[0] ?></button></a></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</body>

</html>