<?php
    header('Access-Control-Allow-Origin:*');
    header("Access-Control-Allow-Credentials", "true");
    header('Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
    header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');
    
    $json = json_decode(file_get_contents('php://input'), true);

    $id = $json["id"];

    // id 값으로 파일 정보랑 파일 경로 찾기
    // 해당 파일 리턴
    try {
        $dbHost = "192.168.1.113:3307";      
        $dbName = "web";     
        $dbUser = "root";       
        $dbPass = "rlawjdaks1!Q";   
    
        $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("set names utf8");

        $query = "SELECT * from `file` WHERE notice_id = :val1; and `state`=1"; 
        $stmt = $pdo->prepare($query); 
        $stmt->bindValue(':val1', $id, PDO::PARAM_INT);  
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_NUM); 
       
        print_r(json_encode($result)); 
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    } 
?> 
