<?php

session_start();
require_once __DIR__ ."/vendor/autoload.php";

use \App\Controller\Pages\Home;
use \App\Controller\Pages\Update;
use \App\Controller\Pages\Create;

if(isset($_SESSION['messagerBar'])){
    ?>
    <div class="alert alert-<?php echo $_SESSION['messagerBar']['alert']?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['messagerBar']['messeger']?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
    session_destroy();
}

try{
    
    if(isset($_GET['type'])){

        if($_GET['type'] == 'update'){
    
            echo Update::getUpdate($_GET['id']);
        } else if($_GET['type'] == 'create'){
    
            echo Create::getCreate();
        } else {
            echo Home::getHome();
        }
    
    } else{
        echo Home::getHome();
    }

} catch (\Exception $e){
    $_SESSION['messagerBar'] = ['alert' => 'danger', 'messeger' => $erro->getMessage()];
    header('Location: index.php');
}
