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