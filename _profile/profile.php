<?php
    function getProfile($connect, $user_id)
    {
        // 200
        $user = $connect->query(
            "SELECT email, avatar, name, birthday, gender FROM Users
                     WHERE id = '$user_id'"
        )->fetch_assoc();
        // echo json_encode(["token" => $token]);
        return json_encode($user);
    }

    function editProfile($connect, $user_id, $requestData)
    {
        if (!is_null($requestData->body->email))
        {
            $email = $requestData->body->email;
            $userUpdateEmail = $connect->query(
                "UPDATE Users
             SET email = '$email'
             WHERE id = $user_id"
            );
            if (!$userUpdateEmail)
            {
                return http_response_code(415) . " error to insert data to DataBase\n";
            }
        }
        if (!is_null($requestData->body->avatar))
        {
            $avatar = $requestData->body->avatar;
            $userUpdateAvatar = $connect->query(
                "UPDATE Users
             SET avatar = '$avatar'
             WHERE id = '$user_id'"
            );
            if (!$userUpdateAvatar)
            {
                return http_response_code(415) . " error to insert data to DataBase\n";
            }
        }
        if (!is_null($requestData->body->name))
        {
            $name = $requestData->body->name;
            $userUpdateName = $connect->query(
                "UPDATE Users
             SET name = '$name'
             WHERE id = '$user_id'"
            );
            if (!$userUpdateName)
            {
                return http_response_code(415) . " error to insert data to DataBase\n";
            }
        }
        if (!is_null($requestData->body->birthday))
        {
            $birthday = $requestData->body->birthday;
            $userUpdateBirthday = $connect->query(
                "UPDATE Users
             SET birthday = '$birthday'
             WHERE id = '$user_id'"
            );
            if (!$userUpdateBirthday)
            {
                return http_response_code(415) . " error to insert data to DataBase\n";
            }
        }
        if (!is_null($requestData->body->gender))
        {
            $gender = $requestData->body->gender;
            $userUpdateGender = $connect->query(
                "UPDATE Users
             SET gender = '$gender'
             WHERE id = '$user_id'"
            );
            if (!$userUpdateGender)
            {
                return http_response_code(415) . " error to insert data to DataBase\n";
            }
        }
        $userFinal = $connect->query(
            "SELECT email, avatar, name, birthday, gender FROM Users
                     WHERE id = '$user_id'"
        )->fetch_assoc();
        // echo json_encode(["token" => $token]);
        return json_encode($userFinal);
    }