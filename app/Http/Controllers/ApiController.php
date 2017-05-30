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
        
        $this->digitalOcean = DB::connection('mysqlDO');

        //DB::enableQueryLog();
        $this->current_date_time = Carbon::now('Asia/Dubai');

        $this->save_success = 'Successfully saved';
        $this->save_error = 'Could not be saved';
        $this->update_success = 'Successfully updated';
        $this->update_error = 'Could not be updated';
    }

    public function getCountriesCities() {

        $countries = Country::with('cities')->orderBy(DB::raw('country_name'))->get();

        return response()->json($countries);
    }

    public function getCategories() {

        $cities = DB::table('cities')->select('city_name', 'arabic_city_name', 'latitude', 'longitude')->where('country_id', '=', 11)->orderBy('city_name', 'ASC')->get();
        $arabic_categories = DB::table('categories')->select('id', 'arabic', 'selected_icon', 'non_selected_icon')->orderBy('arabic')->get();
        $english_categories = DB::table('categories')->select('id', 'english', 'selected_icon', 'non_selected_icon')->orderBy('english')->get();
        $languages = DB::table('languages')->select('id', 'title')->orderBy('title')->get();

        foreach ($arabic_categories as $key => $cat) {
            $categories['arabic'][$key]['id'] = $cat->id;
            $categories['arabic'][$key]['title'] = $cat->arabic;
            $categories['arabic'][$key]['non_selected_icon'] = $cat->non_selected_icon;
            $categories['arabic'][$key]['selected_icon'] = $cat->selected_icon;

            $categories['english'][$key]['id'] = $english_categories[$key]->id;
            $categories['english'][$key]['title'] = ucwords($english_categories[$key]->english);
            $categories['english'][$key]['non_selected_icon'] = $english_categories[$key]->non_selected_icon;
            $categories['english'][$key]['selected_icon'] = $english_categories[$key]->selected_icon;
        }

        $arabic_types = DB::table('types')->select('id', 'arabic')->orderBy('arabic')->get();
        $english_types = DB::table('types')->select('id', 'english')->orderBy('english')->get();

        foreach ($arabic_types as $key => $type) {
            $types['arabic'][$key]['id'] = $type->id;
            $types['arabic'][$key]['title'] = $type->arabic;

            $types['english'][$key]['id'] = $english_types[$key]->id;
            $types['english'][$key]['title'] = ucwords($english_types[$key]->english);
        }

        $result['status'] = 'Success';
        $result['msg'] = 'All Preferred';
        $result['cities'] = $cities;
        $result['types'] = $types;
        $result['categories'] = $categories;
        $result['languages'] = $languages;
        $result['icon_url'] = url('public/admin/icons');

        return response()->json($result);
    }

    public function getTypes() {

        $arabic_types = DB::table('types')->select('id', 'arabic')->orderBy('arabic')->get();
        $english_types = DB::table('types')->select('id', 'english')->orderBy('english')->get();

        foreach ($arabic_types as $key => $type) {
            $types['arabic'][$key]['id'] = $type->id;
            $types['arabic'][$key]['title'] = $type->arabic;

            $types['english'][$key]['id'] = $english_types[$key]->id;
            $types['english'][$key]['title'] = $english_types[$key]->english;
        }

        $result['status'] = 'Success';
        $result['msg'] = 'All Preferred';
        $result['categories'] = $types;
        $result['languages'] = $languages;
        $result['icon_url'] = url('public/admin/icons');

        return response()->json($result);
    }

    protected function generateRandomString($length) {

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }

    public function addEvent(Request $request) {

        $check = DB::table('users')->where('email', '=', $request->user_email)->first();

        if (!$check) {
            $result['status'] = 'Error';
            $result['msg'] = 'User not found';
            $result['data'] = $request->all();

            return response()->json($result);
            exit;
        }

        $reference_no = $this->generateRandomString(5);

        $all_day = !empty($request->all_day) ? $request->all_day : 0;
        $free_event = !empty($request->free_event) ? $request->free_event : 0;
//        $kids_event = !empty($request->kids_event) ? $request->kids_event : 0;
//        $disable_event = !empty($request->disable_event) ? $request->disable_event : 0;
        $event_data = [
            'reference_no' => $reference_no,
            'type_id' => implode(',', $request->types),
            'category_id' => implode(',', $request->categories),
            'keyword' => $request->keywords,
            'phone' => $request->phone,
            'email' => $request->email,
            'weblink' => $request->website,
            'start_date' => $request->start_date_time,
            'end_date' => $request->end_date_time,
            'all_day' => $all_day,
            'free_event' => $free_event,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'event_language' => implode(',', $request->languages),
            'venue' => $request->venue,
            'share_count' => 0,
            'user_id' => $check->id,
            'created_at' => $this->current_date_time,
            'updated_at' => $this->current_date_time,
        ];
        
        if($request->lang == 'en'){
            $lang_fields = [
                'eng_name' => $request->event_name,
                'eng_company_name' => trim($request->company_name),
                'eng_description' => $request->description
            ];
        }
        else{
            $lang_fields = [
                'ar_name' => $request->event_name,
                'ar_company_name' => trim($request->company_name),
                'ar_description' => $request->description
            ];
        }
        
        $event_data = array_merge($event_data, $lang_fields);

        $id = DB::table('events')->insertGetId($event_data);
        if ($id) {

            $destinationPath = base_path() . '/public/uploads';

            // Pictures and attachments upload
            for ($i = 1; $i < 5; $i++) {

                if ($request->hasFile('picture_' . $i)) {
                    $file = $request->file('picture_' . $i);
                    $extension = $file->getClientOriginalExtension();
                    $picture[$i - 1] = uniqid() . '.' . $extension;
                    $file->move($destinationPath, $picture[$i - 1]);

                    $this->create_watermark($destinationPath . '/' . $picture[$i - 1], $destinationPath . '/' . $picture[$i - 1]);
                    $this->compressImage($destinationPath.'/'.$picture[$i - 1], base_path() . '/public/thumbnail/'.$picture[$i - 1], 20);
                    $pic_data[] = ['event_id' => $id, 'picture' => $picture[$i - 1]];
                }

                if ($request->hasFile('attachment_' . $i)) {
                    $file = $request->file('attachment_' . $i);
                    $extension = $file->getClientOriginalExtension();
                    $attachment[$i - 1] = uniqid() . '.' . $extension;
                    $file->move($destinationPath, $attachment[$i - 1]);

                    $this->create_watermark($destinationPath . '/' . $attachment[$i - 1], $destinationPath . '/' . $attachment[$i - 1]);
                    $this->compressImage($destinationPath.'/'.$attachment[$i - 1], base_path() . '/public/thumbnail/'.$attachment[$i - 1], 20);
                    $attch_data[] = ['event_id' => $id, 'picture' => $attachment[$i - 1]];
                }
            }

            if (isset($pic_data)) {
                DB::table('pictures')->insert($pic_data);
                $this->digitalOcean->table('pictures')->insert($pic_data);
            }
            if (isset($attch_data)) {
                DB::table('attachments')->insert($attch_data);
                $this->digitalOcean->table('attachments')->insert($attch_data);
            }

            if (isset($request->locations) || count($request->locations) > 0) {
                DB::table('locations')->where('event_id', $id)->delete();

                for ($loc = 0; $loc < count($request->locations); $loc++) {
                    $latlng = explode(',', $request->locations[$loc]['latlng']);
                    $loc_data[] = [
                        'event_id' => $id,
                        'city' => trim($request->locations[$loc]['city']),
                        'location' => trim($request->locations[$loc]['location']),
                        'latitude' => $latlng[0],
                        'longitude' => $latlng[1]
                    ];
                }
                DB::table('locations')->insert($loc_data);
                $this->digitalOcean->table('locations')->insert($loc_data);
            }

            $result['status'] = 'Success';
            $result['msg'] = $this->save_success;
        } else {
            $result['status'] = 'Error';
            $result['msg'] = $this->save_error;
        }
        return response()->json($result);
    }

    public function getEvents(Request $request) {

        $offset = ($request->offset > 0) ? $request->offset : 0;
        
        if (!empty($request->user_id)) {
            $user_language = DB::table('user_languages')->select(DB::raw('group_concat(language_id) as language'))->where('user_id', $request->user_id)->first();
            $user_category = DB::table('user_categories')->select(DB::raw('group_concat(category_id) as category'))->where('user_id', $request->user_id)->first();
            $user_kids_disabled = DB::table('users')->select('interested_in_kids','interested_in_disabled')->where('id', $request->user_id)->first();
                       
            $user_language = $user_language->language;
            $user_category = $user_category->category;
            $kids = $user_kids_disabled->interested_in_kids;
            $disabled = $user_kids_disabled->interested_in_disabled;
        }
        else{
            $user_language = '';
            $user_category = '';
            $kids = '';
            $disabled = '';
        }
        
//        echo '<pre>'; print_r($user_language); die;
        
        if (!empty($request->radius)) {
            $events = Event::getEventsByRadius($request->latLng, $request->radius, $request->type, $user_category, $user_language);
            $offset = -1;
        } else {
            $events = Event::getEvents($request->city, $request->type, $offset, false, $user_category, $user_language);
        }

        if (count($events) == 0 && $offset > 0 || !empty($request->radius)) {
            $offset = -1;
        } else {
            $offset = $offset + 5;
        }

        $featured_events = Event::getEvents($request->city, '', 0, true);
        $likes = Event::getMostLike();
        $shared = Event::getMostShared();

        if (count($events) > 0 || count($featured_events) > 0 || count($shared) > 0 || count($likes) > 0) {
            if (!empty($request->user_id)) {
                $user_favourites = DB::table('user_favourite_events')->where('user_id', $request->user_id)->get();
                foreach ($user_favourites as $key => $fav) {
                    $user_favs[] = $fav->event_id;
                }
            }

            foreach ($events as $key => $event) {
                $ids = $event->event_language;
                $lang = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
                $type = DB::table('types')->whereIn('id', explode(',', $event->type_id))->get();
                $category = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();
                if ($event->user_id != 0) {
                    $user = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();
                    $events[$key]->username = $user->username;
                    $events[$key]->user_email = $user->email;
                    $events[$key]->is_verified = $user->is_verified;
                    $events[$key]->user_picture = $user->image;
                } else {
                    $events[$key]->username = 'KHAIR KEYS';
                    $events[$key]->user_email = '';
                    $events[$key]->is_verified = '';
                    $events[$key]->user_picture = '';
                }

                if ($events[$key]->all_day == 1) {
                    $events[$key]->start_date = substr($events[$key]->start_date, 0, 10);
                    $events[$key]->end_date = substr($events[$key]->end_date, 0, 10);
                }

                if (isset($user_favs)) {
                    $events[$key]->is_favourite = (in_array($event->id, $user_favs)) ? 1 : 0;
                } else {
                    $events[$key]->is_favourite = 0;
                }

                $events[$key]->types = $type;
                $events[$key]->categories = $category;
                $events[$key]->languages = $lang;
            }

            foreach ($featured_events as $key => $event) {
                $ids = $event->event_language;
                $lang = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
                $type = DB::table('types')->whereIn('id', explode(',', $event->type_id))->get();
                $category = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();

                if ($event->user_id != 0) {
                    $user = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();
                    $featured_events[$key]->username = $user->username;
                    $featured_events[$key]->user_email = $user->email;
                    $featured_events[$key]->is_verified = $user->is_verified;
                    $featured_events[$key]->user_picture = $user->image;
                } else {

                    $featured_events[$key]->username = 'KHAIR KEYS';
                    $featured_events[$key]->user_email = '';
                    $featured_events[$key]->is_verified = '';
                    $featured_events[$key]->user_picture = '';
                }

                if ($featured_events[$key]->all_day == 1) {
                    $featured_events[$key]->start_date = substr($featured_events[$key]->start_date, 0, 10);
                    $featured_events[$key]->end_date = substr($featured_events[$key]->end_date, 0, 10);
                }

                if (isset($user_favs)) {
                    $featured_events[$key]->is_favourite = (in_array($event->id, $user_favs)) ? 1 : 0;
                } else {
                    $featured_events[$key]->is_favourite = 0;
                }

                $featured_events[$key]->types = $type;
                $featured_events[$key]->categories = $category;
                $featured_events[$key]->languages = $lang;
            }

            if (count($likes) > 0) {

                foreach ($likes as $key => $event) {
                    $ids = $event->event_language;
                    $lang = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
                    $type = DB::table('types')->whereIn('id', explode(',', $event->type_id))->get();
                    $category = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();

                    if ($event->user_id != 0) {
                        $user = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();
                        $likes[$key]->username = $user->username;
                        $likes[$key]->user_email = $user->email;
                        $likes[$key]->is_verified = $user->is_verified;
                        $likes[$key]->user_picture = $user->image;
                    } else {
                        $likes[$key]->username = 'KHAIR KEYS';
                        $likes[$key]->user_email = '';
                        $likes[$key]->is_verified = '';
                        $likes[$key]->user_picture = '';
                    }

                    if ($likes[$key]->all_day == 1) {
                        $likes[$key]->start_date = substr($likes[$key]->start_date, 0, 10);
                        $likes[$key]->end_date = substr($likes[$key]->end_date, 0, 10);
                    }

                    if (isset($user_favs)) {
                        $likes[$key]->is_favourite = (in_array($event->id, $user_favs)) ? 1 : 0;
                    } else {
                        $likes[$key]->is_favourite = 0;
                    }

                    $likes[$key]->types = $type;
                    $likes[$key]->categories = $category;
                    $likes[$key]->languages = $lang;
                }
            }

            if (count($shared) > 0) {
                foreach ($shared as $key => $event) {
                    $ids = $event->event_language;
                    $lang = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
                    $type = DB::table('types')->whereIn('id', explode(',', $event->type_id))->get();
                    $category = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();
                    if ($event->user_id != 0) {
                        $user = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();

                        $shared[$key]->username = $user->username;
                        $shared[$key]->user_email = $user->email;
                        $shared[$key]->is_verified = $user->is_verified;
                        $shared[$key]->user_picture = $user->image;
                    } else {

                        $shared[$key]->username = 'KHAIR KEYS';
                        $shared[$key]->user_email = '';
                        $shared[$key]->is_verified = '';
                        $shared[$key]->user_picture = '';
                    }

                    if ($shared[$key]->all_day == 1) {
                        $shared[$key]->start_date = substr($shared[$key]->start_date, 0, 10);
                        $shared[$key]->end_date = substr($shared[$key]->end_date, 0, 10);
                    }

                    if (isset($user_favs)) {
                        $shared[$key]->is_favourite = (in_array($event->id, $user_favs)) ? 1 : 0;
                    } else {
                        $shared[$key]->is_favourite = 0;
                    }

                    $shared[$key]->types = $type;
                    $shared[$key]->categories = $category;
                    $shared[$key]->languages = $lang;
                }
            }

            $result['status'] = 'Success';
            $result['msg'] = 'Events';
            $result['offset'] = $offset;
            $result['events'] = $events;
            $result['featured_events'] = $featured_events;
            $result['most_liked'] = $likes;
            $result['most_shared'] = $shared;
        } else {
            if ($offset > 0) {
                $result['status'] = 'Error';
                $result['msg'] = 'No more events';
            } else {
                $result['status'] = 'Error';
                $result['msg'] = 'No event found';
            }
        }

        return response()->json($result);
    }

    public function getUserEvents(Request $request) {

        $user = User::getUserDetails($request->user_id);

        if ($user) {
            $events = Event::getUserEvents($request->user_id);
            
            $user_id = !empty($request->loggedInUserId) ? $request->loggedInUserId : $request->user_id;

            if (count($events) > 0) {

                $user_favourites = DB::table('user_favourite_events')->where('user_id', $user_id)->get();
                foreach ($user_favourites as $key => $fav) {
                    $user_favs[] = $fav->event_id;
                }

                foreach ($events as $key => $event) {
                    $ids = $event->event_language;
                    $lang = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
                    $type = DB::table('types')->whereIn('id', explode(',', $event->type_id))->get();
                    $category = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();

                    $user = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();

                    $events[$key]->username = $user->username;
                    $events[$key]->user_email = $user->email;
                    $events[$key]->is_verified = $user->is_verified;
                    $events[$key]->user_picture = $user->image;

                    if (isset($user_favs)) {
                        $events[$key]->is_favourite = (in_array($event->id, $user_favs)) ? 1 : 0;
                    } else {
                        $events[$key]->is_favourite = 0;
                    }

                    $events[$key]->types = $type;
                    $events[$key]->categories = $category;
                    $events[$key]->languages = $lang;
                }

                $result['status'] = 'Success';
                $result['msg'] = 'Events';
                $result['events'] = $events;
            } else {
                $result['status'] = 'Error';
                $result['msg'] = 'No event found';
            }
        } else {
            $result['status'] = 'Error';
            $result['msg'] = 'User not found';
        }

        return response()->json($result);
    }

    public function getUserFavouriteEvents(Request $request) {

        $validator = Validator::make($request->all(), ['user_id' => 'required']);

        if ($validator->fails()) {
            $result['status'] = 'Error';
            $result['msg'] = 'Required fields must not be empty';
            $result['error'] = $validator->errors();

            return response()->json($result);
        }

        $events = Event::getFavouriteEvents($request->user_id);

        if (count($events) > 0) {

            foreach ($events as $key => $event) {
                $ids = $event->event_language;
                $lang = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
                $type = DB::table('types')->whereIn('id', explode(',', $event->type_id))->get();
                $category = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();
                if ($event->user_id != 0) {
                    $user = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();
                    $events[$key]->username = $user->username;
                    $events[$key]->user_email = $user->email;
                    $events[$key]->is_verified = $user->is_verified;
                    $events[$key]->user_picture = $user->image;
                } else {
                    $events[$key]->username = 'KHAIR KEYS';
                    $events[$key]->user_email = '';
                    $events[$key]->is_verified = '';
                    $events[$key]->user_picture = '';
                }

                $events[$key]->is_favourite = 1;
                $events[$key]->types = $type;
                $events[$key]->categories = $category;
                $events[$key]->languages = $lang;
            }

            $result['status'] = 'Success';
            $result['msg'] = 'Events';
            $result['events'] = $events;
        } else {
            $result['status'] = 'Error';
            $result['msg'] = 'No event found';
        }

        return response()->json($result);
    }

    public function saveFavourite(Request $request) {

        $validation_data = [
            'user_id' => 'required',
            'event_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            $result['status'] = 'Error';
            $result['msg'] = 'Required fields must not be empty';
            $result['error'] = $validator->errors();

            return response()->json($result);
        }

        $user_id = $request->user_id;
        $check = DB::table('users')->where('id', '=', $user_id)->first();
        if (!$check) {
            $result['status'] = 'Error';
            $result['msg'] = 'User not found';

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
                $result['msg'] = 'Succcessfully deleted';
            } else {
                $result['status'] = 'Error';
                $result['msg'] = 'Could not be deleted';
            }
        } else {
            $like_check = DB::table('user_favourite_events')->where('user_id', '=', $user_id)->where('event_id', '=', $request->event_id)->first();
            if (!$like_check) {
                $res = DB::table('user_favourite_events')->insertGetId($data);

                if ($res > 0) {
                    $result['status'] = 'Success';
                    $result['msg'] = $this->save_success;
                } else {
                    $result['status'] = 'Error';
                    $result['msg'] = $this->save_error;
                }
            } else {
                $result['status'] = 'Success';
                $result['msg'] = $this->save_success;
            }
        }

        return response()->json($result);
    }

    public function eventShare(Request $request) {

        $validation_data = [
            'event_id' => 'required',
            'share_count' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            $result['status'] = 'Error';
            $result['msg'] = 'Required fields must not be empty';
            $result['error'] = $validator->errors();

            return response()->json($result);
        }

        $res = DB::table('events')->where('id', '=', $request->event_id)->update(['share_count' => $request->share_count + 1]);

        if ($res == 1) {
            $result['status'] = 'Success';
            $result['msg'] = $this->update_success;
        } else {
            $result['status'] = 'Error';
            $result['msg'] = $this->update_error;
        }

        return response()->json($result);
    }

    public function getCompanyVerifiedUsers() {

        $companies = DB::table('events')->select('eng_company_name', 'ar_company_name')->where('status', '=', 'Active')->groupBy('eng_company_name', 'ar_company_name')->orderBy('eng_company_name')->get();
        $users = DB::table('users')->select('id','username')->where('is_verified', '=', 1)->orderBy('username')->get();

        if (count($users) > 0 || count($companies) > 0) {
            $result['status'] = 'Success';
            $result['msg'] = 'Companies, Verified users';
            $result['companies'] = $companies;
            $result['users'] = $users;
        } else {
            $result['status'] = 'Error';
            $result['msg'] = 'No record found';
        }

        return response()->json($result);
    }

    public function searchEvent(Request $request) {
               
        $data = [
            'eng_company' => $request->eng_company_name,
            'ar_company' => $request->ar_company_name,
            'category' => $request->category,
            'type' => $request->type,
            'language' => $request->language,
            'city' => $request->city,
            'verified_user' => $request->verified_user,
            'keyword' => $request->keyword,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'venue' => $request->venue,
            'is_kids' => $request->is_kids,
            'is_disabled' => $request->is_disabled,
            'is_free' => $request->is_free,
            'is_paid' => $request->is_paid,
        ];

        $events = Event::getAppSearchEvent($data);

        if (count($events) > 0) {
            if (!empty($request->user_id)) {
                $user_favourites = DB::table('user_favourite_events')->where('user_id', $request->user_id)->get();
                foreach ($user_favourites as $key => $fav) {
                    $user_favs[] = $fav->event_id;
                }
            }

            foreach ($events as $key => $event) {
                $ids = $event->event_language;
                $lang = DB::table('languages')->select('id', 'title')->whereIn('id', explode(',', $ids))->get();
                $type = DB::table('types')->whereIn('id', explode(',', $event->type_id))->get();
                $category = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();
                if ($event->user_id != 0) {
                    $user = DB::table('users')->select('username', 'email', 'is_verified', 'image')->where('id', $event->user_id)->first();
                    $events[$key]->username = $user->username;
                    $events[$key]->user_email = $user->email;
                    $events[$key]->is_verified = $user->is_verified;
                    $events[$key]->user_picture = $user->image;
                } else {
                    $events[$key]->username = 'KHAIR KEYS';
                    $events[$key]->user_email = '';
                    $events[$key]->is_verified = '';
                    $events[$key]->user_picture = '';
                }

                if ($events[$key]->all_day == 1) {
                    $events[$key]->start_date = substr($events[$key]->start_date, 0, 10);
                    $events[$key]->end_date = substr($events[$key]->end_date, 0, 10);
                }

                if (isset($user_favs)) {
                    $events[$key]->is_favourite = (in_array($event->id, $user_favs)) ? 1 : 0;
                } else {
                    $events[$key]->is_favourite = 0;
                }

                $events[$key]->types = $type;
                $events[$key]->categories = $category;
                $events[$key]->languages = $lang;
            }

            $result['status'] = 'Success';
            $result['msg'] = 'Events';
            $result['events'] = $events;
        } else {
            $result['status'] = 'Error';
            $result['msg'] = 'No event found';
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
        $overlay_gd_image = imagecreatefrompng(base_path() . '/public/logo.png');
        $overlay_width = imagesx($overlay_gd_image);
        $overlay_height = imagesy($overlay_gd_image);
        imagecopymerge(
                $source_gd_image, $overlay_gd_image, $source_width - $overlay_width, $source_height - $overlay_height, 0, 0, $overlay_width, $overlay_height, 10
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
                'gender' => 'required',
                'dob' => 'required',
                'password' => 'required',
            ];
            // 'email'    => 'required|email|max:100'
            $validation_data = array_merge($password_validate, $validation_data);

            $social = 0;
            $status = 0;
        } else {
            /* $email_validate  = ['email' => 'required|email|max:100'];
              $validation_data = array_merge($email_validate, $validation_data); */

            $social = 1;
            $status = 1;
        }

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            $result['status'] = 'Error';
            $result['msg'] = 'Required fields must not be empty';
            $result['error'] = $validator->errors();

            return response()->json($result);
        }

        $email = trim($request->input('email'));
        $username = trim($request->input('username'));

        /* check email already exists or not */
        if ($social == 0) {
            $check = DB::table('users')->where('email', '=', $email)->first();
            if ($check) {
                $result['status'] = 'Error';
                $result['msg'] = 'Email already exists';

                return response()->json($result);
            }
        } else if ($social == 1) {
            if(empty($email)){
                $check = DB::table('users')->where('username', '=', $username)->first();
            }
            else{
                $check = DB::table('users')->where('email', '=', $email)->first();
            }
            if ($check) {
                $user = User::getUserDetails($check->id);
                $user->categories = User::getUserCategories($user->id);
                $user->languages = DB::table('user_languages')
                        ->select('languages.id', 'title')
                        ->join('languages', 'user_languages.language_id', '=', 'languages.id')
                        ->where('user_id', '=', $check->id)
                        ->get();
                $user->image_path = url('public/uploads');
                
                if (!empty($request->token)) {
                    if (!empty($request->device_id)) {
                        $this->saveAndroidToken($request->token, $request->device_id);
                    } else {
                        $this->saveToken($request->token);
                    }
                    DB::table('tokens')->where('token', $request->token)->update(['user_id' => $check->id, 'updated_at' => $this->current_date_time]);
                }
                
                $result['status'] = 'Success';
                $result['msg'] = $this->save_success;
                $result['profile'] = $user;

                return response()->json($result);
            }
        }

        $password = trim($request->input('password'));
        $gender = trim($request->input('gender'));
        $dob = trim($request->input('dob'));
        $remember_token = uniqid();

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $extension = $file->getClientOriginalExtension();
            $picture = uniqid() . '.' . $extension;
            $destinationPath = base_path() . '/public/uploads';
            $file->move($destinationPath, $picture);

