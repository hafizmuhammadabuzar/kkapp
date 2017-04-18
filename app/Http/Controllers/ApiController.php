<?php

namespace App\Http\Controllers;

use App\Country;
use App\Event;
use App\User;
use Carbon\Carbon;

use DB;

use Illuminate\Http\Request;
use Session;

use Validator;

class ApiController extends Controller {
	public $current_date_time;
	public $save_success;
	public $save_error;
	public $update_success;
	public $update_error;

	public function __construct() {

		//DB::enableQueryLog();
		$this->current_date_time = Carbon::now('Asia/Dubai');

		$this->save_success   = 'Successfully saved';
		$this->save_error     = 'Could not be saved';
		$this->update_success = 'Successfully updated';
		$this->update_error   = 'Could not be updated';
	}

	public function getCountriesCities() {

		$countries = Country::with('cities')->orderBy(DB::raw('country_name'))->get();

		return response()->json($countries);
	}

	public function getCategories() {

		$cities             = DB::table('cities')->select('city_name', 'latitude', 'longitude')->where('country_id', '=', 11)->orderBy('city_name', 'ASC')->get();
		$arabic_categories  = DB::table('categories')->select('id', 'arabic', 'selected_icon', 'non_selected_icon')->orderBy('arabic')->get();
		$english_categories = DB::table('categories')->select('id', 'english', 'selected_icon', 'non_selected_icon')->orderBy('english')->get();
		$languages          = DB::table('languages')->select('id', 'title')->orderBy('title')->get();

		foreach ($arabic_categories as $key => $cat) {
			$categories['arabic'][$key]['id']                = $cat->id;
			$categories['arabic'][$key]['title']             = $cat->arabic;
			$categories['arabic'][$key]['non_selected_icon'] = $cat->non_selected_icon;
			$categories['arabic'][$key]['selected_icon']     = $cat->selected_icon;

			$categories['english'][$key]['id']                = $english_categories[$key]->id;
			$categories['english'][$key]['title']             = $english_categories[$key]->english;
			$categories['english'][$key]['non_selected_icon'] = $english_categories[$key]->non_selected_icon;
			$categories['english'][$key]['selected_icon']     = $english_categories[$key]->selected_icon;
		}

		$arabic_types  = DB::table('types')->select('id', 'arabic')->orderBy('arabic')->get();
		$english_types = DB::table('types')->select('id', 'english')->orderBy('english')->get();

		foreach ($arabic_types as $key => $type) {
			$types['arabic'][$key]['id']    = $type->id;
			$types['arabic'][$key]['title'] = $type->arabic;

			$types['english'][$key]['id']    = $english_types[$key]->id;
			$types['english'][$key]['title'] = $english_types[$key]->english;
		}

		$result['status']     = 'Success';
		$result['msg']        = 'All Preferred';
		$result['cities']     = $cities;
		$result['types']      = $types;
		$result['categories'] = $categories;
		$result['languages']  = $languages;
		$result['icon_url']   = url('public/admin/icons');

		return response()->json($result);
	}

	public function getTypes() {

		$arabic_types  = DB::table('types')->select('id', 'arabic')->orderBy('arabic')->get();
		$english_types = DB::table('types')->select('id', 'english')->orderBy('english')->get();

		foreach ($arabic_types as $key => $type) {
			$types['arabic'][$key]['id']    = $type->id;
			$types['arabic'][$key]['title'] = $type->arabic;

			$types['english'][$key]['id']    = $english_types[$key]->id;
			$types['english'][$key]['title'] = $english_types[$key]->english;
		}

		$result['status']     = 'Success';
		$result['msg']        = 'All Preferred';
		$result['categories'] = $types;
		$result['languages']  = $languages;
		$result['icon_url']   = url('public/admin/icons');

		return response()->json($result);
	}

