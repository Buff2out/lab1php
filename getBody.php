<?php
    function getBody($method)
    {
        $data = new stdClass();
        if ($method != "GET")
        {
            #echo "POSTMETHOD\n";
            $data->body = json_decode(file_get_contents("php://input"));
        }
        $data->parameteres = [];
        $dataGet = $_GET;
        foreach ($dataGet as $key => $value)
        {
            if ($key != 'q')
            {
                $data->parameteres[$key] = $value;
            }
        }
        return $data;
    }