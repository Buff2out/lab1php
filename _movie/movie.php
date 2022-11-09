<?php
    function getMovieIds($connect, $movie_id)
    {
        $iycd_ids = $connect->query(
            "SELECT id, year_id, country_id, distributer_id
             FROM Movies
             WHERE id = '$movie_id'"
        )->fetch_assoc();
        $genresReq = $connect->query(
            "SELECT genre_id FROM genres_movies
             WHERE movie_id = '$movie_id'"
        );
        $j = 0;
        /* получение массива объектов */
        while ($row1 = $genresReq->fetch_row())
        {
            $iycd_ids['gs'][$j] = $row1[0];
            $j++;
        }
        $iycd_ids['gs']['len'] = $j;

        $reviewsReq = $connect->query(
            "SELECT * FROM Reviews
                 WHERE movie_id = '$movie_id'"
        );
        $j = 0;
        /* получение массива объектов */
        while ($row2 = $reviewsReq->fetch_row())
        {
            $iycd_ids['revs'][$j] = $row2;
            $j++;
        }
        $iycd_ids['revs']['len'] = $j;
        return $iycd_ids;
    }
    function getMovieInfo($connect, $movie_id=1)
    {
        $iycdg_ids = getMovieIds($connect, $movie_id);
        $movie_id = $iycdg_ids['id'];
        $year_id = $iycdg_ids['year_id'];
        $ct_id = $iycdg_ids['country_id'];
        $cm_id = $iycdg_ids['distributer_id'];
        $iycdg_vals = $connect->query(
            "SELECT (SELECT name FROM Movies WHERE id = '$movie_id') as name, 
                    (SELECT year FROM years WHERE id = '$year_id') as year, 
                    (SELECT name FROM countries WHERE id = '$ct_id') as country, 
                    (SELECT name FROM companies WHERE id = '$cm_id') as distributer,
                    poster, budget, fees, tagline, agelimit 
                    FROM Movies WHERE id = '$movie_id'"
        )->fetch_assoc();
        for ($j = 0; $j < $iycdg_ids['gs']['len']; $j++)
        {
            $jstr = $j + 1;
            $genre_id = $iycdg_ids['gs'][$j];
            $iycdg_vals['genre'][$j] = $connect->query(
                "SELECT name as '$jstr' FROM genres WHERE id = '$genre_id'"
            )->fetch_assoc();
            $iycdg_vals['genre'][$j] = $iycdg_vals['genre'][$j][$jstr];
        }
        for ($j = 0; $j < $iycdg_ids['revs']['len']; $j++)
        {
            $jstr = $j + 1;
            $user_id = $iycdg_ids['revs'][$j][1];
            //$movie_id = $iycdg_ids['revs'][$j]['movie_id'];


            $iycdg_vals['reviews'][$j]['user'] = $connect->query(
                "SELECT nickName as '$jstr' FROM Users WHERE id = '$user_id'"
            )->fetch_assoc();
            $iycdg_vals['reviews'][$j]['user'] = $iycdg_vals['reviews'][$j]['user'][$jstr];

            $iycdg_vals['reviews'][$j]['movie'] = $connect->query(
                "SELECT name as '$jstr' FROM Movies WHERE id = '$movie_id'"
            )->fetch_assoc();
            $iycdg_vals['reviews'][$j]['movie'] = $iycdg_vals['reviews'][$j]['movie'][$jstr];
            $iycdg_vals['reviews'][$j]['isAnonimous'] = $iycdg_ids['revs'][$j][3];
            $iycdg_vals['reviews'][$j]['header'] = $iycdg_ids['revs'][$j][4];
            $iycdg_vals['reviews'][$j]['text'] = $iycdg_ids['revs'][$j][5];
            $iycdg_vals['reviews'][$j]['dateCreated'] = $iycdg_ids['revs'][$j][6];
            $iycdg_vals['reviews'][$j]['dateUpdated'] = $iycdg_ids['revs'][$j][7];
            $iycdg_vals['reviews'][$j]['rate'] = $iycdg_ids['revs'][$j][8];
        }

        return json_encode($iycdg_vals);
    }
