<?php

namespace App\Http\Controllers;

use App\Event;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Session;
use Validator;

class UserController extends Controller {
	public function __construct() {

		$this->current_date_time = Carbon::now('Asia/Dubai');
	}

	public function index(Request $request) {

		if ($request->isMethod('get')) {

			if (Session::has('user_id')) {
				return redirect('user/view-events');
			}

			return view('user.login');
		}

		$check = DB::table('users')
			->where('email', $request->email)
			->where('password', $request->password)
			->first();

		if ($check) {
			Session::put('user_id', $check->id);
			Session::put('user_name', $check->username);
			return redirect('user/view-events');
		}

		Session::put('login_error', 'Invalid Username or Password');
		return redirect()->back();
	}

	public function logout(Request $request) {

		Session::flush('user_id');
		Session::flush('user_name');
		return view('user.login');
	}

	/* Event module start */

	public function viewEvents() {

		$result['events'] = DB::table('events')
			->select(DB::raw('id, eng_name, eng_company_name, phone, email, start_date, end_date, all_day'))
			->where('user_id', Session::get('user_id'))
			->orderBy('id', 'DESC')
			->paginate(15);

		return view('user.view-events', $result);
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

			return view('user.event-details', $result);
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
			'user_id'          => Session::get('user_id'),
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
		$event_id     = Crypt::decrypt($request->event_id);

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
			$res = DB::table('events')->where('id', '=', $event_id)->update($event_data);

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
				$pic_res = DB::table('pictures')->where('event_id', $event_id)->get();
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