	public function addEvent(Request $request) {

		$check = DB::table('users')->where('email', '=', $request->user_email)->first();

		if (!$check) {
			$result['status'] = 'Error';
			$result['msg']    = 'User not found';
			$result['data']   = $request->all();

			return response()->json($result);
			exit;
		}

		$reference_no = uniqid();

		$event_data = [
			'reference_no'     => $reference_no,
			'type_id'          => $request->types[0],
			'category_id'      => $request->categories[0],
			'keyword'          => $request->keywords,
			'eng_name'         => $request->event_name,
			'eng_company_name' => $request->company_name,
			'phone'            => $request->phone,
			'email'            => $request->email,
			'weblink'          => $request->website,
			'start_date'       => $request->start_date_time,
			'end_date'         => $request->end_date_time,
			'all_day'          => $request->all_day,
			'free_event'       => $request->free_event,
			'facebook'         => $request->facebook,
			'twitter'          => $request->twitter,
			'instagram'        => $request->instagram,
			'event_language'   => implode(',', $request->languages),
			'eng_description'  => $request->description,
			'venue'            => $request->venue,
			'is_kids'          => $request->kids_event,
			'is_disabled'      => $request->disable_event,
			'shared_count'     => 1,
			'user_id'          => $check->id,
			'created_at'       => $this->current_date_time,
			'updated_at'       => $this->current_date_time,
		];

		$id = DB::table('events')->insertGetId($event_data);
		if ($id) {

			$destinationPath = base_path().'/public/uploads';

			// Pictures and attachments upload
			for ($i = 1; $i < 5; $i++) {

				if ($request->hasFile('picture_'.$i)) {
					$file          = $request->file('picture_'.$i);
					$extension     = $file->getClientOriginalExtension();
					$picture[$i-1] = uniqid().'.'.$extension;
					$file->move($destinationPath, $picture[$i-1]);

					$this->create_watermark($destinationPath.'/'.$picture[$i-1], $destinationPath.'/'.$picture[$i-1]);
					$pic_data[] = ['event_id' => $id, 'picture' => $picture[$i-1]];

				}

				if ($request->hasFile('attachment_'.$i)) {
					$file             = $request->file('attachment_'.$i);
					$extension        = $file->getClientOriginalExtension();
					$attachment[$i-1] = uniqid().'.'.$extension;
					$file->move($destinationPath, $attachment[$i-1]);

					$this->create_watermark($destinationPath.'/'.$attachment[$i-1], $destinationPath.'/'.$attachment[$i-1]);
					$attch_data[] = ['event_id' => $id, 'picture' => $attachment[$i-1]];

				}
			}

			if (isset($pic_data)) {
				DB::table('pictures')->insert($pic_data);
			}
			if (isset($attch_data)) {
				DB::table('attachments')->insert($attch_data);
			}

			DB::table('locations')->where('event_id', $id)->delete();

			for ($loc = 0; $loc < count($request->locations); $loc++) {
				$latlng     = explode(',', $request->locations[$loc]['latlng']);
				$loc_data[] = [
					'event_id'  => $id,
					'city'      => $request->locations[$loc]['city'],
					'location'  => $request->locations[$loc]['location'],
					'latitude'  => $latlng[0],
					'longitude' => $latlng[1]
				];
			}
			DB::table('locations')->insert($loc_data);

			$result['status'] = 'Success';
			$result['msg']    = $this->save_success;
		} else {
			$result['status'] = 'Error';
			$result['msg']    = $this->save_error;
		}
		return response()->json($result);
	}

