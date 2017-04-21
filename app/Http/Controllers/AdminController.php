<?php

namespace App\Http\Controllers;

use App\Event;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Session;
use Validator;

class AdminController extends Controller {
	public function __construct() {

		$this->current_date_time = Carbon::now('Asia/Dubai');
	}

	public function index(Request $request) {

		if ($request->isMethod('get')) {

			if (Session::has('admin_data')) {
				return redirect('admin/view-events');
			}

			return view('admin.login');
		}

		if ($request->email == 'admin' && $request->password == 'admin') {
			Session::put('admin_data', 'loggedIn');
			return redirect('admin/view-events');
		}

		Session::put('login_error', 'Invalid Username or Password');
		return redirect()->back();
	}

	public function logout(Request $request) {

		Session::flush('admin_data');
		return view('admin.login');
	}

	/* Category CRUD start */
	public function addCategory(Request $request) {

		if ($request->isMethod('get')) {
			return view('admin.add-category');
		}

		$validation_data = [
			'arabic_category'   => 'required',
			'english_category'  => 'required',
			'non_selected_icon' => 'required',
			'selected_icon'     => 'required',
		];

		$validator = Validator::make($request->all(), $validation_data);

		if ($validator    ->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}

		// selected icon
		$file            = $request->file('selected_icon');
		$extension       = $file->getClientOriginalExtension();
		$picture         = uniqid().'.'.$extension;
		$destinationPath = base_path().'/public/admin/icons';
		$file->move($destinationPath, $picture);

		// non selected icon
		$file2           = $request->file('non_selected_icon');
		$picture2        = 'non-'.$picture;
		$destinationPath = base_path().'/public/admin/icons';
		$file2->move($destinationPath, $picture2);

		$category_data = [
			'arabic'            => $request->arabic_category,
			'english'           => $request->english_category,
			'non_selected_icon' => $picture2,
			'selected_icon'     => $picture,
			'created_at'        => $this->current_date_time,
			'updated_at'        => $this->current_date_time,
		];

		$id = DB::table('categories')->insertGetId($category_data);

		if ($id) {
			Session::flash('success', 'Type successfully added');

			return redirect('admin/view-categories');
		}

		Session::flash('error', 'Type could not be added');

		return redirect()->back();
	}

	public function viewCategories() {

		$result['categories'] = DB::table('categories')->orderBy('id', 'DESC')->paginate(15);

		return view('admin.view-categories', $result);
	}

	public function updateCategory(Request $request) {

		if ($request->isMethod('get')) {

			$id = Crypt::decrypt($request->segment(3));

			$result['category'] = DB::table('categories')->where('id', '=', $id)->first();

			return view('admin.edit-category', $result);
		}

		$validation_data = [
			'arabic_category'  => 'required',
			'english_category' => 'required',
		];

		if (empty($request->old_icon)) {
			$icon            = ['selected_icon' => 'required'];
			$validation_data = array_merge($icon, $validation_data);
		}
		if (empty($request->old_icon_non)) {
			$non_icon        = ['non_selected_icon' => 'required'];
			$validation_data = array_merge($non_icon, $validation_data);
		}

		$validator = Validator::make($request->all(), $validation_data);

		if ($validator    ->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}

		if ($request->hasFile('selected_icon')) {
			$file            = $request->file('selected_icon');
			$extension       = $file->getClientOriginalExtension();
			$picture         = uniqid().'.'.$extension;
			$destinationPath = base_path().'/public/admin/icons';
			$file->move($destinationPath, $picture);
		} else {
			$picture = $request->old_icon;
		}
		if ($request->hasFile('non_selected_icon')) {
			$file2           = $request->file('non_selected_icon');
			$picture2        = 'non_'.$picture;
			$destinationPath = base_path().'/public/admin/icons';
			$file2->move($destinationPath, $picture2);
		} else {
			$picture2 = $request->old_icon_non;
		}

		$category_data = [
			'arabic'            => $request->arabic_category,
			'english'           => $request->english_category,
			'selected_icon'     => $picture,
			'non_selected_icon' => $picture2,
			'updated_at'        => $this->current_date_time,
		];

		$id = Crypt::decrypt($request->cat_id);

		$res = DB::table('categories')->where('id', '=', $id)->update($category_data);

		if ($res > 0) {
			Session::flash('success', 'Category successfully updated');

			return redirect('admin/view-categories');
		}

		Session::flash('error', 'Category could not be updated');

		return redirect()->back();
	}

