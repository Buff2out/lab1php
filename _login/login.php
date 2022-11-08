<?php
    function login($connect, $requestData)
    {
        $userFromToken = tryAuthByToken($connect);
        if (is_null($userFromToken))
        {
            $nickName = $requestData->body->login;
            $password = hash("sha1", $requestData->body->password);
            $user = $connect->query(
                "SELECT id from Users
                 WHERE nickName='$nickName' AND password='$password'"
            )->fetch_assoc();
            if (is_null($user))
            {
                // "400 input data incorrect"
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
                    echo "login works\n";
                    return json_encode($user);
                }
            }
        }
        else
        {
            // 200
            echo "token works\n";
            return $userFromToken;
        }
    }