//            $result = $this->create_watermark($destinationPath . '/' . $picture, $destinationPath . '/' . $picture);
        } else {
            $picture = 'default.png';
        }

        $user_data = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'gender' => $gender,
            'dob' => $dob,
            'image' => $picture,
            'remember_token' => $remember_token,
            'status' => $status,
            'is_social' => $social,
            'created_at' => $this->current_date_time,
            'updated_at' => $this->current_date_time,
        ];
        $id = DB::table('users')->insertGetId($user_data);

        if ($id) {
            if ($social == 0) {
                $msg = "Dear " . ucwords($username) . ",<br/><br/>Please click the given link to activate your account<br/>
                <a href='" . url('/user/verification/' . $remember_token) . "'>Click Here</a>";
                $this->new_send_email($email, 'KK Events - Account Activation', $msg);
//                $send = $this->send_email($email, 'KK Events', 'Account Activation', $msg);
            }

            if (!empty($request->token)) {
                if (!empty($request->device_id)) {
                    $this->saveAndroidToken($request->token, $request->device_id);
                } else {
                    $this->saveToken($request->token);
                }
            }

            $result['status'] = 'Success';
            $result['msg'] = $this->save_success;
            $user = User::getUserDetails($id);
            $user->categories = User::getUserCategories($user->id);
            $user->languages = DB::table('user_languages')
                    ->select('languages.id', 'title')
                    ->join('languages', 'user_languages.language_id', '=', 'languages.id')
                    ->where('user_id', '=', $id)
                    ->get();
            $user->image_path = url('public/uploads');

            $result['profile'] = $user;
        } else {
            $result['status'] = 'Error';
            $result['msg'] = $this->save_error;
        }

        return response()->json($result);
    }

    public function updateProfile(Request $request) {

        $validator = Validator::make($request->all(), [
                    'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = 'Error';
            $result['msg'] = 'Required fields must not be empty';
            $result['error'] = $validator->errors();

            return response()->json($result);
        }

        $user_id = $request->user_id;
        $username = trim($request->input('username'));
        $password = trim($request->input('password'));
        $gender = trim($request->input('gender'));
        $dob = trim($request->input('dob'));
        $remember_token = uniqid();

        $user_data = [
            'username' => $username,
            'password' => $password,
            'gender' => $gender,
            'dob' => $dob,
            'updated_at' => $this->current_date_time,
        ];

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $extension = $file->getClientOriginalExtension();
            $picture = uniqid() . '.' . $extension;
            $destinationPath = base_path() . '/public/uploads';
            $file->move($destinationPath, $picture);

            $result = $this->create_watermark($destinationPath . '/' . $picture, $destinationPath . '/' . $picture);

            $image_data = ['image' => $picture];
            $user_data = array_merge($user_data, $image_data);
        }

        $res = DB::table('users')->where('id', '=', $user_id)->update($user_data);

        if ($res == 1 || $res == 0) {

            $result['status'] = 'Success';
            $result['msg'] = $this->update_success;
            $user = User::getUserDetails($user_id);
            $user->categories = User::getUserCategories($user_id);
            $user->languages = DB::table('user_languages')
                    ->select('languages.id', 'title')
                    ->join('languages', 'user_languages.language_id', '=', 'languages.id')
                    ->where('user_id', '=', $user_id)
                    ->get();
            $user->image_path = url('public/uploads');

            $result['profile'] = $user;
        } else {
            $result['status'] = 'Error';
            $result['msg'] = $this->update_error;
        }

        return response()->json($result);
    }

    public function userVerify(Request $request) {
        
        $token = $request->segment(3);
        if ($token) {
            $check = $this->checkRecord('users', ['remember_token' => $token]);
            if ($check) {
                $res = $this->updateRecord('users', ['remember_token' => $token], [
                    'remember_token' => uniqid(), 'status' => 1,
                ]);

                if ($res) {
//                    die('Account Successfully verified');
                    Session::flash('success', 'Account Successfully verified');
                    return view('thank-you');
                } else {
//                    die('Could not be activated');
                    Session::flash('error', 'Could not be activated');
                    return view('thank-you');
                }
            } else {
                die('Invalid Request');
            }
        }
    }

    public function corporateRegister(Request $request) {

        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'company_name' => 'required',
                    'email' => 'required|email',
                    'phone' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = 'Error';
            $result['msg'] = 'Required fields must not be empty';
            $result['error'] = $validator->errors();

            return response()->json($result);
        }

        $msg = '<b>Name</b>: ' . $request->name . '
		<br><b>Company Name</b>: ' . $request->company_name . '
		<br><b>Email</b>: ' . $request->email . '
		<br><b>Company Name</b>: ' . $request->phone . '
		<br><b>Notes</b>: ' . $request->notes;

        $this->new_send_email('info@khairkeys.com', 'KK Events - Corporate User', $msg);
