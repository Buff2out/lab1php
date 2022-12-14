<?php
    function tryAuthByToken($connect)
    {
        $token = explode(" ", getallheaders()['Authorization'])[1];
        if (is_null($token))
        {
            return null; // can't get token from headers
        }
        $user = $connect->query(
            "SELECT * FROM Users
             WHERE id = (
             SELECT user_id FROM tokens WHERE token='$token')"
        )->fetch_assoc();
        if (is_null($user))
        {
            return null; // invalid token or doesn't exist in DB;
        }
        return json_encode($user);
    }