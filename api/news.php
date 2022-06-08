<?php
    header('Access-Control-Allow-Origin:*');
    header("Access-Control-Allow-Credentials", "true");
    header('Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
    header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');

    $json = json_decode(file_get_contents('php://input'), true);

    try {
        $dbHost = "192.168.1.113:3307";      
        $dbName = "web";     
        $dbUser = "root";       
        $dbPass = "rlawjdaks1!Q";     

        $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM notice where `state`=1 order by id desc";
        $stmt = $pdo->prepare($query); 
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_NUM); 
        print_r(json_encode($result));

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    } 
?>