//        $send = $this->send_email('hafizmabuzar@synergistics.pk', 'KK Events', 'Corporate User', $msg, $request->email);

        $result['status'] = 'Success';
        $result['msg'] = 'Successfully Sent';

        return response()->json($result);
    }

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
                    'email' => 'required',
                    'password' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = 'Error';
            $result['msg'] = 'Required fields must not be empty';
            $result['error'] = $validator->errors();

            return response()->json($result);
        }

        $email = trim($request->input('email'));
        $password = trim($request->input('password'));

        // params => id, email, password
        $user = User::getUserDetails(null, $email, $password);

        if ($user) {
            if ($user->status == 0) {
                $result['status'] = 'Pending';
                $result['msg'] = 'Account pending';
            } else {
                $user->categories = User::getUserCategories($user->id);
                $user->languages = DB::table('user_languages')
                        ->select('languages.id', 'title')
                        ->join('languages', 'user_languages.language_id', '=', 'languages.id')
                        ->where('user_id', '=', $user->id)
                        ->get();
                $user->image_path = url('public/uploads');

                $user->categories = User::getUserCategories($user->id);
                $user->image_path = url('public/uploads');

                if (!empty($request->token)) {
                    DB::table('tokens')->where('token', $request->token)->update(['user_id' => $user->id, 'updated_at' => $this->current_date_time]);
                }

                $result['status'] = 'Success';
                $result['msg'] = 'User found';
                $result['profile'] = $user;
            }
        } else {
            $result['status'] = 'Error';
            $result['msg'] = 'Invalid Credentials';
        }

        return response()->json($result);
    }

    public function logout(Request $request) {

        if (!empty($request->token)) {
            DB::table('tokens')->where('token', $request->token)->update(['user_id' => '', 'updated_at' => $this->current_date_time]);
        }

        $result['status'] = 'Success';
        $result['msg'] = 'Successfully LoggedOut';

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
                $result['msg'] = 'Social Account';
            } else {
                $new_password = $this->generateRandomString(8);
                $res = DB::table('users')->where('email', '=', $email)->update(['password' => $new_password]);
                if ($res == 1) {
                    $msg = "Dear " . ucwords($check->username) . ",<br/><br/> Your new Passord: $new_password";
                    $this->new_send_email($email, 'KK Events - Forgot Password', $msg);

//                    $send = $this->send_email($email, 'KK Events', 'Reset Password', $msg);

                    $result['status'] = 'Success';
                    $result['msg'] = 'Email sent';
                } else {
                    $result['status'] = 'Error';
                    $result['msg'] = 'Could not be reset';
                }
            }
        } else {
            $result['status'] = 'Error';
            $result['msg'] = 'Email not found';
        }

        return response()->json($result);
    }

    public function saveUserPreferred(Request $request) {

        $validator = Validator::make($request->all(), [
                    'categories' => 'required',
                    'languages' => 'required',
                    'kids' => 'required',
                    'disability' => 'required',
//                    'email' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = 'Error';
            $result['msg'] = 'Required fields must not be empty';
            $result['error'] = $validator->errors();

            return response()->json($result);
        }

        $user_id = $request->user_id;
        if (!empty($user_id)) {
            $check = $this->checkRecord('users', ['id' => $user_id]);
        } else {
            $email = $request->email;
            $check = $this->checkRecord('users', ['email' => $email]);
        }
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
                'interested_in_kids' => $request->kids,
                'interested_in_disabled' => $request->disability
            ]);

            if ($res == 1) {
                $user = User::getUserDetails($check[0]->id);
                $user->categories = User::getUserCategories($check[0]->id);
                $user->languages = DB::table('user_languages')
                        ->select('languages.id', 'title')
                        ->join('languages', 'user_languages.language_id', '=', 'languages.id')
                        ->where('user_id', '=', $check[0]->id)
                        ->get();
                $user->image_path = url('public/uploads');

                $result['status'] = 'Success';
                $result['msg'] = $this->save_success;
                $result['profile'] = $user;
            } else {
                $result['status'] = 'Error';
                $result['msg'] = $this->save_error;
            }
        } else {
            $result['status'] = 'Error';
            $result['msg'] = 'User not found';
        }

        return response()->json($result);
    }

    public function saveToken($token = '') {

        $token = !empty($token) ? $token : $_REQUEST['token'];

        if (empty($token)) {
            $result['status'] = 'Error';
            $result['msg'] = 'Token required';
        } else {
            $check = $this->checkRecord('tokens', array('token' => $token));
            if (!$check) {
                $fields = array(
                    'app_id' => "81c54624-340c-4ccd-907e-8fade9cbccb9",
                    'identifier' => $token,
                    'language' => "en",
                    'timezone' => "-28800",
                    'game_version' => "1.0",
                    'device_os' => "",
                    'device_type' => "0",
                    'device_model' => "iPhone",
                    'test_type' => 2,
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

                $res = DB::table('tokens')->insertGetId([
                    'token' => $token,
                    'object_id' => $response->id,
                    'created_at' => $this->current_date_time,
                    'updated_at' => $this->current_date_time,
                ]);

                if ($res) {
                    $result['status'] = 'Success';
                    $result['msg'] = $this->save_success;
                } else {
                    $result['status'] = 'Error';
                    $result['msg'] = $this->save_error;
                }
            } else {
                $result['status'] = 'Success';
                $result['msg'] = 'Token saved';
            }
        }

        return $result;
    }

    public function saveAndroidToken($token = null, $device_id = null) {

        $token = (!empty($token)) ? $token : $_REQUEST['token'];
        $device_id = (!empty($device_id)) ? $device_id : $_REQUEST['device_id'];

        if (empty($token) || empty($device_id)) {
            $result['status'] = 'Error';
            $result['msg'] = 'Required fields must not be empty';

            return response()->json($result);
        }

        $token_data = array(
            'token' => $token,
            'device_id' => $device_id,
            'created_at' => $this->current_date_time,
            'updated_at' => $this->current_date_time,
        );

        $check = $this->checkRecord('tokens', array('device_id' => $device_id));
        if ($check) {
            $this->updateRecord('tokens', ['device_id' => $device_id], array('token' => $token));
            $result['status'] = 'Success';
            $result['msg'] = $this->update_success;
        } else {
            $res = DB::table('tokens')->insertGetId($token_data);
            if ($res) {
                $result['status'] = 'Success';
                $result['msg'] = $this->save_success;
            } else {
                $result['status'] = 'Error';
                $result['msg'] = $this->save_error;
            }
        }

        return response()->json($result);
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
        $email = (!empty($email)) ? "and user_email = '" . $email . "'" : '';

        return $query = DB::table(DB::raw("select token from tokens where device_id <> '' $email"))->get();
    }

    public function send_email($to, $from = '', $subject, $msg, $from_email = null) {

        $mail = new \PHPMailer(true);

        $mail->SMTPDebug = 0; // Enable verbose debug output

        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'ssl://smtp.googlemail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'hamzasynergistics@gmail.com'; // SMTP username
        $mail->Password = 'synergistics'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;

        // live
        /* $mail->isSMTP();                                      // Set mailer to use SMTP
          $mail->Host = 'secureuk6.sgcpanel.com';                // Specify main and backup SMTP servers
          $mail->SMTPAuth = true;                               // Enable SMTP authentication
          $mail->Username = 'support@alphabeta.ae';                 // SMTP username
          $mail->Password = 'support123@';                         // SMTP password
          $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
          $mail->Port = 465; */// TCP port to connect to

        $mail->setFrom('do-not-reply@kkevents.ae', $from);
        $mail->addAddress($to, 'KK APP'); // Add a recipient
        if (!empty($from_email)) {
            $mail->addReplyTo($from_email, '');
        } else {
            $mail->addReplyTo('do-not-reply@kkevents.ae', '');
        }

        $mail->isHTML(true); // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body = $msg;
        $mail->AltBody = 'Welcome to KK App';

        if (!$mail->send()) {
            return 0;
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 1;
        }
    }

    protected function new_send_email($to, $subject, $msg) {

        $headers = 'From: do-not-reply@khiarkeys.com' . "\r\n" .
                'Reply-To: do-not-reply@khiarkeys.com'."\r\n" .
                'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $msg, $headers);
    }
    
    
    function save_test_iOS(){
        
        $fields = array(
            'app_id' => "81c54624-340c-4ccd-907e-8fade9cbccb9",
            'identifier' => $_GET['token'],
            'language' => "en",
            'timezone' => "-28800",
            'game_version' => "1.0",
            'device_os' => "",
            'device_type' => "0",
            'device_model' => "iPhone",
            'test_type' => 2,
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

        echo json_encode($response);
        
    }
    
    public function test_ios_notification() {

        $title = array(
            "en" => $_GET['title'],
        );
        $content = array(
            "en" => $_GET['msg'],
        );

        $fields = array(
            'app_id' => "81c54624-340c-4ccd-907e-8fade9cbccb9",
            'include_player_ids' => ["f84dbba7-fda7-4bdd-afaf-54e987b7d4ba"],
            'contents' => $content,
            'heading' => $title,
            'data' => ['title' => $_GET['title'], 'body' => $_GET['msg']],
            'ios_badgeType' => 'SetTo',
            'ios_badgeCount' => 1,
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic ZmYwY2NmYWYtMDYyZC00ODBhLWJhYmQtNjVhYTFiYTY5NWZl'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }
    
    public function test_android_push() {
        
//        foreach ($chunks as $chk) {
            $registrationIds[] = $_GET['token'];
            define('API_ACCESS_KEY', 'AIzaSyA7qEzOETYNfXWADnLq_heXFXoSY25nr-k');

            $msg['notification'] = array
                (
                'title' => $_GET['title'],
                'message' => $_GET['msg'],
            );

            $fields = array
                (
                'registration_ids' => $registrationIds,
                'data' => $msg,
            );

            $headers = array
                (
                'Authorization: key=' . API_ACCESS_KEY,
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
//        }
    }
       
    
    function compressImage($source, $destination, $quality){
                
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/jpg')
            $image = imagecreatefromjpg($source);
        
        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);
        

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);

        imagejpeg($image, $destination, $quality);
        
        return $destination;
    }
    
    
    

    /*
      ---------------------------------------
      End common queries functions
      ---------------------------------------
     */
}
