<div class="content">
    <ul class="list-group">
        <li class="list-group-item"><h2>Galleries</h2></li>
        <?php
        if (isset($_SESSION['galleries'])) {
            $galleryob = $_SESSION['galleries'];
            foreach ($galleryob as $gallery){
                echo '<li class="list-group-item"><a href="/picture/getPictures?id='. $gallery->id .'&name='. $gallery->name .'">'. $gallery->name .'</a><a style="float: right; margin-left:20px;" href="/gallery/doDelete?id='. $gallery->id .'">Löschen <i class="fas fa-trash-alt"></i></a><a style="float: right;">Bearbeiten <i class="fas fa-edit"></i></a></li>';
            }
        }
        ?>
        <a href="/default/create"><li class="list-group-item"><span class="glyphicon glyphicon-plus"></span> Create New Gallery</li></a>
    </ul>
</div>