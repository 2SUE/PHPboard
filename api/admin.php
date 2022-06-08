<?php
    header('Access-Control-Allow-Origin:*');
    header("Access-Control-Allow-Credentials", "true");
    header('Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
    header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');
    
    $json = json_decode(file_get_contents('php://input'), true);
    
    $encrypted_passwd = password_hash(1234, PASSWORD_DEFAULT);
        
        if (password_verify($json["password"], $encrypted_passwd))
        {           
            $characters  = "0123456789";  
            $characters .= "abcdefghijklmnopqrstuvwxyz";  
            $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  
            $characters .= "_";  
              
            $string_generated = "";  
              
            $nmr_loops = 6;  
            while ($nmr_loops--)  
            {  
                $string_generated .= $characters[mt_rand(0, strlen($characters) - 1)];  
            }  

            echo $string_generated;
        } else {
            echo 0;
        }
?> 