<?php
    function logout($connect)
    {
        $token = explode(" ", getallheaders()['Authorization'])[1];
        if (is_null($token))
        {
            echo http_response_code(500) . "can't get token from headers\n";
            return null; // can't get token from headers
        }
        $tokenDeleteResult = $connect->query(
            "DELETE FROM tokens
             WHERE token = (
             SELECT token FROM tokens WHERE token='$token')"
        );
        if (!$tokenDeleteResult)
        {
            echo http_response_code(500) . " Fail to delete token\n";
        }
        else
        {
            echo http_response_code(200) . "Success to delete token\n";
        }
    }