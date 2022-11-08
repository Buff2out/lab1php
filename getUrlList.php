<?php
    function getUrlList()
    {
        $url = isset($_GET['q']) ? $_GET['q'] : '';
        $url = rtrim($url, '/');
        $urlList = explode('/', $url);
        return $urlList;
    }