	public function deleteCategory(Request $request) {

		$id       = Crypt::decrypt($request->segment(3));
		$category = DB::table('categories')->where('id', '=', $id)->first();
		unlink(base_path().'/public/admin/icons/'.$category->selected_icon);
		unlink(base_path().'/public/admin/icons/non-'.$category->selected_icon);

		$res = DB::table('categories')->where('id', '=', $id)->delete();

		if ($res == 1) {
			Session::flash('success', 'Category successfully deleted');
		} else {
			Session::flash('error', 'Category could not be deleted');
		}

		return redirect('admin/view-categories');
	}
	/*---------- Category CRUD End ----------*/

	/* Type CRUD start */
	public function addType(Request $request) {

		if ($request->isMethod('get')) {
			return view('admin.add-type');
		}

		$validation_data = [
			'arabic_type'  => 'required',
			'english_type' => 'required',
		];

		$validator = Validator::make($request->all(), $validation_data);

		if ($validator    ->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}

		$type_data = [
			'arabic'     => $request->arabic_type,
			'english'    => $request->english_type,
			'created_at' => $this->current_date_time,
			'updated_at' => $this->current_date_time,
		];

		$id = DB::table('types')->insertGetId($type_data);

		if ($id) {
			Session::flash('success', 'Type successfully added');

			return redirect('admin/view-types');
		}

		Session::flash('error', 'Type could not be added');

		return redirect()->back();
	}

	public function viewTypes() {

		$result['types'] = DB::table('types')->orderBy('id', 'DESC')->paginate(15);
		return view('admin.view-types', $result);
	}

	public function updateType(Request $request) {

		if ($request->isMethod('get')) {

			$id             = Crypt::decrypt($request->segment(3));
			$result['type'] = DB::table('types')->where('id', '=', $id)->first();

			return view('admin.edit-type', $result);
		}

		$validation_data = [
			'arabic_type'  => 'required',
			'english_type' => 'required',
		];

		$validator = Validator::make($request->all(), $validation_data);

		if ($validator    ->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}

		$type_data = [
			'arabic'     => $request->arabic_type,
			'english'    => $request->english_type,
			'updated_at' => $this->current_date_time,
		];

		$id = Crypt::decrypt($request->type_id);

		$res = DB::table('types')->where('id', '=', $id)->update($type_data);

		if ($res > 0) {
			Session::flash('success', 'Type successfully updated');

			return redirect('admin/view-types');
		}

		Session::flash('error', 'Type could not be updated');

		return redirect()->back();
	}

	public function deleteType(Request $request) {

		$id  = Crypt::decrypt($request->segment(3));
		$res = DB::table('types')->where('id', '=', $id)->delete();

		if ($res == 1) {
			Session::flash('success', 'Type successfully deleted');
		} else {
			Session::flash('error', 'Type could not be deleted');
		}

		return redirect('admin/view-types');
	}
	/*---------- Type CRUD End ----------*/

