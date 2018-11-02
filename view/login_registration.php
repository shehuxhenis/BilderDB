<?php
$message = "";

if(isset($_POST['password']) && isset($_POST['passwordrep'])) {
    if ($_POST['password'] != $_POST['passwordrep']) {
        $message = '<div class="alert alert-danger">The passwords do not match!</div>';
    } else {
        $registration = new userController();
        if(!$registration->doRegistration()) {
            $message = '<div class="alert alert-danger">This E-Mail is already registered.</div>';
        } else {
            $registration->doRegistration();
        }
    }
}
?>
<div class="content">
  <form action="/user/registration" method="post" enctype="application/x-www-form-urlencoded">
    <div class="form-group">
      <input type="email" id="email" class="form-control" name="email" placeholder="Email max 50 Chars" maxlength="50" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}" value="<?= (!empty($_POST['email'])) ? $_POST['email'] : "" ?>" required><br><br>
    </div>
    <div class="form-group">
      <input type="text" id="username" class="form-control" name="username" placeholder="Username max 16 Chars" value="<?= (!empty($_POST['username'])) ? $_POST['username'] : "" ?>" maxlength="16" required><br><br>
    </div>
    <div class="form-group">
      <input type="password" id="password" class="form-control" name="password" placeholder="Password max 50 Chars" maxlength="50" pattern="((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,20})" required><br><br>
    </div>
    <div class="form-group">
      <input type="password" id="passwordrep" class="form-control" name="passwordrep" placeholder="Rewrite Password max 50 Chars" maxlength="50" required>
    </div>
    <button name="reg" type="submit" class="btn btn-default">Submit</button>
  </form>
</div>