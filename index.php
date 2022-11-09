<?php
    function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    header('content-type: application/json');
    require_once "connectToDataBase.php";
    require_once "getUrlList.php";
    require_once "showFilms.php";
    require_once "getBody.php";
    require_once "./_registration/registration.php";
    require_once "./_login/login.php";
    require_once "./_login/auth.php";
    require_once "./_login/logout.php";
    require_once "statuscodes.php";
    require_once "./_profile/profile.php"; // editProfile(), getProfile()

    $connect = getConnect_lab1php();
    $urlList = getUrlList();
    $req = $urlList[0];
    $requestData = getBody(getMethod());
    // created years from 1900 to 2021

    //    for ($i = 1900; $i < 2022; $i++)
    //    {
    //        $connect->query("INSERT INTO years (year) VALUES ('$i')")
    //    }

    switch (getMethod())
    {
        case "GET":
            if ("profile" == $req)
            {
                $userFromToken = json_decode(tryAuthByToken($connect));
                echo getProfile($connect, $userFromToken->id) . " \n";
                //http_response_code(200);
            }
            else if ("movie" == $req)
            {
                echo "movie";
            }
            else if ("login" == $req)
            {
                $userFromToken = tryAuthByToken($connect);
                echo $userFromToken;
            }
            else if ("logout" == $req)
            {
                logout($connect);
            }
            else if ("" == $req)
            {
                echo showFilms($connect);
            }
            else if (0 < $req && $req < 6)
            {
                echo showFilms($connect, $req);
            }
            else
            {
                echo "failGET";
            }
            break;
        case "POST":
            switch ($req)
            {
                case "profile":
                    $userFromToken = json_decode(tryAuthByToken($connect));
                    echo editProfile($connect, $userFromToken->id, $requestData);
                    break;
                case "login":
                    echo login($connect, $requestData) . " \n";
                    // echo http_response_code(415) . " login works\n";
                    break;
                case "registration":
                    register($connect, $requestData);
                    break;
                default:
                    echo "failPOST\n";
                    echo $req;
                    break;
            }
            break;
    }
