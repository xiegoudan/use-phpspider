<?php

require './vendor/autoload.php';

use phpspider\core\phpspider;
use phpspider\core\requests;


/* Do NOT delete this comment */
/* 不要删除这段注释 */

// 知轩藏书 都市*娱乐 评价抓取
$url = 'http://www.zxcs.me/sort/23';
$voteurl = 'http://www.zxcs.me/content/plugins/cgz_xinqing/cgz_xinqing_action.php?action=show?id=';
$vote = [];
$configs = [
    'name' => '知轩藏书',
    'log_show' => false,
    'input_encoding' => 'utf-8', // 输入编码
    'output_encoding' => 'utf-8', // 输出编码
    'max_depth' => 1, // 采集深度
    'export' => [
        'type' => 'csv',
        'file' => './data/zxcs.csv'
    ],
    'user_agent' => phpspider::AGENT_ANDROID,
    'domains' => [
        'zxcs.me',
        'www.zxcs.me'
    ],
    'scan_urls' => [
        'http://www.zxcs.me/sort/23/page/1',
    ],
    'content_url_regexes' => [
        "http://www.zxcs.me/post/\d+"
    ],
    'list_url_regexes' => [
        "http://www.zxcs.me/sort/23/page/\d+"
    ],
    'fields' => [
        [
            'name' => 'book_name',
            'selector' => "//div[@id='m']/div[@class='posttitle']"
        ]
    ]

];
$spider = new phpspider($configs);
$spider->on_content_page = function($page, $content, $phpspider)
{
    
    return false;
};

$spider->on_extract_field = function($filedname, $data, $page)
{
    $url_arr = explode('/', $page['url']);
    $id = $url_arr[4];
    requests::set_header('cookie', 'security_session_verify=' . requests::get_cookies('www.zxcs.me')['security_session_verify']);
    $vote = requests::get('http://www.zxcs.me/content/plugins/cgz_xinqing/cgz_xinqing_action.php?action=show&id=' . $id);
    echo $vote;
    $data = $data . ',' . $vote;
    return $data;
};

$spider->start();