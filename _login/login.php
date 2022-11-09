<?php
    function login($connect, $requestData)
    {
        $nickName = $requestData->body->nickName;
        $password = hash("sha1", $requestData->body->password);
        $user = $connect->query(
            "SELECT id from Users
             WHERE nickName='$nickName' AND password='$password'"
        )->fetch_assoc();
        if (is_null($user))
        {
            echo http_response_code(400) . " input data incorrect\n";
            return null;
        }
        else
        {
            $token = bin2hex(random_bytes(16));
            $user_id = $user['id'];
            $tokenInsertResult = $connect->query(
                "INSERT INTO tokens(user_id, token) 
                 VALUES ('$user_id', '$token')"
            );
            if (!$tokenInsertResult)
            {
                // 400
                return json_encode($connect->error);
            }
            else
            {
                // 200
                $user = $connect->query(
                    "SELECT * FROM Users
                     WHERE id = '$user_id'"
                )->fetch_assoc();
                // echo json_encode(["token" => $token]);
                echo $token . "\n";
                return json_encode($user);
            }
        }
    }
