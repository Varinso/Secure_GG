<?php
require_once __DIR__ . '/security/bootstrap.php';
require_once __DIR__ . '/db.php';
require_login();
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Secure Upload</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"></head>
<body class="container" style="max-width:700px;margin-top:40px;">
  <h3>Secure File Upload</h3>
  <form action="upload_handle.php" method="POST" enctype="multipart/form-data" class="form">
    <?php echo csrf_input(); ?>
    <div class="form-group">
      <label>Select file (PNG/JPG/PDF, max 2MB)</label>
      <input type="file" name="file" class="form-control" required>
    </div>
    <button class="btn btn-primary">Upload</button>
  </form>
</body></html>
