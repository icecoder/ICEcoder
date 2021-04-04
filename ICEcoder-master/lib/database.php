<?php
function adminer_object() {
    include_once "./adminer-plugins/plugin.php";
    include_once "./adminer-plugins/frames.php";
    
    $plugins = array(
        new AdminerFrames()
    );
    
    return new AdminerPlugin($plugins);
}

// include original Adminer or Adminer Editor
include "./database-adminer-480-en.php";
?>