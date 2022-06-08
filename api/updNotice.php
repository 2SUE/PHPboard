<?php
    header('Access-Control-Allow-Origin:*');
    header("Access-Control-Allow-Credentials", "true");
    header('Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
    header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');
    
    $json = json_decode(file_get_contents('php://input'), true);
    echo sizeof($_FILES);
    require_once("./dbconfig.php");

    $query = "UPDATE board SET title = :val1, content = :val2 WHERE id = :val3;"; 
    $stmt = $pdo->prepare($query); 
    $stmt->bindValue(':val1', $_POST["title"], PDO::PARAM_STR); 
    $stmt->bindValue(':val2', $_POST["content"], PDO::PARAM_STR);  
    $stmt->bindValue(':val3', $_POST["id"], PDO::PARAM_INT);  
    $stmt->execute();

    if (sizeof($_FILES) > 0) {
        $dir = 'C:\xampp\file\link\\';
        $max_file_size = 10485760;
        for ($i = 0; $i < sizeof($_FILES); $i++) {
            $file = $_FILES[$i];
            $size = $file["size"];
            $name = $file["name"];

            $query = "INSERT INTO `file` (board_id, origin_name, size)
                        VALUES (:val1, :val2, :val3);"; 
            $stmt = $pdo->prepare($query); 
            $stmt->bindValue(':val1', $_POST["id"], PDO::PARAM_INT); 
            $stmt->bindValue(':val2', $name, PDO::PARAM_STR);  
            $stmt->bindValue(':val3', $size, PDO::PARAM_INT);  
            $stmt->execute();
            $fileId = $pdo->lastInsertId();

            $ext = substr($file["name"], strrpos($file["name"], '.') + 1);
            $path = $fileId . '.' . $ext;
            if (move_uploaded_file($file["tmp_name"], $dir.$path)) {
                $query = "UPDATE `file` SET `state` = 1 WHERE id = :val1;"; 
                $stmt = $pdo->prepare($query); 
                $stmt->bindValue(':val1', $fileId, PDO::PARAM_INT); 
                $stmt->execute();
            } else {
                $query = "UPDATE `file` SET `state` = 3 WHERE id = :val1;"; 
                $stmt = $pdo->prepare($query); 
                $stmt->bindValue(':val1', $fileId, PDO::PARAM_INT); 
                $stmt->execute();
                continue; // 파일 업로드 실패
            }
        }
    }
?> 