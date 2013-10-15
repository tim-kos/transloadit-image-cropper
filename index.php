<?php
require_once('helpers.php');

$results = array();
if (isset($_POST['transloadit'])) {
  $results = prepareTransloaditResults($_POST['transloadit']);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Transloadit meets Tito</title>
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" type="text/css" href="css/img_area_select/imgareaselect-default.css" />
</head>
<body>
  <div class="container">
    <h1>Transloadit meets Tito</h1>
    <hr />

    <?php if (!empty($results)) : ?>
      <h2>Form Fields</h2>
      <dl>
        <dt>Name:</dt><dd><?php echo $_POST['name'] ?></dd>
        <dt>Email:</dt><dd><?php echo $_POST['email_address'] ?></dd>
        <dt>Other:</dt><dd><?php echo $_POST['other_form_field'] ?></dd>
      </dl>
      <hr />

      <?php if (isset($results['crop'])) : ?>
        <h2>Crop Results</h2>
        <?php echo displayThumbnails($results['crop']) ?>
        <hr />
      <?php endif; ?>
    <?php endif; ?>
    <div class="row">
      <div class="js-transloadit-upload col-md-4">
        <form role="form" action="index.php" enctype="multipart/form-data" method="POST">
          <div class="form-group">
            <label for="name">Choose some files:</label>
            <input type="file" name="file" id="file" class="form-control" />
          </div>

          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" />
          </div>
          <div class="form-group">
            <label for="email_address">Email address:</label>
            <input type="text" name="email_address" id="email_address" class="form-control" />
          </div>
          <div class="form-group">
            <label for="other_form_field">Another form field:</label>
            <input type="text" name="other_form_field" id="other_form_field" class="form-control" />
          </div>

          <img src="" class="js-img-to-crop img-to-crop" />

          <hr />
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>


  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
  <script src="//assets.transloadit.com/js/jquery.transloadit2-v2-latest.js"></script>
  <script src="js/transloadit_cropper.js"></script>
  <script src="js/jquery.imgareaselect.pack.js"></script>
</body>
</html>
