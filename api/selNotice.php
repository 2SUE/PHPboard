<?php
    header('Access-Control-Allow-Origin:*');
    header("Access-Control-Allow-Credentials", "true");
    header('Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
    header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');

    $json = json_decode(file_get_contents('php://input'), true);
    $id = ($json["id"]);
    
    require_once("./dbconfig.php");

    $noticeQuery = "SELECT title, content, reg_date, `state` FROM notice WHERE id = :val;";
    $stmt = $pdo->prepare($noticeQuery); 
    $stmt->bindValue(':val', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_NUM); 

    $fileQuery = "SELECT id, origin_name, size, `state` FROM `file` WHERE notice_id = :val;";
    $stmt = $pdo->prepare($fileQuery);  
    $stmt->bindValue(':val', $id, PDO::PARAM_INT);
    $stmt->execute();

    array_push($result, $stmt->fetchAll(PDO::FETCH_NUM)); 
    print_r(json_encode($result)); 
?> 