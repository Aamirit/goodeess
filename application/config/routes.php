<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/



// Admin routes start
$route['default_controller'] = 'admin/dashboard';
$route['admin/offers'] = 'admin/Offers/offers';
$route['login'] = 'admin/Auth/loginView';
$route['admin/campaigns'] = 'admin/Campaigns/campaigns';
$route['admin/users'] = 'admin/Users/users';
$route['admin/add/user'] = 'admin/Users/create_user';
$route['admin/user/delete'] = 'admin/Users/delete_user';
$route['admin/user/getedit'] = 'admin/Users/getedit';
$route['admin/users/edituser'] = 'admin/Users/editUser';
$route['admin/logout'] = 'admin/auth/logout';
$route['admin/orders'] = 'admin/Orders';
$route['orders/list'] = 'admin/Orders/orders_list';
// Admin routes end

// Frontend routes start
$route['checkout/(:any)'] = 'home/checkout/$1';
$route['checkout2/(:any)'] = 'home/checkout2/$1';
$route['thankyou/(:any)'] = 'home/thankyou/$1';
// Frontend routes end

$route['product/(:any)'] = 'home/product/$1';
$route['free/product/(:any)'] = 'home/free_product/$1';
$route['free/checkout/(:any)'] = 'home/free_checkout/$1';
$route['free/thankyou/(:any)'] = 'home/free_thankyou/$1';
$route['apply/discount'] = 'home/discount_price';
$route['soldout/product/(:any)'] = 'home/soldout_product/$1';
$route['get/notification'] = 'home/get_notification';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
