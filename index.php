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
    'log_show' => true,
    'log_file' => './data/phpspider.log',
    'log_type' => 'error,debug,warn,info',
    'input_encoding' => 'utf-8', // 输入编码
    'output_encoding' => 'utf-8', // 输出编码
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
        'http://www.zxcs.me/sort/23',
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
        ],
        [
            'name' => 'level1',
            'select' => '//div[@id=66565]'
        ],
        [
            'name' => 'level2',
            'select' => '//div[@id=66565]'
        ],
        [
            'name' => 'level3',
            'select' => '//div[@id=66565]'
        ],
        [
            'name' => 'level4',
            'select' => '//div[@id=66565]'
        ],
        [
            'name' => 'level5',
            'select' => '//div[@id=66565]'
        ]
    ]

];
$spider = new phpspider($configs);

$spider->on_download_page = function($page, $phpspider) 
{
    $html = $page['raw'];
    $tophtml = explode('<div class="hot_wenzhang" style="display:none">', $html)[0];
    $bottomhtml = explode('<div id="bodybg"></div>', $html)[1];
    $html = $tophtml . $bottomhtml;
    $page['raw'] = $html;
    return $page;
};


$spider->on_content_page = function($page, $content, $phpspider) 
{
    return false;
};

$spider->on_extract_page = function($page, $data)
{
    $url_arr = explode('/', $page['url']);
    $id = $url_arr[4];
    // requests::set_header('cookie', 'security_session_verify=' . requests::get_cookies('www.zxcs.me')['security_session_verify']);
    $vote = requests::get('http://www.zxcs.me/content/plugins/cgz_xinqing/cgz_xinqing_action.php?action=show&id=' . $id);
    echo $vote;
    $vote = explode(',', $vote);
    $data['level1'] = $vote[0];
    $data['level2'] = $vote[1];
    $data['level3'] = $vote[2];
    $data['level4'] = $vote[3];
    $data['level5'] = $vote[4];
    return $data;
};

$spider->start();