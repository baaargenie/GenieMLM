<?php

Route::get('updateapp', function()
{
    Artisan::call('dump-autoload');
    echo 'dump-autoload complete';
});

Route::get('/', array(
	'as'	=> 'home',
	'uses'	=> 'HomeController@home'
));

Route::get('shop', array(
	'as'	=> 'product',
	'uses'	=> 'HomeController@products'
));

Route::get('product-details', array(
	'as'	=> 'product-details',
	'uses'	=> 'HomeController@productDetails'
));

Route::get('checkout', array(
	'as'	=> 'checkout',
	'uses'	=> 'HomeController@checkOut'
));

Route::get('cart', array(
	'as'	=> 'cart',
	'uses'	=> 'HomeController@cart'
));

Route::get('contact-us', array(
	'as'	=> 'contact-us',
	'uses'	=> 'HomeController@contactUs'
));


/**
 * Unauthenticated group
 */
Route::group(array('before' => 'guest'), function() {
	
	Route::get('login', array(
		'as'	=> 'login',
		'uses'	=> 'HomeController@login'
	));
	
	Route::group(array('before' => 'csrf'), function() {
		Route::post('signup', array(
			'as' 	=> 'signup',
			'uses'	=> 'users@signup'
		));
		
		Route::post('login', array(
			'as'	=> 'login-post',
			'uses'	=> 'users@login'
		));
	});
	
});

/**
 * Authenticated group
 */
Route::group(array('before' => 'auth'), function() {
	Route::get('logout', array(
		'as'	=> 'logout-page',
		'uses'	=> 'users@logout'
	));

	/**
	 * User page
	 */
	Route::get('/user/', array(
		'as'	=> 'user-page',
		'uses'	=> 'UserController@home'
	));
	
	/**
	 * User accout page
	 */
	Route::get('user/account', array(
		'as'	=> 'user-account-page',
		'uses'	=> 'UserController@account'
	));
	
	/**
	 * Ajax request to arrange member
	 */	
	Route::post('/user/accountright', function() {
		
		if (Request::ajax()) {
			$id 	= Input::get('id');
			$right 	= Input::get('right');
			
			$group = User::find($id);
			
			$group->arrange_group = 'right_side';
			
			$group->save();
			
			return "Success";
		}
	});
	
	Route::post('/user/accountleft', function() {
		
		if (Request::ajax()) {
			$id 	= Input::get('id');
			$left 	= Input::get('left');
			
			$group = User::find($id);
			
			$group->arrange_group = 'left_side';
			
			$group->save();
			
			return "Success";
		}
	});
	
	/**
	 * Upload image
	 */
	Route::post('upload', array(
		'as'	=> 'upload-pro-pic',
		'uses'	=> 'ProfileController@upload'
	));
});

/*********************************************************************************
 * Admin panel
 *********************************************************************************
*/
 
// Unauthenicated group
Route::group(array('before' => 'guest'), function() {

	/**
	 * Admin Login get
	 */
	Route::get('/admin/login', array(
		'as'	=> 'admin-login',
		'uses'	=> 'AdminLogin@login_page'
	));
	
	Route::group(array('before' => 'csrf'), function() {
	
		/**
		 * Admin Login post
		 */
		Route::post('/admin/login', array(
			'as'	=> 'admin-login',
			'uses'	=> 'AdminLogin@login'
		));
	});
});
 

