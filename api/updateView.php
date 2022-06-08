<?php
    header('Access-Control-Allow-Origin:*');
    header("Access-Control-Allow-Credentials", "true");
    header('Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
    header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');
    
    $json = json_decode(file_get_contents('php://input'), true);

    require_once("./dbconfig.php");

    $query = "UPDATE board SET view_count = board.view_count +1 WHERE id = :val;"; 
    $stmt = $pdo->prepare($query); 
    $stmt->bindValue(':val', $json["id"], PDO::PARAM_INT);  
    $stmt->execute();
?> 