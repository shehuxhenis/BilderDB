<div class="content">
    <ul class="list-group">
        <li class="list-group-item"><h2>Öffentliche Gallerien</h2></li>
        <?php
            foreach ($publicGalleries as $gallery){
                echo '<li class="list-group-item"><a href="/picture/getPictures?id='. $gallery->id .'&name='. $gallery->name .'">'. $gallery->name .'</a>';
            }
        ?>
    </ul>
</div>