// Authenticated group
Route::group(array('before' => 'admin'), function() {
	/**
	 * Admin home page
	 */
	Route::get('/admin/', array(
		'as'	=> 'admin-home',
		'uses'	=> 'AdminLogin@home'
	));
	
	/**
	 * Admin account page
	 */
	Route::get('/admin/account', array(
		'as'	=> 'admin-account',
		'uses'	=> 'AdminLogin@account'
	));
	
	// Change password page
	Route::get('/admin/password', array(
		'as'	=> 'change-password-admin-page',
		'uses'	=> 'AdminLogin@changePasswordPage'
	));
	
	// Change password
	Route::get('/admin/change_password/{id}', array(
		'as'	=> 'change-password-admin',
		'uses'	=> 'AdminLogin@changePassword'
	));
	
	// Add product page
	Route::get('/admin/add-product', array(
		'as'	=> 'add-product-page',
		'uses'	=> 'ProductController@addProductPage'
	));
	
	// Add product catagory page
	Route::get('/admin/catagory', array(
		'as'	=> 'add-catagory-page',
		'uses'	=> 'ProductController@addCatagoryPage'
	));
	
	// Edit catagory page
	Route::get('/admin/edit_catagory/{id}', array(
		'as'	=> 'edit-catagory-page',
		'uses'	=> 'ProductController@editCatagoryPage'
	));
	
	// Delete catagory
	Route::get('/admin/delete_catagory/{id}', array(
		'as'	=> 'delete-catagory',
		'uses'	=> 'ProductController@deleteCatagory'
	));

    // Product details page
    Route::get('/admin/product-details/{id}', array(
        'as'    => 'product-details-page',
        'uses'  => 'ProductController@productDetailsPage'
    ));
	
	// User management
	Route::get('/admin/usermanagement', array('as' => 'user-management-page', 'uses' => 'UserManagement@userManagementPage'));
	Route::get('/admin/user/{id}', array('as' => 'user-view', 'uses' => 'UserManagement@viewUser'));
	Route::get('/admin/user/activate/{id}', array('as' => 'user-active', 'uses' => 'UserManagement@activeUser'));
	Route::get('/admin/user/deactivate/{id}', array('as' => 'user-deactive', 'uses' => 'UserManagement@deactiveUser'));
	
	// Content Management
	Route::get( '/admin/manage-content', array( 'as' => 'manage-content-page', 'uses' => 'ContentManagement@index' ) );
	Route::get( '/admin/add-content', array( 'as' => 'add-content-page', 'uses' => 'ContentManagement@addContent' ) );
	Route::get('admin/edit-content/{id}', array('as' => 'edit-content-page', 'uses' => 'ContentManagement@edit'));
    Route::get('/admin/change-settings', array('as' => 'change-settings-page', 'uses' => 'ContentManagement@settings'));

    // Contact Info
    //Route::get('admin/contact-info', array('as' => 'contact-info-page', 'uses' => 'ContactController@index'));
    Route::controller('admin/contact-info', 'ContactController');

    // Slider
    Route::get('/admin/slider', array('as' => 'slider-page', 'uses' => 'SliderController@index'));
    Route::get('/admin/add-slider', array('as' => 'add-slider-page', 'uses' => 'SliderController@addSliderPage'));
	Route::get('/admin/edit-slider/{id}', array('as' => 'view-slider-page', 'uses' => 'SliderController@edit'));
    Route::get('/admin/getId', function()
    {
        if (Request::ajax())
        {
            $query  = Slider::get();
            foreach ($query as $row)
            {
                $id     = $row->slider_id;
            }
            return $id + 1;
        }
    });
    Route::post('/admin/contentImage', array('as'=>'content-image', 'uses'=>'ContentManagement@upload'));
    Route::post('/admin/edit-content/updateContentImage', array('as'=>'update-content-image', 'uses'=>'ContentManagement@updateUpload'));
	
	Route::group(array('before' => 'csrf'), function() {
		
		// Add product catagory
		Route::post('/admin/addCatagory', array(
			'as'	=> 'add-catagory',
			'uses'	=> 'ProductController@addCatagory'
		));
		
		// Add product
		Route::post('/admin/addProduct', array(
			'as'	=> 'add-product',
			'uses'	=> 'ProductController@addProduct'
		));
		
		// Edit product
		Route::post("/admin/edit-product-details/{id}", array(
			"as"	=> "edit-product",
			"uses"	=> 'ProductController@editProduct'
		));
		
		// Edit catagory
		Route::post('/admin/edit-catagory/{id}', array(
			'as'	=> 'edit-catagory',
			'uses'	=> 'ProductController@editCatagory'
		));

        // Content Management
        Route::post( '/admin/storeContent', array('as' => 'add-content', 'uses' => 'ContentManagement@store') );
        Route::post('/admin/changeSettings', array('as' => 'change-settings', 'uses' => 'ContentManagement@change'));
        Route::post('admin/changeStatus', array('as' => 'change-status', 'uses' => 'ContentManagement@status'));
		Route::post('admin/update-content', array('as' => 'update-content', 'uses' => 'ContentManagement@update'));
		Route::post('admin/delete-content', array('as' => 'delete-content', 'uses' => 'ContentManagement@delete'));
        Route::post('/admin/add-slider-post', array('as' => 'add-slider', 'uses' => 'SliderController@addSlider'));
        Route::post('/admin/slider-status', array('as' => 'slider-status', 'uses' => 'SliderController@changeStatus'));
		Route::post('/admin/update/{id}', array('as' => 'update-slider', 'uses' => 'SliderController@update'));
		Route::post('/admin/delete', array('as' => 'delete-slider', 'uses' => 'SliderController@delete'));
		
	});
	
	// Products page
	Route::get('/admin/shop', array(
		'as'	=> 'products',
		'uses'	=> 'ProductController@products'
	));
	
	// VIew catagory page
	Route::get('/admin/view-catagory', array(
		'as'	=> 'view-catagory-page',
		'uses'	=> 'ProductController@viewCatagory'
	));
	
	// Cart page
	Route::get('/admin/cart', array(
		'as'	=> 'cart-page',
		'uses'	=> 'ProductController@cartPage'
	));
	
	/**
	 * Admin logout page
	 */
	Route::get('/admin/logout', array(
		'as'	=> 'logout-admin',
		'uses'	=> 'AdminLogin@logout'
	));
});


Route::post('signup', 'users@signup');

Route::post('login', 'users@login');