	public function getEvents(Request $request) {

		$offset = ($request->offset > 0)?$request->offset:0;

		if (!empty($request->radius)) {
			$events = Event::getEventsByRadius($request->latLng, $request->radius, $request->type);
		} else {
			$events = Event::getEvents($request->city, $request->type, $offset, false);
		}
		$featured_events = Event::getEvents($request->city, $request->type, 0, true);
		$likes           = Event::getMostLike();
		$shared          = Event::getMostShared();

		if (count($events) > 0 || count($featured_events) > 0) {
			if (!empty($request->user_id)) {
				$user_favourites = DB::table('user_favourite_events')->where('user_id', $request->user_id)->get();
				foreach ($user_favourites as $key => $fav) {
					$user_favs[] = $fav->event_id;
				}
			}

			foreach ($events as $key => $event) {
				$ids      = $event->event_language;
				$lang     = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
				$type     = DB::table('types')->where('id', $event->type_id)->first();
				$category = DB::table('categories')->where('id', $event->category_id)->first();
				if ($event->user_id != 0) {
					$user                       = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();
					$events[$key]->username     = $user->username;
					$events[$key]->user_email   = $user->email;
					$events[$key]->is_verified  = $user->is_verified;
					$events[$key]->user_picture = $user->image;
				} else {
					$events[$key]->username     = '';
					$events[$key]->user_email   = '';
					$events[$key]->is_verfied   = '';
					$events[$key]->user_picture = '';
				}

				if (isset($user_favs)) {
					$events[$key]->is_favourite = (in_array($event->id, $user_favs))?1:0;
				} else {
					$events[$key]->is_favourite = 0;
				}

				$events[$key]->type_english     = $type->english;
				$events[$key]->type_arabic      = $type->arabic;
				$events[$key]->category_english = $category->english;
				$events[$key]->category_arabic  = $category->arabic;
				$events[$key]->languages        = $lang;
			}

			foreach ($featured_events as $key => $event) {
				$ids      = $event->event_language;
				$lang     = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
				$type     = DB::table('types')->where('id', $event->type_id)->first();
				$category = DB::table('categories')->where('id', $event->category_id)->first();

				if ($event->user_id != 0) {
					$user                                = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();
					$featured_events[$key]->username     = $user->username;
					$featured_events[$key]->user_email   = $user->email;
					$featured_events[$key]->is_verified  = $user->is_verified;
					$featured_events[$key]->user_picture = $user->image;
				} else {

					$featured_events[$key]->username     = '';
					$featured_events[$key]->user_email   = '';
					$featured_events[$key]->is_verfied   = '';
					$featured_events[$key]->user_picture = '';
				}

				if (isset($user_favs)) {
					$featured_events[$key]->is_favourite = (in_array($event->id, $user_favs))?1:0;
				} else {
					$featured_events[$key]->is_favourite = 0;
				}

				$featured_events[$key]->type_english     = $type->english;
				$featured_events[$key]->type_arabic      = $type->arabic;
				$featured_events[$key]->category_english = $category->english;
				$featured_events[$key]->category_arabic  = $category->arabic;
				$featured_events[$key]->languages        = $lang;
			}

			if (count($likes) > 0) {

				foreach ($likes as $key => $event) {
					$ids      = $event->event_language;
					$lang     = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
					$type     = DB::table('types')->where('id', $event->type_id)->first();
					$category = DB::table('categories')->where('id', $event->category_id)->first();

					if ($event->user_id != 0) {
						$user                      = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();
						$likes[$key]->username     = $user->username;
						$likes[$key]->user_email   = $user->email;
						$likes[$key]->is_verified  = $user->is_verified;
						$likes[$key]->user_picture = $user->image;
					} else {
						$likes[$key]->username     = '';
						$likes[$key]->user_email   = '';
						$likes[$key]->is_verfied   = '';
						$likes[$key]->user_picture = '';
					}

					if (isset($user_favs)) {
						$likes[$key]->is_favourite = (in_array($event->id, $user_favs))?1:0;
					} else {
						$likes[$key]->is_favourite = 0;
					}
					$likes[$key]->type_english     = $type->english;
					$likes[$key]->type_arabic      = $type->arabic;
					$likes[$key]->category_english = $category->english;
					$likes[$key]->category_arabic  = $category->arabic;
					$likes[$key]->languages        = $lang;
				}
			}

			if (count($shared) > 0) {
				foreach ($shared as $key => $event) {
					$ids      = $event->event_language;
					$lang     = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
					$type     = DB::table('types')->where('id', $event->type_id)->first();
					$category = DB::table('categories')->where('id', $event->category_id)->first();
					if ($event->user_id != 0) {
						$user = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();

						$shared[$key]->username     = $user->username;
						$shared[$key]->user_email   = $user->email;
						$shared[$key]->is_verified  = $user->is_verified;
						$shared[$key]->user_picture = $user->image;
					} else {

						$shared[$key]->username     = '';
						$shared[$key]->user_email   = '';
						$shared[$key]->is_verfied   = '';
						$shared[$key]->user_picture = '';
					}

					if (isset($user_favs)) {
						$shared[$key]->is_favourite = (in_array($event->id, $user_favs))?1:0;
					} else {
						$shared[$key]->is_favourite = 0;
					}

					$shared[$key]->type_english     = $type->english;
					$shared[$key]->type_arabic      = $type->arabic;
					$shared[$key]->category_english = $category->english;
					$shared[$key]->category_arabic  = $category->arabic;
					$shared[$key]->languages        = $lang;
				}
			}

			$result['status']          = 'Success';
			$result['msg']             = 'Events';
			$result['events']          = $events;
			$result['featured_events'] = $featured_events;
			$result['most_liked']      = $likes;
			$result['most_shared']     = $shared;
		} else {
			if ($offset > 0) {
				$result['status'] = 'Error';
				$result['msg']    = 'No more events';
			} else {
				$result['status'] = 'Error';
				$result['msg']    = 'No event found';
			}
		}

		return response()->json($result);
	}

	public function getUserFavouriteEvents(Request $request) {

		$validator = Validator::make($request->all(), ['user_id' => 'required']);

		if ($validator->fails()) {
			$result['status'] = 'Error';
			$result['msg']    = 'Required fields must not be empty';
			$result['error']  = $validator->errors();

			return response()->json($result);
		}

		$events = Event::getFavouriteEvents($request->user_id);
		// return response()->json($events);

		if (count($events) > 0) {

			foreach ($events as $key => $event) {
				$ids      = $event->event_language;
				$lang     = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
				$type     = DB::table('types')->where('id', $event->type_id)->first();
				$category = DB::table('categories')->where('id', $event->category_id)->first();
				if ($event->user_id != 0) {
					$user                       = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();
					$events[$key]->username     = $user->username;
					$events[$key]->user_email   = $user->email;
					$events[$key]->is_verified  = $user->is_verified;
					$events[$key]->user_picture = $user->image;
				} else {
					$events[$key]->username     = '';
					$events[$key]->user_email   = '';
					$events[$key]->is_verfied   = '';
					$events[$key]->user_picture = '';
				}

				$events[$key]->is_favourite     = 1;
				$events[$key]->type_english     = $type->english;
				$events[$key]->type_arabic      = $type->arabic;
				$events[$key]->category_english = $category->english;
				$events[$key]->category_arabic  = $category->arabic;
				$events[$key]->languages        = $lang;
			}

			$result['status'] = 'Success';
			$result['msg']    = 'Events';
			$result['events'] = $events;
		} else {
			$result['status'] = 'Error';
			$result['msg']    = 'No event found';
		}

		return response()->json($result);
	}