	/* User CRUD start */
	public function AddUser(Request $request) {

		if ($request->isMethod('get')) {
			return view('admin.add-user');
		}

		$validation_data = [
			'username' => 'required',
			'email'    => 'required|unique:users|email',
			'password' => 'required',
			'gender'   => 'required',
			'dob'      => 'required',
		];

		$validator = Validator::make($request->all(), $validation_data);

		if ($validator    ->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}

		$file            = $request->file('picture');
		$extension       = $file->getClientOriginalExtension();
		$picture         = uniqid().'.'.$extension;
		$destinationPath = base_path().'/public/uploads';
		$file->move($destinationPath, $picture);

		$remember_token = uniqid();

		$user_data = [
			'username'       => $request->username,
			'email'          => $request->email,
			'password'       => $request->password,
			'gender'         => $request->gender,
			'dob'            => $request->dob,
			'image'          => $picture,
			'remember_token' => $remember_token,
			'status'         => 1,
			'is_verified'    => 1,
			'created_at'     => $this->current_date_time,
			'updated_at'     => $this->current_date_time,
		];

		$id = DB::table('users')->insertGetId($user_data);

		if ($id) {
			Session::flash('success', 'User successfully added');

			return redirect('admin/view-users');
		}

		Session::flash('error', 'User could not be added');

		return redirect()->back();
	}

	public function updateUser(Request $request) {

		if ($request->isMethod('get')) {

			$id             = Crypt::decrypt($request->segment(3));
			$result['user'] = DB::table('users')->where('id', '=', $id)->first();

			return view('admin.edit-user', $result);
		}

		$validation_data = [
			'username' => 'required',
			'email'    => 'required|email',
			'gender'   => 'required',
		];

		if (empty($request->old_picture)) {
			$picture         = ['picture' => 'required'];
			$validation_data = array_merge($picture, $validation_data);
		}

		$validator = Validator::make($request->all(), $validation_data);

		if ($validator    ->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}

		$id = Crypt::decrypt($request->user_id);

		$check = DB::table('users')->where('id', '!=', $id)->where('email', '=', $request->email)->first();
		if ($check) {
			return redirect()->back()->withInput()->withErrors(['error' => 'Email already exists']);
		}

		if ($request->hasFile('picture')) {
			$file            = $request->file('picture');
			$extension       = $file->getClientOriginalExtension();
			$picture         = uniqid().'.'.$extension;
			$destinationPath = base_path().'/public/uploads';
			$file->move($destinationPath, $picture);
		} else {
			$picture = $request->old_picture;
		}

		$user_data = [
			'username'    => $request->username,
			'email'       => $request->email,
			'gender'      => $request->gender,
			'dob'         => $request->dob,
			'image'       => $picture,
			'is_verified' => $request->verified,
			'updated_at'  => $this->current_date_time,
		];

		$res = DB::table('users')->where('id', '=', $id)->update($user_data);

		if ($res > 0) {
			Session::flash('success', 'User successfully updated');
			if ($request->verified == 1) {
				return redirect('admin/view-verified-users');
			} else {
				return redirect('admin/view-users');
			}
		}

		Session::flash('error', 'User could not be updated');

		return redirect()->back();
	}

	public function viewUsers() {

		$result['users'] = DB::table('users')->select('*',
			DB::raw('(
            CASE
            WHEN status = 0 THEN "Pending"
            WHEN status = 1 THEN "Active"
            ELSE "Deleted" END) AS status, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age')
		)->where('is_verified', '=', 0)->orderBy('username', 'asc')->paginate(15);

		return view('admin.view-users', $result);
	}

	public function viewVerifiedUsers() {

		$result['users'] = DB::table('users')->select('*',
			DB::raw('(
            CASE
            WHEN status = 0 THEN "Pending"
            WHEN status = 1 THEN "Active"
            ELSE "Deleted" END) AS status, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age')
		)->where('is_verified', '=', 1)->orderBy('username', 'asc')->paginate(15);

		return view('admin.view-verified-users', $result);
	}

	public function deleteUser(Request $request) {

		$id  = Crypt::decrypt($request->segment(3));
		$res = DB::table('users')->where('id', '=', $id)->update(['status' => 2]);

		if ($res == 1) {
			Session::flash('success', 'User successfully deleted');
		} else {
			Session::flash('error', 'User could not be deleted');
		}

		return redirect('admin/view-users');
	}

