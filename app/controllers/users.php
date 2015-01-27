<?php

class Users extends BaseController {
	
	/**
	 * Singup
	 */
	public function signup()
	{
		$validator = Validator::make(Input::all(),
			array(
				'name'				=> 'required',
				'email'				=> 'required|email|unique:users',
				'password'			=> 'required',
				'confirm_password'	=> 'required|same:password',
				'date'				=> 'required',
				'month'				=> 'required',
				'year'				=> 'required',
                'permanent_address' => 'required',
				'present_address'	=> 'required',
				'referal_id'		=> 'required',
                'side'              => 'required'
			)
		);
		
		if ($validator->fails()) {
			return Redirect::route('login')
							->withErrors($validator)
							->withInput();
		} else {
            $name                   = Input::get('name');
            $email                  = Input::get('email');
            $password               = Input::get('password');
			$gender                 = Input::get('gender');
			$date                   = Input::get('date');
			$year                   = Input::get('year');
			$month                  = Input::get('month');
            $permanent_address      = Input::get("permanent_address");
			$present_address  		= Input::get('present_address');
			$referal_id             = Input::get('referal_id');
            $side                   = Input::get("side");
			
			// Activation code
			$code 			= md5(uniqid(str_random(60)));
			
			$query = User::where('id', '=', $referal_id)->where('active', '=', 1);
			
			if ($query->count()) {
				$create = User::create(array(
					'name'			 => $name,
					'email'			 => $email,
					'password'		 => Hash::make($password),
					'gender'		 => $gender,
					'date_of_birth'	 => $date,
					'month_of_birth' => $month,
					'year_of_birth'	 => $year,
                    'permanent_address' => $permanent_address,
					'present_address'	=> $present_address,
					'referal_id'	 => $referal_id,
					'type'			 => 'member',
					'token'			 => $code,
					'designation'	 => 'Not an active member',
					'active'		 => 1,
					'arrange_group'  => $side
				));
				
				if ($create) {
					return Redirect::route('login')
								->with('signup_success', 'Your account has been created successfully! We have sent you an email to activate your account. Please LOG IN');
				} else {
					return Redirect::route('login')
									->with('singup_error', 'Error occured. Please try after sometimes');
				}
			} else {
				return Redirect::route('login')
								->with('referal_error', 'Referal ID ' . $referal_id . ' is not found. Please use valid referal ID.');
			}
		}
	}
	
	/**
	 * Login page
	 */
	public function login()
	{
		$validator = Validator::make(Input::all(),
			array(
				'login_email'	 => 'required|email',
				'login_password' => 'required'
			)
		);
		
		if ($validator->fails()) {
			return Redirect::to('login')
					->withErrors($validator)
					->withInput();
		} else {
			$auth = Auth::attempt(array(
				'email'		=> Input::get('login_email'),
				'password'	=> Input::get('login_password'),
				'active'	=> 1
			));
			
			if ($auth) {
				return Redirect::intended();
			} else {
				return Redirect::route('login')
								->with('login_failed', 'Email/Password wrong, or account not acctivated.');
			}
		}
		
		return Redirect::route('login')
						->with('login_failed', 'There was a problem signin you in.');
	}
    
    // Find refer
    public function findRefer()
    {
        $id = Input::get("referal_id");
        
        $user = User::where("id", "=", $id);
        $full = User::where("referal_id", "=", $id);
        
        if($user->count() != 1)
        {
            ?>
                <div class="alert alert-danger" id="user_error">
                    User not found. Please try another user
                </div>
            <?php
        }
        else if($full->count() == 2)
        {
            return "<div class='alert alert-danger' id='user_full'>This user is full. Please try another user</div>";
        }
        else if($full->count() == 1)
        {
            $left = User::where("referal_id", "=", $id)->where("arrange_group", "=", "left_side");
            
            if($left->count() == 1)
            {
                ?>
                    <?= Form::label("side", "Select Hand"); ?>
                    <select name="side">
                        <option value="right_side">Right hand side</option>
                    </select>
                <?php
            }
            else
            {
                $right = User::where("referal_id", "=", $id)->where("arrange_group", "=", "right_side");
                
                if($right->count() == 1)
                {
                    ?>
                        <?= Form::label("side", "Select Hand"); ?>
                        <select name="side">
                            <option value="left_side">Left hand side</option>
                        </select>
                    <?php
                }
            }
        }
        else
        {
            ?>
                <?= Form::label("side", "Select Hand"); ?>
                <select name="side">
                    <option value="left_side">Left hand side</option>
                    <option value="right_side">Right hand side</option>
                </select>
            <?php
        }
    }

	/**
	 * Logout page
	 */
	public function logout()
	{
		Auth::logout();
		
		return Redirect::route('login')
						->with('logout', 'You are loged out!');
		
	}
}