	public function saveFavourite(Request $request) {

		$validation_data = [
			'user_id'  => 'required',
			'event_id' => 'required',
		];

		$validator = Validator::make($request->all(), $validation_data);

		if ($validator->fails()) {
			$result['status'] = 'Error';
			$result['msg']    = 'Required fields must not be empty';
			$result['error']  = $validator->errors();

			return response()->json($result);
		}

		$user_id = $request->user_id;
		$check   = DB::table('users')->where('id', '=', $user_id)->first();
		if (!$check) {
			$result['status'] = 'Error';
			$result['msg']    = 'User not found';

			return response()->json($result);
		}

		$data = ['user_id' => $user_id, 'event_id' => $request->event_id, 'created_at' => $this->current_date_time];

		if ($request->is_favourite == 1) {
			$res = DB::table('user_favourite_events')
				->where('user_id', '=', $user_id)
				->where('event_id', '=', $request->event_id)
				->delete();

			if ($res == 1) {
				$result['status'] = 'Success';
				$result['msg']    = 'Succcessfully deleted';
			} else {
				$result['status'] = 'Error';
				$result['msg']    = 'Could not be deleted';
			}
		} else {
			$like_check = DB::table('user_favourite_events')->where('user_id', '=', $user_id)->where('event_id', '=', $request->event_id)->first();
			if (!$like_check) {
				$res = DB::table('user_favourite_events')->insertGetId($data);

				if ($res > 0) {
					$result['status'] = 'Success';
					$result['msg']    = $this->save_success;
				} else {
					$result['status'] = 'Error';
					$result['msg']    = $this->save_error;
				}
			} else {
				$result['status'] = 'Success';
				$result['msg']    = $this->save_success;
			}
		}

		return response()->json($result);
	}

	public function eventShare(Request $request) {

		$validation_data = [
			'event_id'    => 'required',
			'share_count' => 'required',
		];

		$validator = Validator::make($request->all(), $validation_data);

		if ($validator->fails()) {
			$result['status'] = 'Error';
			$result['msg']    = 'Required fields must not be empty';
			$result['error']  = $validator->errors();

			return response()->json($result);
		}

		$res = DB::table('events')->where('id', '=', $request->event_id)->update(['share_count' => $request->share_count+1]);

		if ($res == 1) {
			$result['status'] = 'Success';
			$result['msg']    = $this->update_success;
		} else {
			$result['status'] = 'Error';
			$result['msg']    = $this->update_error;
		}

		return response()->json($result);
	}

	function create_watermark($source_file_path, $output_file_path) {
		list($source_width, $source_height, $source_type) = getimagesize($source_file_path);
		if ($source_type === NULL) {
			return false;
		}
		switch ($source_type) {
			case IMAGETYPE_GIF:
				$source_gd_image = imagecreatefromgif($source_file_path);
				break;
			case IMAGETYPE_JPEG:
				$source_gd_image = imagecreatefromjpeg($source_file_path);
				break;
			case IMAGETYPE_PNG:
				$source_gd_image = imagecreatefrompng($source_file_path);
				break;
			default:
				return false;
		}
		$overlay_gd_image = imagecreatefrompng(base_path().'/public/wt.png');
		$overlay_width    = imagesx($overlay_gd_image);
		$overlay_height   = imagesy($overlay_gd_image);
		imagecopymerge(
			$source_gd_image,
			$overlay_gd_image,
			$source_width-$overlay_width,
			$source_height-$overlay_height,
			0,
			0,
			$overlay_width,
			$overlay_height,
			10
		);
		imagejpeg($source_gd_image, $output_file_path, 90);
		imagedestroy($source_gd_image);
		imagedestroy($overlay_gd_image);
	}

