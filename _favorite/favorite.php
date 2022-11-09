<?php
    function getFavIds($connect, $user_id)
    {
        $favReq = $connect->query(
            "SELECT movie_id FROM favorite
             WHERE user_id = '$user_id'"
        );
        $j = 0;
        /* получение массива айдишек */
        while ($row1 = $favReq->fetch_row())
        {
            $favIds[$j] = $row1[0];
            $j++;
        }
        for ($i = 0; $i < $j; $i++)
        {
            $movie_id = $favIds[$i];
            $filmIds[$i] = $connect->query(
                "SELECT * FROM Movies WHERE id = '$movie_id'"
            )->fetch_assoc();
            $result = $connect->query(
                "SELECT genre_id FROM genres_movies
                 WHERE movie_id = '$movie_id'"
            );
            $j1 = 0;
            /* получение массива объектов */
            while ($row = $result->fetch_row())
            {
                $filmIds[$i]['gs'][$j1] = $row[0];
                $j1++;
            }
            $filmIds[$i]['gs']['len'] = $j1;

        }
        $filmIds[0]['len'] = $j;
        return $filmIds;
    }
    function getFavorite($connect, $user_id)
    {
        $filmIds = getFavIds($connect, $user_id);
        // $newFilmVar
        for ($i = 0; $i < $filmIds[0]['len']; $i++)
        {
            $movie_id = $filmIds[$i]['id'];
            $year_id = $filmIds[$i]['year_id'];
            $ct_id = $filmIds[$i]['country_id'];
            $cm_id = $filmIds[$i]['distributer_id'];
            $newFilmVar[$i] = $connect->query(
                "SELECT (SELECT name FROM Movies WHERE id = '$movie_id') as name, 
                    (SELECT year FROM years WHERE id = '$year_id') as year, 
                    (SELECT name FROM countries WHERE id = '$ct_id') as country, 
                    (SELECT name FROM companies WHERE id = '$cm_id') as distributer,
                    (SELECT poster FROM Movies WHERE id = '$movie_id') as poster"
            )->fetch_assoc();
            for ($j1 = 0; $j1 < $filmIds[$i]['gs']['len']; $j1++)
            {
                $jstr = $j1 + 1;
                $genre_id = $filmIds[$i]['gs'][$j1];
                $newFilmVar[$i]['genre'][$j1] = $connect->query(
                    "SELECT name as '$jstr' FROM genres WHERE id = '$genre_id'"
                )->fetch_assoc();
                $newFilmVar[$i]['genre'][$j1] = $newFilmVar[$i]['genre'][$j1][$jstr];
            }
        }
        return json_encode($newFilmVar);
    }
    function addToFavorite($connect, $user_id, $movie_id=1)
    {
        $film = $connect->query(
            "SELECT * FROM favorite WHERE user_id = '$user_id' AND movie_id='$movie_id'"
        )->fetch_assoc();
        if (is_null($film))
        {
            $favoriteInsertResult = $connect->query(
                "INSERT INTO favorite(user_id, movie_id)
             VALUES ('$user_id', '$movie_id')"
            );
            if (!$favoriteInsertResult)
            {
                echo http_response_code(415) . " error to insert data to DataBase\n";
            }
            else
            {
                echo http_response_code(201) . " added to wishlist successful";
            }
        }
        else
        {
            http_response_code(400);
            echo "400 already added to wishlist";
        }
    }
    function deleteFromFavorite($connect, $user_id, $movie_id=1)
    {
        $film = $connect->query(
            "SELECT * FROM favorite WHERE user_id = '$user_id' AND movie_id='$movie_id'"
        )->fetch_assoc();
        if (is_null($film))
        {
            http_response_code(400);
            echo "400. already deleted from wishlist";
        }
        else
        {
            $favoriteInsertResult = $connect->query(
                "DELETE FROM favorite
                 WHERE user_id = '$user_id' AND movie_id = '$movie_id'"
            );
            if (!$favoriteInsertResult)
            {
                echo http_response_code(400) . " error to delete data from DataBase\n";
            }
            else
            {
                echo http_response_code(201) . " removed from wishlist successful";
            }
        }
    }