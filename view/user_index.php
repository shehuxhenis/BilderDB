<div class="content">
<?php
if ($_SESSION['user']->admin) { ?>
        <ul class="list-group">
            <li class="list-group-item"><h2>Benutzer</h2></li>
            <?php
                foreach ($users as $user){
                    $uid = $user->id;
                    echo '<li class="list-group-item"><div class="listing">'. $uid . '</div>';
                    echo '<div class="listing">' . $user->email . '</div>';
                    echo '<div class="listing">' . $user->username .'</div>';
                    echo '<a class="btn btn-danger deletebutton" href="/user/doDeleteByAdmin?id='. $uid .'. ">Delete Account</a><div style="clear: both"></div> </li>';
                }
            ?>
        </ul>
    <?php
}
else if(isset($_SESSION['user'])) {
    echo '
            <form action="/user/doAlter" method="post" enctype="application/x-www-form-urlencoded">
                <div class="form-group">
                    <label for="username">Change Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Username"  maxlength="16" required>
                </div>
                <button name="alterusrnm" type="submit" class="btn btn-default">Change</button><br><br>
            </form>
            <form action="/user/doAlter" method="post" enctype="application/x-www-form-urlencoded">
                <div class="form-group">
                    <label for="password">Change Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password"  maxlength="50" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="passwordrep" placeholder="Rewrite Password"  maxlength="50" required>
                </div>
                <button name="alterpw" type="submit" class="btn btn-default">Change</button>
            <a class="btn btn-danger deletebutton" href="/user/confirmDelete">Delete Account</a>
            </form>';
?></div><?php
}

    else header('Location: /user/login');