	public function register(Request $request) {

		/* Form post data for register */
		$validation_data = [
			'username' => 'required',
		];

		/* check is social login or from app sign up form */
		if ($request->is_social == 0 || $request->is_social == '') {
			$password_validate = [
				'gender'   => 'required',
				'dob'      => 'required',
				'password' => 'required',
			];
			// 'email'    => 'required|email|max:100'
			$validation_data = array_merge($password_validate, $validation_data);

			$social = 0;
			$status = 1;
		} else {
			$email_validate  = ['email' => 'required|email|max:100'];
			$validation_data = array_merge($email_validate, $validation_data);

			$social = 1;
			$status = 0;
		}

		$validator = Validator::make($request->all(), $validation_data);

		if ($validator->fails()) {
			$result['status'] = 'Error';
			$result['msg']    = 'Required fields must not be empty';
			$result['error']  = $validator->errors();

			return response()->json($result);
		}

		$email = trim($request->input('email'));

		/* check email already exists or not */

		if ($social == 0) {
			$check = DB::table('users')->where('email', '=', $email)->where('is_social', '=', $social)->first();
			if ($check) {
				$result['status'] = 'Error';
				$result['msg']    = 'Email already exists';

				return response()->json($result);
			}
		} else if ($social == 1) {
			$check = DB::table('users')->where('email', '=', $email)->where('is_social', '=', $social)->first();
			if ($check) {
				$user             = User::getUserDetails($check->id);
				$user->categories = User::getUserCategories($user->id);
				$user->languages  = DB::table('user_languages')
				     ->select('languages.id', 'title')
				     ->join('languages', 'user_languages.language_id', '=', 'languages.id')
				     ->where('user_id', '=', $check->id)
					->get();
				$user->image_path = url('public/uploads');

				$result['status']  = 'Success';
				$result['msg']     = $this->save_success;
				$result['profile'] = $user;

				return response()->json($result);
			}
		}

		$username       = trim($request->input('username'));
		$password       = trim($request->input('password'));
		$gender         = trim($request->input('gender'));
		$dob            = trim($request->input('dob'));
		$remember_token = uniqid();

		if ($request->hasFile('picture')) {
			$file            = $request->file('picture');
			$extension       = $file->getClientOriginalExtension();
			$picture         = uniqid().'.'.$extension;
			$destinationPath = base_path().'/public/uploads';
			$file->move($destinationPath, $picture);

			$result = $this->create_watermark($destinationPath.'/'.$picture, $destinationPath.'/'.$picture);

		} else {
			$picture = 'default.png';
		}

		$user_data = [
			'username'       => $username,
			'email'          => $email,
			'password'       => $password,
			'gender'         => $gender,
			'dob'            => $dob,
			'image'          => $picture,
			'remember_token' => $remember_token,
			'status'         => $status,
			'is_social'      => $social,
			'created_at'     => $this->current_date_time,
			'updated_at'     => $this->current_date_time,
		];
		$id = DB::table('users')->insertGetId($user_data);

		if ($id) {
			if ($social == 0) {
				$msg = "Dear ".ucwords($username).",<br/><br/>Please click the given link to activate your account<br/>
                <a href='".url('/user/verification/'.$remember_token)."'>Click Here</a>";
				$send = $this->send_email($email, 'KK Events', 'Account Activation', $msg);
			}

			if (!empty($request->token)) {
				if (!empty($request->device_id)) {
					$this-saveAndroidToken($request->token, $request->device_id);
				} else {
					$this->saveToken($request->token);
				}
			}

			$result['status'] = 'Success';
			$result['msg']    = $this->save_success;
			$user             = User::getUserDetails($id);
			$user->categories = User::getUserCategories($user->id);
			$user->languages  = DB::table('user_languages')
			     ->select('languages.id', 'title')
			     ->join('languages', 'user_languages.language_id', '=', 'languages.id')
			     ->where('user_id', '=', $id)
			     ->get();
			$user->image_path = url('public/uploads');

			$result['profile'] = $user;
		} else {
			$result['status'] = 'Error';
			$result['msg']    = $this->save_error;
		}

		return response()->json($result);
	}

	public function updateProfile(Request $request) {

		$validator = Validator::make($request->all(), [
				'user_id' => 'required',
			]);

		if ($validator->fails()) {
			$result['status'] = 'Error';
			$result['msg']    = 'Required fields must not be empty';
			$result['error']  = $validator->errors();

			return response()->json($result);
		}

		$user_id        = $request->user_id;
		$username       = trim($request->input('username'));
		$password       = trim($request->input('password'));
		$gender         = trim($request->input('gender'));
		$dob            = trim($request->input('dob'));
		$remember_token = uniqid();

		$user_data = [
			'username'   => $username,
			'password'   => $password,
			'gender'     => $gender,
			'dob'        => $dob,
			'updated_at' => $this->current_date_time,
		];

		if ($request->hasFile('picture')) {
			$file            = $request->file('picture');
			$extension       = $file->getClientOriginalExtension();
			$picture         = uniqid().'.'.$extension;
			$destinationPath = base_path().'/public/uploads';
			$file->move($destinationPath, $picture);

			$result = $this->create_watermark($destinationPath.'/'.$picture, $destinationPath.'/'.$picture);

			$image_data = ['image' => $picture];
			$user_data  = array_merge($user_data, $image_data);
		}

		$res = DB::table('users')->where('id', '=', $user_id)->update($user_data);

		if ($res == 1 || $res == 0) {

			$result['status'] = 'Success';
			$result['msg']    = $this->update_success;
			$user             = User::getUserDetails($user_id);
			$user->categories = User::getUserCategories($user_id);
			$user->languages  = DB::table('user_languages')
			     ->select('languages.id', 'title')
			     ->join('languages', 'user_languages.language_id', '=', 'languages.id')
			     ->where('user_id', '=', $user_id)
			     ->get();
			$user->image_path = url('public/uploads');

			$result['profile'] = $user;
		} else {
			$result['status'] = 'Error';
			$result['msg']    = $this->update_error;
		}

		return response()->json($result);
	}

