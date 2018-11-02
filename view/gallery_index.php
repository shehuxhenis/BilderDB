<?php
if(!empty($_GET['id'])) {
    $_SESSION['id'] = $_GET['id'];
}
?>
<html>

<body>

<div class="container">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="addImage titel">Ein neues Bild hochladen</div>
        <form action = "/picture/upload?id=<?php echo $_GET['id']; ?>" method = "POST" enctype = "multipart/form-data">
            <input type = "file" name = "image" class="inputbox" required />
            <input type="text" name="title" class="inputbox" placeholder="Name max 50 Chars" required />
            <textarea name="desc" class="inputbox" placeholder="Description max 150 Chars" required></textarea>
            <input type = "submit" class="submitbox"/>
        </form>
        </div>
    </div>

    <div class="row">

<?php

if(!empty($_SESSION['id'])) {
    $gallery_id = $_SESSION['id'];
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
        echo '<h4><div style="float:left;">' . $bild->name . '</div><a href="/picture/delete?id='. $gallery_id .'&bildId=' . $bild->id . '" style="float: right; margin-right: 10px;"><i class="fas fa-trash-alt"></i></a><div style="clear: both;"></div></h4>';
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

