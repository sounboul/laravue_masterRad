<?php 

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Laravue\Faker;
use \App\Laravue\JsonResponse;
use \App\Laravue\Acl;

Route::namespace('Api')->group(function() {
    Route::post('auth/login', 'AuthController@login');
    Route::get('v1/get_customer_level/{id}', 'BexterController@get_customer_level');
    Route::get('v1/update_list', 'BexterController@customers');
    Route::post('v1/place_order', 'BexterController@place_order');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        // Auth routes
        Route::get('auth/user', 'AuthController@user');
        Route::post('auth/logout', 'AuthController@logout');

        Route::get('/user', function (Request $request) {
            return new UserResource($request->user());
        });

        // Api resource routes
        Route::apiResource('roles', 'RoleController')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        Route::apiResource('users', 'UserController')->middleware('permission:' . Acl::PERMISSION_USER_MANAGE);
        Route::apiResource('permissions', 'PermissionController')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        Route::apiResource('customers', 'CustomersController')->middleware('permission:' . Acl::PERMISSION_CUSTOMER_MANAGE);
        Route::apiResource('discounts', 'MemberLevelController')->middleware('permission:' . Acl::PERMISSION_DISCOUNT_MANAGE);

        // Custom routes
        Route::put('users/{user}', 'UserController@update');
        Route::get('users/{user}/permissions', 'UserController@permissions')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        Route::put('users/{user}/permissions', 'UserController@updatePermissions')->middleware('permission:' .Acl::PERMISSION_PERMISSION_MANAGE);
        Route::get('roles/{role}/permissions', 'RoleController@permissions')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        Route::get('customers/{customer}/permissions', 'CustomersController@permissions')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        Route::get('categories/{category}/permissions', 'CategoriesController@permissions')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        Route::get('suppliers/{supplier}/permissions', 'SuppliersController@permissions')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        Route::get('discounts/{discount}/permissions', 'MemberLevelController@permissions')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);

        // Articles
        Route::get('/article', 'ArticlesController@index');
        Route::get('/articles', 'ArticlesController@fetchArticles');
        Route::get('/articles_selling', 'ArticlesController@fetchArticles1');
        Route::get('/article/preview/{id}', 'ArticlesController@previewArticle');
        Route::post('/article/create', 'ArticlesController@createArticle');
        Route::post('/article/update', 'ArticlesController@update');
        Route::post('/articleUpdate1', 'ArticlesController@articleUpdate1');
        //      
        Route::get('/article/delete/{id}', 'ArticlesController@deleteArticle');

        // Customers
        Route::get('/test', 'CustomersController@test');
        Route::get('/customers', 'CustomersController@fetchCustomers');
        Route::put('/customers/{id?}', 'CustomersController@update');
        Route::get('/customer/preview/{id}', 'CustomersController@fetchCustomer');
        Route::get('/all_customers', 'CustomersController@fetchAllCustomers');
        Route::post('/customer/create', 'CustomersController@createCustomer');
        Route::post('/customerUpdate1', 'CustomersController@customerUpdate1');
        Route::get('/customer/delete/{id}', 'CustomersController@deleteCustomer');

        // Stores
        Route::get('/stores', 'StoresController@fetchStores');

        // Dashboard
        Route::get('/getDates', 'SellTicoController@getDates');
        Route::get('/getValue', 'SellTicoController@chart_values');
        

        // Categories
        Route::get('/categories', 'CategoriesController@fetchCategories');
        Route::post('/categories/update', 'CategoriesController@updateCategory');
        Route::post('/categories/create', 'CategoriesController@createCategory');
        Route::get('/categories/delete/{id}', 'CategoriesController@deleteCategory');
        Route::get('/getCategories', 'CategoriesController@getCategories');

        // MailChimp
        Route::get('/mailchimp', 'MailChimpController@index');
        Route::post('/mailchimp/create', 'MailChimpController@create');
        Route::post('/mailchimp/update', 'MailChimpController@update');
        Route::get('/mailchimp/delete/{email}', 'MailChimpController@delete');
        Route::post('/mailchimp/tags', 'MailChimpController@tags');

        // Suppliers
        Route::get('/suppliers', 'SuppliersController@index');   
        Route::get('/all_suppliers', 'SuppliersController@getSuppliers');   
        Route::post('/suppliers/update', 'SuppliersController@updateSupplier');
        Route::post('/suppliers/create', 'SuppliersController@createSupplier');
        Route::get('/suppliers/delete/{id}', 'SuppliersController@deleteSupplier');


        // Departments
        Route::get('/departments', 'DepartmentsController@fetchDepartments');

        // Discounts
        Route::get('/members', 'MemberLevelController@index');
        Route::get('/fetchLevels', 'MemberLevelController@fetchLevels');
        Route::get('/get_points', 'MemberLevelController@get_points');
        Route::get('/updateLevel', 'MemberLevelController@updateLevel');
        Route::post('/discount/update', 'MemberLevelController@update');
        Route::post('/discount/updateValue', 'MemberLevelController@updateValue');
        Route::post('/discount/updatePoint', 'MemberLevelController@updatePoint');
        Route::post('/discount/updateLimit', 'MemberLevelController@updateLimit');

        // Bills
        Route::post('/listItem1', 'BillController@listItem');
        Route::post('/executeQuery', 'BillController@executeQuery');


        //Route::get('/articlesTico','SellTicoController@articles');
        Route::get('/articlesTico','ArticlesController@articles');
        Route::get('/customersTico','SellTicoController@customers');
        Route::get('/fetchListTico','SellTicoController@fetchListTico');
        Route::get('/marketing_tico/{category_id}','SellTicoController@marketing_tico');
        Route::post('/APIcredentials','SellTicoController@APIcredentials');

        // E-mail
        Route::post('/send_mail', 'MailController@send_mail');
        Route::post('/send_sms', 'MailController@send_sms');

        //Validate mail
        Route::get('/send_mail/{email}', 'Validate_emailController@send_mail');

    });
});
