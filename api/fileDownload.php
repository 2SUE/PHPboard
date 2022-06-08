<?php
    header('Access-Control-Allow-Origin:*');
    header("Access-Control-Allow-Credentials", "true");
    header('Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
    header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');
    
    $json = json_decode(file_get_contents('php://input'), true);

    $id = $json["id"];
    
    require_once("./dbconfig.php");

    // id 값으로 파일 정보랑 파일 경로 찾기
    // 해당 파일 리턴
    $query = "SELECT * from `file` WHERE id = :val1; and `state`=1"; 
    $stmt = $pdo->prepare($query); 
    $stmt->bindValue(':val1', $id, PDO::PARAM_INT);  
    $stmt->execute();
    $result = $stmt->fetch(PDO::PARAM_INT);

    $real_filename = $result["origin_name"];
    $ext = substr($real_filename, strrpos($real_filename, '.'));
    $file_dir = 'C:\xampp\file\link\\'.$id.$ext;

    $h = $filename = iconv("UTF-8", "EUC-KR", $real_filename);
    
    header('Access-Control-Expose-Headers: *');
    header('Content-Type: application/x-octetstream');
    header('Content-Length: '.filesize($file_dir));
    header('Content-Disposition: attachment; filename='.$h);
    header('Content-Transfer-Encoding: binary');

    $fp = fopen($file_dir, "r");
    fpassthru($fp);
    fclose($fp);
?> 