	public function userVerify(Request $request) {

		$token = $request->segment(3);
		if ($token) {
			$check = $this->checkRecord('users', ['remember_token' => $token]);
			if ($check) {
				$res = $this->updateRecord('users', ['remember_token' => $token],
					[
						'remember_token' => uniqid(), 'status' => 1,
					]);

				if ($res) {
					die('Account Successfully verified');
					//Session::flash('success', 'Account Successfully verified');
				} else {
					die('Could not be activated');
					//Session::flash('error', 'Could not be activated');
				}
			} else {
				die('Invalid Request');
			}
		}
	}

	public function corporateRegister(Request $request) {

		$validator = Validator::make($request->all(), [
				'name'         => 'required',
				'company_name' => 'required',
				'email'        => 'required|email',
				'phone'        => 'required',
			]);

		if ($validator->fails()) {
			$result['status'] = 'Error';
			$result['msg']    = 'Required fields must not be empty';
			$result['error']  = $validator->errors();

			return response()->json($result);
		}

		$msg = '<b>Name</b>: '.$request->name.'
		<br><b>Company Name</b>: '.$request->company_name.'
		<br><b>Email</b>: '.$request->email.'
		<br><b>Company Name</b>: '.$request->phone.'
		<br><b>Notes</b>: '.$request->notes;
		$send = $this->send_email('hafizmabuzar@synergistics.pk', 'KK Events', 'Corporate User', $msg, $request->email);

		$result['status'] = 'Success';
		$result['msg']    = 'Successfully Sent';

		return response()->json($result);
	}

	public function login(Request $request) {

		$validator = Validator::make($request->all(), [
				'email'    => 'required',
				'password' => 'required',
			]);

		if ($validator->fails()) {
			$result['status'] = 'Error';
			$result['msg']    = 'Required fields must not be empty';
			$result['error']  = $validator->errors();

			return response()->json($result);
		}

		$email    = trim($request->input('email'));
		$password = trim($request->input('password'));

		// params => id, email, password
		$user = User::getUserDetails(null, $email, $password);

		if ($user) {
			if ($user->status == 0) {
				$result['status'] = 'Pending';
				$result['msg']    = 'Account pending';
			} else {
				$user->categories = User::getUserCategories($user->id);
				$user->languages  = DB::table('user_languages')
				     ->select('languages.id', 'title')
				     ->join('languages', 'user_languages.language_id', '=', 'languages.id')
				     ->where('user_id', '=', $user->id)
					->get();
				$user->image_path = url('public/uploads');

				$user->categories = User::getUserCategories($user->id);
				$user->image_path = url('public/uploads');

				if (!empty($request ->token)) {
					DB::table('tokens')->where('token', $request->token)->update(['user_email' => $email]);
				}

				$result['status']  = 'Success';
				$result['msg']     = 'User found';
				$result['profile'] = $user;
			}
		} else {
			$result['status'] = 'Error';
			$result['msg']    = 'Invalid Credentials';
		}

		return response()->json($result);
	}

	public function logout(Request $request) {

		if (!empty($request ->token)) {
			DB::table('tokens')->where('token', $request->token)->update(['user_email' => '']);
		}

		$result['status'] = 'Success';
		$result['msg']    = 'Successfully LoggedOut';

		return response()->json($result);
	}

	public function forgotPassword(Request $request) {

		$email = $request->email;
		if (empty($email)) {
			$result['status'] = 'Error';
			$result['status'] = 'Email field is required';

			return response()->json($result);
		}

		$check = DB::table('users')->where('email', '=', $email)->first();
		if ($check) {
			if ($check->is_social == 1) {
				$result['status'] = 'Error';
				$result['msg']    = 'Social Account';
			} else {
				$msg = "Dear ".ucwords($check->username).",<br/><br/>Please click the given link to reset your password<br/>
                <a href='".url('/password/reset/'.$remember_token)."'>Click Here</a>";
				$send = $this->send_email($email, 'KK Events', 'Reset Password', $msg);
			}
		} else {
			$result['status'] = 'Error';
			$result['msg']    = 'Email not found';
		}

		return response()->json($result);
	}

