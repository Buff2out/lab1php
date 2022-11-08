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
                echo "profile";
            }
            else if ("movie" == $req)
            {
                echo "movie";
            }
            else if ("" == $req)
            {
                echo showFilms();
            }
            else if (0 < $req && $req < 6)
            {
                echo showFilms($req);
            }
            else
            {
                echo "failGET";
            }
            break;
        case "POST":
            switch ($req)
            {
                case "login":
                    echo login($connect, $requestData);
                    break;
                case "registration":
//                    $login = $requestData->body->login;
//                    $user = $connect->query(
//                        "SELECT id from Users
//                    WHERE username='$login'"
//                    )->fetch_assoc();
//                    echo json_encode($user);
                    register($connect, $requestData);
                    break;
                default:
                    echo "failPOST\n";
                    echo $req;
                    break;
            }
            break;
    }
