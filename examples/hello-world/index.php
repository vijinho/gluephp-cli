<?php
require_once "../../glue.php";

    $urls = array(
        '/' => 'index'
    );
    
    class index {
        function GET() {
            echo "Hello, World!";
        }
    }
    
    glue::stick($urls);
    