	public function deleteVerifiedUser(Request $request) {

		$id  = Crypt::decrypt($request->segment(3));
		$res = DB::table('users')->where('id', '=', $id)->update(['status' => 2]);

		if ($res == 1) {
			Session::flash('success', 'User successfully deleted');
		} else {
			Session::flash('error', 'User could not be deleted');
		}

		return redirect('admin/view-verified-users');
	}
	/*---------- User CRUD End ----------*/

	/* Event module start */
	public function searchEvents(Request $request) {

		$search = $request->search;

		if(empty($search)){
			return redirect('admin/view-events/'.$request->sort);
		}
		if (!empty($request->sort)) {
			$sort = [
				'paid'     => 'free_event ASC, start_date DESC',
				'free'     => 'free_event DESC, start_date DESC',
				'name'     => 'eng_name ASC',
				'company'  => 'eng_company_name ASC',
				'date'     => 'start_date DESC'
			];

			$order = $sort[$request->sort];
		} else {
			$order = 'id DESC';
		}

		$result['events'] = Event::getSearchEvent($search, $order);
		$result['uri_segment'] = 'search';
		$result['search'] = $search;

		if (count($result['events']) > 0) {
			foreach ($result['events'] as $event) {
				$result['categories'][] = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();
				$result['locations'][]   = DB::table('locations')->where('event_id', $event->id)->get();
			}
		}
		else{
			$result['categories'] = array();
			$result['locations'] = array();
		}

		return view('admin.view-events', $result);
	}

	public function viewEvents(Request $request) {

		if (!empty($request->sort)) {
			$sort = [
				'paid'     => 'free_event ASC, start_date DESC',
				'free'     => 'free_event DESC, start_date DESC',
				'name'     => 'eng_name ASC',
				'company'  => 'eng_company_name ASC',
				'date'     => 'start_date DESC'
			];

			$order = $sort[$request->sort];
		} else {
			$order = 'id DESC';
		}

		$result['events'] = DB::table('events')
			->select(DB::raw('events.*, username'))
			->join('users', 'users.id', '=', 'events.user_id', 'left')
			->join('categories', 'users.id', '=', 'events.user_id', 'left')
			->orderByRaw($order)
			->groupBy('events.id')
			->paginate(15);

		if (count($result['events']) > 0) {
			foreach ($result['events'] as $event) {
				$result['categories'][] = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();
				$result['locations'][]   = DB::table('locations')->where('event_id', $event->id)->get();
			}
		}


		// dd($result);


		$result['uri_segment'] = 'view';

		return view('admin.view-events', $result);
	}

