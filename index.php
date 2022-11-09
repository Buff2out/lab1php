<?php
    function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    header('content-type: application/json');
    require_once "connectToDataBase.php";
    require_once "getUrlList.php";
    require_once "./_movie/showFilms.php";
    require_once "./_movie/movie.php";
    require_once "./_movie/SetReview.php";
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
    $lenOfFilms = $connect->query(
        "SELECT COUNT(*) as '0' FROM Movies"
    )->fetch_assoc();
    $lenOfFilms = $lenOfFilms[0];
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
                $movie_id = $urlList[1];
                if (is_null($movie_id))
                {
                    echo getMovieInfo($connect);
                }
                else if ($movie_id <= $lenOfFilms)
                {
                    echo getMovieInfo($connect, $movie_id);
                }
                else
                {
                    http_response_code(400);
                    echo "400 Request doesn't exist\n";
                }
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
            else if (0 < $req && $req <= ceil($lenOfFilms / 5))
            {
                echo showFilms($connect, $req);
            }
            else
            {
                http_response_code(400);
                echo "400 Request doesn't exist\n";
            }
            break;
        case "POST":
            switch ($req)
            {
                case "movie":
                    $movie_id = $urlList[1];
                    $SetReviewToMovie = $urlList[2];
                    $userFromToken = json_decode(tryAuthByToken($connect));
                    $user_id = $userFromToken->id;
                    if ("setrev" ==  $SetReviewToMovie && $movie_id <= $lenOfFilms && !is_null($user_id))
                    {
                        setReviewToMovie($connect, $user_id, $movie_id, $requestData);
                    }
                    else if ("updrev" ==  $SetReviewToMovie && $movie_id <= $lenOfFilms && !is_null($user_id))
                    {
                        echo updateReview($connect, $user_id, $movie_id, $requestData);
                    }
                    else if ("delrev" ==  $SetReviewToMovie && $movie_id <= $lenOfFilms && !is_null($user_id))
                    {
                        deleteReview($connect, $user_id, $movie_id);
                    }
                    else
                    {
                        http_response_code(400);
                        echo "400 Request doesn't exist or user is not authorized\n";
                    }
                    break;
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