	public function saveUserPreferred(Request $request) {

		$validator = Validator::make($request->all(), [
				'categories' => 'required',
				'languages'  => 'required',
				'kids'       => 'required',
				'disability' => 'required',
				'email'      => 'required|email|max:100',
			]);

		if ($validator->fails()) {
			$result['status'] = 'Error';
			$result['msg']    = 'Required fields must not be empty';
			$result['error']  = $validator->errors();

			return response()->json($result);
		}

		$email = $request->email;
		$check = $this->checkRecord('users', ['email' => $email]);
		if ($check) {
			DB::table('user_categories')->where('user_id', $check[0]->id)->delete();
			DB::table('user_languages')->where('user_id', $check[0]->id)->delete();

			foreach ($request->categories as $cat) {
				$categories[] = ['user_id' => $check[0]->id, 'category_id' => $cat];
			}
			foreach ($request->languages as $lng) {
				$languages[] = ['user_id' => $check[0]->id, 'language_id' => $lng];
			}

			$res = DB::table('user_categories')->insert($categories);
			$res = DB::table('user_languages')->insert($languages);

			DB::table('users')->where('id', '=', $check[0]->id)->update([
					'interested_in_kids'     => $request->kids,
					'interested_in_disabled' => $request->disability
				]);

			if ($res == 1) {
				$user             = User::getUserDetails($check[0]->id);
				$user->categories = User::getUserCategories($check[0]->id);
				$user->languages  = DB::table('user_languages')
				     ->select('languages.id', 'title')
				     ->join('languages', 'user_languages.language_id', '=', 'languages.id')
				     ->where('user_id', '=', $check[0]->id)
					->get();
				$user->image_path = url('public/uploads');

				$result['status']  = 'Success';
				$result['msg']     = $this->save_success;
				$result['profile'] = $user;
			} else {
				$result['status'] = 'Error';
				$result['msg']    = $this->save_error;
			}
		} else {
			$result['status'] = 'Error';
			$result['msg']    = 'User not found';
		}

		return response()->json($result);
	}

	public function saveToken($token = null) {

		$token = ($token)?$token:$_REQUEST['token'];

		if (empty($token)) {
			$result['status'] = 'Error';
			$result['msg']    = 'Token required';
		} else {
			$check = $this->checkRecord('tokens', array('token' => $token));
			if (!$check) {
				$fields = array(
					'app_id'       => "465e788f-a2c0-4d49-b875-6b92120c2e5b",
					'identifier'   => $token,
					'language'     => "en",
					'timezone'     => "-28800",
					'game_version' => "1.0",
					'device_os'    => "",
					'device_type'  => "0",
					'device_model' => "iPhone",
					'test_type'    => 1,
				);

				$fields = json_encode($fields);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				$response = curl_exec($ch);
				curl_close($ch);

				$response = json_decode($response);
				$res      = DB::table('tokens')->insertGetId([
						'token'      => $token,
						'object_id'  => $response->id,
						'created_at' => $this->current_date_time,
						'updated_at' => $this->current_date_time,
					]);

				if ($res) {
					$result['status'] = 'Success';
					$result['msg']    = $this->save_success;
				} else {
					$result['status'] = 'Error';
					$result['msg']    = $this->save_error;
				}
			}
		}

		return response()->json($result);
	}

	public function saveAndroidToken($token = null, $device_id = null) {

		$token     = ($token)?$token:$_REQUEST['token'];
		$device_id = ($device_id)?$device_id:$_REQUEST['device_id'];

		if (empty($token) || empty($device_id)) {
			$result['status'] = 'Error';
			$result['msg']    = 'Required fields must not be empty';

			return response()->json($result);
		}

		$token_data = array(
			'token'      => $token,
			'device_id'  => $device_id,
			'created_at' => $this->current_date_time,
			'updated_at' => $this->current_date_time,
		);

		$check = $this->checkRecord('tokens', array('device_id' => $device_id));
		if ($check) {
			$this->updateRecord('tokens', ['device_id' => $device_id], array('token' => $token));
			$result['status'] = 'Success';
			$result['msg']    = $this->update_success;
		} else {
			$res = DB::table('tokens')->insersatGetId($token_data);
			if ($res) {
				$result['status'] = 'Success';
				$result['msg']    = $this->save_success;
			} else {
				$result['status'] = 'Error';
				$result['msg']    = $this->save_error;
			}
		}

		return response()->json($result);
	}

	public function product_notification() {

		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('msg', 'Message', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->load->view('includes/header');
			$this->load->view('push_notification');
			$this->load->view('includes/footer');
		} else {
			$title = $_POST['title'];
			$msg   = $_POST['msg'];

			if (isset($_POST['send_to_android']) && isset($_POST['send_to_ios'])) {

				$result_android = $this->android_push($title, $msg);
				$result_ios     = $this->ios_notification($title, $msg);
			} else if (isset($_POST['send_to_android'])) {
				$result_android = $this->android_push($title, $msg);
			} else if ($_POST['send_to_ios']) {
				$result_ios = $this->ios_notification($title, $msg);
			} else {
				$result_android = $this->android_push($title, $msg);
				$result_ios     = $this->ios_notification($title, $msg);
			}

			if (isset($result_ios) || isset($result_android)) {
				$this->session->set_userdata('error', 'Successfully Sent !');
				redirect('home/push_form');
			}
		}
	}

