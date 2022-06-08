<?php
    header('Access-Control-Allow-Origin:*');
    header("Access-Control-Allow-Credentials", "true");
    header('Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
    header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');
    
    $json = json_decode(file_get_contents('php://input'), true);

    require_once("./dbconfig.php");

    $pagingQuery = "SELECT COUNT(*) as total FROM notice;"; // 페이징 정보
    $stmt = $pdo->prepare($pagingQuery); 
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_NUM); 
    $total_num = $result[0];  

    $list_num = 10;

    $pageNum = isset($json["page"])? intval($json["page"]): 1;
    
    $total_page = ceil($total_num / $list_num);
    $start = ($pageNum) * $list_num;

    $listQuery = "SELECT id, title, reg_date, `state`, view_count
                  FROM notice
                  ORDER bY id DESC
                  LIMIT :val1, :val2;"; // 게시글 정보
    $stmt = $pdo->prepare($listQuery); 
    $stmt->bindValue(':val1', $start, PDO::PARAM_INT); 
    $stmt->bindValue(':val2', $list_num, PDO::PARAM_INT);  
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_NUM); 
    array_push($result, $total_num);
    print_r(json_encode($result));  
?> 