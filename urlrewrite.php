<?php
$arUrlRewrite = array (
    0 =>
        array(
            "CONDITION" => "#^/news/#",
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
);
