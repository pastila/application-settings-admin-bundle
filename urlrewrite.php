<?php
$arUrlRewrite = array (
    0 =>
        array(
            "CONDITION" => "#^/news/([a-zA-Z0-9\\.\\-_]+)/#",
            "RULE" => "",
            "ID" => "bitrix:news",
            "PATH" => "/news/index.php",
        ),
    2 =>
        array (
            'CONDITION' => '#^/feedback/comment-([0-9]*)/#',
            'RULE' => '',
            'PATH' => '/feedback/comment.php',
            'SORT' => 100,
        ),
   3 =>
        array (
            'CONDITION' => '#^/reviews/comment-([0-9]*)/#',
            'RULE' => '',
            'PATH' => '/feedback/comment.php',
            'SORT' => 100,
        ),
);
