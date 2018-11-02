<html>

<body>

<div class="container">
    <div class="row">

        <?php

        if(!empty($_GET['id'])) {
            $gallery_id = $_GET['id'];
        }
        $dir = 'data/'.$gallery_id.'/thumbs/';
        $file_display = array('jpg', 'jpeg', 'png', 'gif');

        if ( file_exists( $dir ) == false ) {
            echo 'Hoops! No pictures found';
        } else {

            foreach($bilder as $bild) {
                echo '<div class="col-md-3">';
                echo '<div class="thumbnail">';
                echo '<a data-fancybox="gallery" href="/data/'.$gallery_id.'/'.$bild->filename.'">';
                echo '<img src="/'. $dir . '/' . $bild->filename .'" alt="', "Bild konnte nicht gefunden werden." , '" />';
                echo '</a>';
                echo '<h4><div style="float:left;">' . $bild->name . '</div></h4>';
                echo '<p>' . $bild->description . '</p>';
                echo '</div>';
                echo '</div>';
            }
        }

        ?>


    </div>
</div>
</body>
</html>

