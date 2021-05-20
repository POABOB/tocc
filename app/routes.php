<?php
if ( ! defined('PPP')) exit('非法入口');
use core\common\auth;
//DOCS: https://github.com/bramus/router

//views
$router->get('/', 'viewsController@index');
$router->get('/customer', 'viewsController@customer');
$router->get('/list', 'viewsController@list');

//api
$router->post('/api/add', 'apiController@add');
$router->get('/api/list', 'apiController@list');


//登入 views&api
$router->post('/login', 'apiController@login');
$router->get('/login', 'viewsController@login');


//middleware 判斷是否登入
$router->before('GET', '/clinic.*', function() {
    //如果$_SESSION沒有user 返回0 else 返回 1
    if(!auth::factory()->user('Permission denied', 1)) {
        redirect('/login');
    }
});
$router->mount('/clinic', function() use ($router) {
    $router->get('/list', 'viewsController@clinic_list');
});