	public function ind_product_notification() {

		if (!isset($_POST['submit'])) {
			$result['emails'] = $this->Home_model->getAllLoggedInUsers();

			$this->load->view('includes/header');
			$this->load->view('ind_push_notification', $result);
		} else {
			$result_ios     = $this->ios_notification($_POST['title'], $_POST['msg'], $_POST['email']);
			$result_android = $this->android_push($_POST['title'], $_POST['msg'], $_POST['email']);

			if (isset($result_ios) || isset($result_android)) {
				$this->session->set_userdata('error', 'Successfully Sent !');

				$result['emails'] = $this->Home_model->getAllLoggedInUsers();

				$this->load->view('includes/header');
				$this->load->view('ind_push_notification', $result);
			}
		}
	}

	public function ios_notification($noti_title, $msg, $email = '') {

		if (!empty($email)) {
			$email  = "and user_email = '".$email."'";
			$tokens = DB::raw("select token from tokens where device_id is null $email");
			foreach ($tokens as $tk) {
				$ids[] = $tk['token'];
			}

			$devices = ['include_ios_tokens' => $ids];
		} else {
			$devices = ['included_segments' => array('All')];
		}

		$title = array(
			"en" => $noti_title,
		);
		$content = array(
			"en" => $msg,
		);

		$fields = array(
			'app_id'         => "a58f2a20-c16c-4b09-8202-4a768d408a97",
			'contents'       => $content,
			'heading'        => $title,
			'data'           => ['title'           => $noti_title, 'body'           => $msg],
			'ios_badgeType'  => 'SetTo',
			'ios_badgeCount' => 1,
		);
		$fields = array_merge($fields, $devices);

		$fields = json_encode($fields);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
				'Authorization: Basic NTdkYTU5ZGYtM2M3Yy00MDNkLWE0NjAtOTU4MWVjZWY2NmNh'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

	public function android_push($title, $body, $email = '') {

		$email = (!empty($email))?$email:'';

		$tokens = $this->Home_model->getTokens($email);

		foreach ($tokens as $tk) {
			$ids[] = $tk['token'];
		}

		$chunks = array_chunk($ids, 1000);

		foreach ($chunks as $chk) {
			$registrationIds = $chk;
			define('API_ACCESS_KEY', 'AIzaSyCFa-Xt1PROlf6n51Mxh8fe4MzyODv8i8Q');

			$msg['notification'] = array
			(
				'title'   => $title,
				'message' => $body,
			);

			$fields = array
			(
				'registration_ids' => $registrationIds,
				'data'             => $msg,
			);

			$headers = array
			(
				'Authorization: key='.API_ACCESS_KEY,
				'Content-Type: application/json',
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			curl_close($ch);

			return $result;
		}
	}

	//-------------------------------------------------------------------------

	/*
	---------------------------------------
	Start common queries functions
	---------------------------------------
	 */

	protected function checkLogin() {

		if (!Session::get('
}manager_data')
		) {
			return view('landlord.login')->withErrors(['error' => 'You are not logged in, Please login first']);
		}
	}

	protected function checkRecord($table, $fields) {

		$query = DB::table($table);
		foreach ($fields as $where => $value) {
			$query->where($where, $value);
		}

		return $query->get();
	}

	protected function updateRecord($table, $fields, $data) {

		$query = DB::table($table);
		foreach ($fields as $where => $value) {
			$query->where($where, $value);
		}

		return $query->update($data);
	}

	function getTokens($email = '') {
		$email = (!empty($email))?"and user_email = '".$email."'":'';

		return $query = DB::table(DB::raw("select token from tokens where device_id <> '' $email"))->get();
	}

	public function send_email($to, $from = '', $subject, $msg, $from_email = null) {

		$mail = new \PHPMailer(true);

		$mail->SMTPDebug = 0;// Enable verbose debug output

		$mail->isSMTP();// Set mailer to use SMTP
		$mail->Host       = 'ssl://smtp.googlemail.com';// Specify main and backup SMTP servers
		$mail->SMTPAuth   = true;// Enable SMTP authentication
		$mail->Username   = 'hamzasynergistics@gmail.com';// SMTP username
		$mail->Password   = 'synergistics';// SMTP password
		$mail->SMTPSecure = 'ssl';// Enable TLS encryption, `ssl` also accepted
		$mail->Port       = 465;

		// live
		/*$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'secureuk6.sgcpanel.com';                // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'support@alphabeta.ae';                 // SMTP username
		$mail->Password = 'support123@';                         // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465;  */// TCP port to connect to

		$mail->setFrom('support@kkevents.ae', $from);
		$mail->addAddress($to, 'KK APP');// Add a recipient
		if (!empty($from_email)) {
			$mail->addReplyTo($from_email, '');
		} else {
			$mail->addReplyTo('no-reply@kkevents.ae', '');
		}

		$mail->isHTML(true);// Set email format to HTML

		$mail->Subject = $subject;
		$mail->Body    = $msg;
		$mail->AltBody = 'Welcome to KK App';

		if (!$mail->send()) {
			return 0;
			echo 'Mailer Error: '.$mail->ErrorInfo;
		} else {
			return 1;
		}
	}

	/*
---------------------------------------
End common queries functions
---------------------------------------
 */
}
