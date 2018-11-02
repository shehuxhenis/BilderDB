<div class="content" style="width: 600px">
    <form action="/default/doCreate" method="post" enctype="application/x-www-form-urlencoded">
        <div class="form-group">
            <input type="text" class="form-control" name="name" placeholder="Name max 50 Chars" maxlength="50" required>
        </div>
        <div class="form-group">
            <textarea id="username" class="form-control" name="desc" placeholder="Description max 150 Chars" maxlength="150" required></textarea>
        </div>
        <div class="form-group">
            <label for="public"><input type="checkbox" id="public" name="public" value="checked"> Öffentlich zugänglich</label>
        </div>
        <button name="gal" type="submit" class="btn btn-default">Submit</button>
    </form>
</div>