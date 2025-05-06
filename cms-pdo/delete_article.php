<?php
require_once "init.php";
checkedUserLoggedIn();

if(isPostRequest()) {
  
    $id = $_POST['id'];
    $article = new Article();

    if($article->deleteWithImage($id)){
        redirect("admin.php");
    } else {
        echo "FAILED TO DELETE";
    }
}
?>