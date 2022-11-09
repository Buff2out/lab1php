<?php
    function updateReview($connect, $user_id, $movie_id, $requestData)
    {
        $review = $connect->query(
            "SELECT * FROM Reviews
             WHERE user_id = '$user_id' AND movie_id = '$movie_id'"
        )->fetch_assoc();
        if (is_null($review))
        {
            echo http_response_code(400) . " review doesn't exist\n";
        }
        else
        {
            if (!is_null($requestData->body->header))
            {
                $header = $requestData->body->header;
                $reviewUpdateResult1 = $connect->query(
                    "UPDATE Reviews
                     SET header = '$header'
                     WHERE user_id = '$user_id' AND movie_id = '$movie_id'"
                );
                if (!$reviewUpdateResult1)
                {
                    echo http_response_code(415) . " error to update data in DataBase\n";
                }
            }
            if (!is_null($requestData->body->text))
            {
                $text = $requestData->body->text;
                $reviewUpdateResult2 = $connect->query(
                    "UPDATE Reviews
                     SET text = '$text'
                     WHERE user_id = '$user_id' AND movie_id = '$movie_id'"
                );
                if (!$reviewUpdateResult2)
                {
                    echo http_response_code(415) . " error to update data in DataBase\n";
                }
            }
            if (!is_null($requestData->body->rate))
            {
                $rate = $requestData->body->rate;
                $reviewUpdateResult3 = $connect->query(
                    "UPDATE Reviews
                     SET rate = '$rate'
                     WHERE user_id = '$user_id' AND movie_id = '$movie_id'"
                );
                if (!$reviewUpdateResult3)
                {
                    echo http_response_code(415) . " error to update data in DataBase\n";
                }
            }
            $reviewUpdateResult4 = $connect->query(
                "UPDATE Reviews
                     SET dateUpdated = NOW()
                     WHERE user_id = '$user_id' AND movie_id = '$movie_id'"
            );
            if (!$reviewUpdateResult4)
            {
                echo http_response_code(415) . " error to update data in DataBase\n";
            }
            $review = $connect->query(
                "SELECT 
                 (SELECT nickName FROM Users WHERE id = '$user_id') AS user,
                 (SELECT name FROM Movies WHERE id = '$movie_id') AS movie,
                 header, text, dateCreated, dateUpdated, rate, isAnonimous
                 FROM Reviews
                 WHERE user_id = '$user_id' AND movie_id = '$movie_id'"
            )->fetch_assoc();
            return json_encode($review);
        }
    }
    function deleteReview($connect, $user_id, $movie_id)
    {
        $review = $connect->query(
            "SELECT * FROM Reviews
             WHERE user_id = '$user_id' AND movie_id = '$movie_id'"
        )->fetch_assoc();
        if (is_null($review))
        {
            echo http_response_code(400) . " review already deleted or doesn't exist\n";
        }
        else
        {
            $reviewDeleteResult = $connect->query(
                "DELETE FROM Reviews
                 WHERE movie_id = '$movie_id' AND user_id = '$user_id'"
            );
            if (!$reviewDeleteResult)
            {
                echo http_response_code(415) . " error to delete review from DataBase\n";
            }
            else
            {
                echo http_response_code(200) . " review deleted\n";
            }
        }
    }

    function setReviewToMovie($connect, $user_id, $movie_id, $requestData)
    {
        $review = $connect->query(
            "SELECT * FROM Reviews
             WHERE user_id = '$user_id' AND movie_id = '$movie_id'"
        )->fetch_assoc();
        if (is_null($review))
        {
            $header = $requestData->body->header;
            $text = $requestData->body->text;
            $rate = $requestData->body->rate;
            if (is_null($requestData->body->isAnonimous)) {
                $isAnonimous = 0;
            } else {
                $isAnonimous = $requestData->body->isAnonimous;
            }
            $reviewInsertResult = $connect->query(
                "INSERT INTO Reviews(movie_id, user_id, header, text, isAnonimous, rate)
             VALUES ('$movie_id', '$user_id', '$header', '$text', '$isAnonimous', '$rate')"
            );
            if (!$reviewInsertResult) {
                echo http_response_code(415) . " error to insert data to DataBase\n";
            } else {
                echo http_response_code(201) . " review created\n";
            }
        }
        else
        {
            echo http_response_code(400) . " review already exist\n";
        }
    }