<?php
    header('Access-Control-Allow-Origin:*');
    header("Access-Control-Allow-Credentials", "true");
    header('Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
    header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');

    $json = json_decode(file_get_contents('php://input'), true);

    if(isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        $file = $_FILES['image'];
        $dir = 'C:\xampp\file\images\\';
        $max_file_size = 10485760;
        $ext = substr($file['name'], strrpos($file['name'], '.') + 1);
        $path = md5(microtime()) . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $dir.$path)) {
            $data = base64_encode(file_get_contents($dir.$path)); 
            echo 'data:image/'.$ext.';base64,'.$data;  
        }
    }
?> 