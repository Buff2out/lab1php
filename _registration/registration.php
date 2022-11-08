<?php
    function register($connect, $requestData)
    {
        $nickName = $requestData->body->login;
        $user = $connect->query(
            "SELECT id from Users 
             WHERE nickName='$nickName'"
        )->fetch_assoc();
        if (is_null($user))
        {
            // var $login already exist
            $password = hash("sha1", $requestData->body->password);
            $email = $requestData->body->email;
            $name = $requestData->body->name;
            $userInsertResult = $connect->query(
                "INSERT INTO Users(nickName, password, email, name) 
                 VALUES('$nickName', '$password', '$email', '$name')"
            );
            if (!$userInsertResult)
            {
                echo "fail\n";
            }
            else
            {
                echo "success\n";
            }
        }
        else
        {
            echo "exist\n";
        }

    }
