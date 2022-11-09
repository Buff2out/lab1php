<?php
    function register($connect, $requestData)
    {
        $nickName = $requestData->body->nickName;
        $user = $connect->query(
            "SELECT id from Users 
             WHERE nickName='$nickName'"
        )->fetch_assoc();
        if (is_null($user))
        {
            // var $nickName already exist
            $password = hash("sha1", $requestData->body->password);
            $email = $requestData->body->email;
            $name = $requestData->body->name;
            $gender = $requestData->body->gender;
            $birthday = $requestData->body->birthday;
            $userInsertResult = $connect->query(
                "INSERT INTO Users(nickName, password, email, name, gender, birthday) 
                 VALUES('$nickName', '$password', '$email', '$name', '$gender', '$birthday')"
            );
            if (!$userInsertResult)
            {
                echo http_response_code(415) . " error to insert data to DataBase\n";
            }
            else
            {
                echo http_response_code(201) . " user created\n";
            }
        }
        else
        {
            echo http_response_code(400) . " nickname already exist\n";
        }

    }
