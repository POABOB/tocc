<?php
if ( ! defined('PPP')) exit('非法入口');
use core\common\auth;
//DOCS: https://github.com/bramus/router

//統計登入 views&api
$router->get('/login', 'viewsController@login');
$router->post('/login', 'apiController@login');

//middleware 判斷是否登入
$router->before('GET', '/list', function() {
    $cusid = get('cusid') ? get('cusid') : null;
    if('leyan' . $cusid !== $_SESSION['user']) {
        $_SESSION['user'] = false;
        redirect('/login?cusid=' . $cusid);
    }
});

//views
$router->get('/', 'viewsController@index');
$router->get('/customer', 'viewsController@customer');
$router->get('/list', 'viewsController@list');

//api
$router->post('/api/add', 'apiController@add');
$router->get('/api/list', 'apiController@list');

//後台登入 views&api
$router->get('/clinic/login', 'viewsController@clinic_login');
$router->post('/clinic/login', 'apiController@clinic_login');

//middleware 判斷是否登入
$router->before('GET', '/clinic/list', function() {
    //如果$_SESSION沒有user 返回0 else 返回 1
    if(!auth::factory()->user('Permission denied', 1)) {
        $_SESSION['user'] = false;
        redirect('/clinic/login');
    }
});
$router->mount('/clinic', function() use ($router) {
    $router->get('/list', 'viewsController@clinic_list');
});