	public function addEvent(Request $request) {

		if ($request->isMethod('get')) {
			$keywords = DB::table('events')->select('keyword')->take(5)->orderBy('id', 'DESC')->get();

			foreach ($keywords as $kw) {
				$sub_keyword = explode(',', $kw->keyword);
				foreach ($sub_keyword as $skw) {
					$result['keywords'][]['keyword'] = trim($skw);
				}
			}
			$result['cities']      = DB::table('cities')->select('city_name', 'latitude', 'longitude')->where('country_id', 11)->orderBy('city_name', 'ASC')->get();
			$result['categories']  = DB::table('categories')->select('id', 'english', 'arabic')->get();
			$result['types']       = DB::table('types')->select('id', 'english', 'arabic')->get();
			$result['languages']   = DB::table('languages')->select('id', 'title')->get();
			$result['uri_segment'] = $request->segment(2);

			return view('admin.event-details', $result);
		}

		$validation_data = [
			'type'             => 'required',
			'category'         => 'required',
			'keyword'          => 'required',
			'event_name'       => 'required',
			'event_name_ar'    => 'required',
			'event_company'    => 'required',
			'event_company_ar' => 'required',
			'phone'            => 'required',
			'email'            => 'required|email',
			'url'              => 'required',
			'fee'              => 'required',
			'city'             => 'required',
			'location'         => 'required',
			'venue'            => 'required',
		];

		if ($request->all_day != 1) {
			$event_dates = [
				'start_date' => 'required',
				'start_time' => 'required',
				'end_date'   => 'required',
				'end_time'   => 'required'
			];

			$validation_data = array_merge($validation_data, $event_dates);
		}

		$validator = Validator::make($request->all(), $validation_data);

		if ($validator    ->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}

		$reference_no = uniqid();

		$event_data = [
			'type_id'          => implode(',', $request->type),
			'category_id'      => implode(',', $request->category),
			'keyword'          => $request->keyword,
			'eng_name'         => $request->event_name,
			'ar_name'          => $request->event_name_ar,
			'eng_company_name' => $request->event_company,
			'ar_company_name'  => $request->event_company_ar,
			'phone'            => $request->phone,
			'email'            => $request->email,
			'weblink'          => $request->url,
			'start_date'       => date('Y-m-d', strtotime($request->start_date)).' '.date('H:i:s', strtotime($request->start_time)),
			'end_date'         => date('Y-m-d', strtotime($request->end_date)).' '.date('H:i:s', strtotime($request->end_time)),
			'all_day'          => $request->all_day,
			'free_event'       => $request->fee,
			'facebook'         => $request->facebook,
			'twitter'          => $request->twitter,
			'instagram'        => $request->instagram,
			'event_language'   => implode(',', $request->event_language),
			'eng_description'  => $request->eng_description,
			'ar_description'   => $request->ar_description,
			'venue'            => $request->venue,
			'is_kids'          => $request->kids,
			'is_disabled'      => $request->disable,
			'is_featured'      => $request->featured,
			'share_count'      => 1,
			'created_at'       => $this->current_date_time,
			'updated_at'       => $this->current_date_time,
		];

		if (!empty($request->event_id) && $request->uri != 'duplicate-event') {
			$event_id = Crypt::decrypt($request->event_id);
			$res      = DB::table('events')->where('id', '=', $event_id)->update($event_data);

			if ($res == 1) {
				$id = $event_id;
			} else {
				return redirect()->back()->withInput()->withErrors(['Could not be updated']);
			}
		} else {
			$ref        = ['reference_no' => $reference_no];
			$event_data = array_merge($ref, $event_data);
			$id         = DB::table('events')->insertGetId($event_data);
		}
		if ($id) {

			$destinationPath = base_path().'/public/uploads';

			if ($request->uri == 'duplicate-event') {
				$event_id = Crypt::decrypt($request->event_id);
				$pic_res  = DB::table('pictures')->where('event_id', $event_id)->get();
				if ($pic_res) {
					foreach ($pic_res as $old) {
						$img1 = $destinationPath.'/'.$old->picture;

						$extension = explode('.', $old->picture);
						$img2_name = uniqid().'.'.$extension[1];
						$img2      = $destinationPath.'/'.$img2_name;

						copy($img1, $img2);
						$pic_data[] = ['event_id' => $id, 'picture' => $img2_name];
					}
				}

				$attch_res = DB::table('attachments')->where('event_id', $event_id)->get();
				if ($attch_res) {
					foreach ($attch_res as $old) {
						$img1 = $destinationPath.'/'.$old->picture;

						$extension = explode('.', $old->picture);
						$img2_name = uniqid().'.'.$extension[1];
						$img2      = $destinationPath.'/'.$img2_name;

						copy($img1, $img2);
						$attch_data[] = ['event_id' => $id, 'picture' => $img2_name];
					}
				}
			}

			if ($request->hasFile('picture')) {
				for ($pic = 1; $pic <= count($request->picture); $pic++) {
					$file          = $request->file('picture')[$pic-1];
					$extension     = $file->getClientOriginalExtension();
					$picture[$pic] = uniqid().'.'.$extension;
					$file->move($destinationPath, $picture[$pic]);

					$this->create_watermark($destinationPath.'/'.$picture[$pic], $destinationPath.'/'.$picture[$pic]);
					$pic_data[] = ['event_id' => $id, 'picture' => $picture[$pic]];
				}
			}

			if (isset($pic_data)) {
				DB::table('pictures')->insert($pic_data);
			}

			if ($request->hasFile('attachment')) {
				for ($attch = 1; $attch <= count($request->attachment); $attch++) {
					$file               = $request->file('attachment')[$attch-1];
					$extension          = $file->getClientOriginalExtension();
					$attachment[$attch] = uniqid().'.'.$extension;
					$file->move($destinationPath, $attachment[$attch]);

					$this->create_watermark($destinationPath.'/'.$attachment[$attch], $destinationPath.'/'.$attachment[$attch]);
					$attch_data[] = ['event_id' => $id, 'picture' => $attachment[$attch]];
				}
			}

			if (isset($attch_data)) {
				DB::table('attachments')->insert($attch_data);
			}

			$cities    = explode('~', $request->city);
			$locations = explode('~', $request->location);
			$latlngs   = explode('~', $request->latlng);

			DB::table('locations')->where('event_id', $id)->delete();

			for ($loc = 1; $loc < count($cities); $loc++) {

				$latlng = str_replace(array('(', ')'), '', $latlngs[$loc]);
				$latlng = explode(',', $latlng);
				$city   = explode(',', $cities[$loc]);

				$loc_data[] = [
					'event_id'  => $id,
					'city'      => $city[0],
					'location'  => $locations[$loc],
					'latitude'  => $latlng[0],
					'longitude' => $latlng[1]
				];

			}
			DB::table('locations')->insert($loc_data);

			Session::flash('success', 'Event successfully saved');

			return redirect('admin/view-events');
		}
	}

