<?php
    function get_iycdg($connect, $req)
    {
        for ($ind = ($req - 1)*5; $ind < ($req)*5; $ind++) {
            $iycd_ids[$ind]['iycd'] = $connect->query(
                "SELECT id, year_id, country_id, distributer_id
             FROM Movies
             WHERE id = '$ind' + 1"
            )->fetch_assoc();
        }
        for ($ind = ($req - 1)*5; $ind < ($req)*5; $ind++) {
            $movie_id = $iycd_ids[$ind]['iycd']['id'];
            $result = $connect->query(
                "SELECT genre_id FROM genres_movies
                 WHERE movie_id = '$movie_id'"
            );
            $j = 0;
            /* получение массива объектов */
            while ($row = $result->fetch_row()) {
                $iycd_ids[$ind]['gs'][$j] = $row[0];
                $j++;
            }
            $iycd_ids[$ind]['gs']['len'] = $j;
        }
        return $iycd_ids;
    }

    function showFilms($connect, $req = 1)
    {
        $iycdg_ids = get_iycdg($connect, $req);
        for ($ind = ($req - 1)*5; $ind < ($req)*5; $ind++)
        {
            $movie_id = $iycdg_ids[$ind]['iycd']['id'];
            $year_id = $iycdg_ids[$ind]['iycd']['year_id'];
            $ct_id = $iycdg_ids[$ind]['iycd']['country_id'];
            $cm_id = $iycdg_ids[$ind]['iycd']['distributer_id'];
            $iycdg_vals[$ind + 1] = $connect->query(
                "SELECT (SELECT name FROM Movies WHERE id = '$movie_id') as name, 
                    (SELECT year FROM years WHERE id = '$year_id') as year, 
                    (SELECT name FROM countries WHERE id = '$ct_id') as country, 
                    (SELECT name FROM companies WHERE id = '$cm_id') as distributer,
                    (SELECT poster FROM Movies WHERE id = '$movie_id') as poster"
                )->fetch_assoc();
        }
        for ($ind = ($req - 1)*5; $ind < ($req)*5; $ind++)
        {
            for ($j = 0; $j < $iycdg_ids[$ind]['gs']['len']; $j++)
            {
                $jstr = $j + 1;
                $genre_id = $iycdg_ids[$ind]['gs'][$j];
                $iycdg_vals[$ind+1]['genre'][$j] = $connect->query(
                    "SELECT name as '$jstr' FROM genres WHERE id = '$genre_id'"
                )->fetch_assoc();
                $iycdg_vals[$ind+1]['genre'][$j] = $iycdg_vals[$ind+1]['genre'][$j][$jstr];
            }
        }
//        for ($ind = ($req - 1)*5; $ind < ($req)*5; $ind++) {
//            $iycd_ids[$ind]['iycd'] = $connect->query(
//                "SELECT id, year_id, country_id, distributer_id
//             FROM Movies
//             WHERE id = '$ind' + 1"
//            )->fetch_assoc();
//        }
//        for ($ind = ($req - 1)*5; $ind < ($req)*5; $ind++) {
//            $movie_id = $iycd_ids[$ind]['iycd']['id'];
//            $result = $connect->query(
//                "SELECT genre_id FROM genres_movies
//                 WHERE movie_id = '$movie_id'"
//            );
//            $j = 0;
//            /* получение массива объектов */
//            while ($row = $result->fetch_row()) {
//                $iycd_ids[$ind]['gs'][$j] = $row[0];
//                $j++;
//            }
//        }
        return json_encode($iycdg_vals);
    }