	public function eventDetails(Request $request) {

		$id = Crypt::decrypt($request->segment(3));
		if ($id) {

			$keywords = DB::table('events')->select('keyword')->take(5)->orderBy('id', 'DESC')->get();
			foreach ($keywords as $kw) {
				$sub_keyword = explode(',', $kw->keyword);
				foreach ($sub_keyword as $skw) {
					$result['keywords'][]['keyword'] = trim($skw);
				}
			}

			$result['event'] = Event::with('pictures', 'attachments', 'locations')->where('events.id', '=', $id)->first();

			$result['cities']              = DB::table('cities')->select('city_name', 'latitude', 'longitude')->where('country_id', 11)->orderBy('city_name', 'ASC')->get();
			$result['categories']          = DB::table('categories')->select('id', 'english', 'arabic')->get();
			$result['types']               = DB::table('types')->select('id', 'english', 'arabic')->get();
			$result['languages']           = DB::table('languages')->select('id', 'title')->get();
			$result['uri_segment']         = $request->segment(2);
			$result['old_event_languages'] = explode(',', $result['event']->event_language);
		}

		return view('admin.event-details', $result);
	}

	public function deleteEvent(Request $request) {

		$id          = Crypt::decrypt($request->segment(3));
		$attachments = DB::table('attachments')->where('event_id', '=', $id)->get();
		$pictures    = DB::table('pictures')->where('event_id', '=', $id)->get();

		foreach ($attachments as $attch) {
			unlink(base_path().'/public/uploads/'.$attch->picture);
		}
		foreach ($pictures as $pic) {
			unlink(base_path().'/public/uploads/'.$pic->picture);
		}

		// $res = DB::table('events')->where('id', '=', $id)->delete();
		$res = DB::delete("delete events, attachments, pictures, locations
					from events
					left join attachments on attachments.event_id = events.id
					left join pictures on pictures.event_id = events.id
					left join locations on locations.event_id = events.id
					where events.id = $id
");

		Session::flash('success', 'Event successfully deleted');
		return redirect('admin/view-events');
	}

	public function eventStatus(Request $request) {

		$id  = Crypt::decrypt($request->segment(3));
		$res = DB::table('users')->where('id', '=', $id)->update(['status' => 2]);

		if ($res == 1) {
			Session::flash('success', 'User successfully deleted');
		} else {
			Session::flash('error', 'User could not be deleted');
		}

		return redirect('admin/view-users');
	}

	// Water mark logo on image
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
		$overlay_gd_image = imagecreatefrompng(base_path().'/public/logo.png');
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

}
