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
        $this->digitalOcean = DB::connection('mysqlDO');

        $this->current_date_time = Carbon::now('Asia/Dubai');
    }

    public function home() {       

        if(isset($_GET['lang']) && $_GET['lang'] == 'EN'){
            return view('index');
        }
        return view('index-ar');
    }

    public function index(Request $request) {
        
        if ($request->isMethod('get')) {

            if (Session::has('admin_data')) {
                return redirect('admin/view-admin-events');
            }

            return view('admin.login');
        }

        if ($request->email == 'admin' && $request->password == 'KhairKeys321@123') {
//        if ($request->email == 'admin' && $request->password == 'admin') {
            Session::put('admin_data', 'loggedIn');
            return redirect('admin/view-admin-events');
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
            'arabic_category' => 'required',
            'english_category' => 'required',
            'non_selected_icon' => 'required',
            'selected_icon' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // selected icon
        $file = $request->file('selected_icon');
        $extension = $file->getClientOriginalExtension();
        $picture = uniqid() . '.' . $extension;
        $destinationPath = base_path() . '/public/admin/icons';
        $file->move($destinationPath, $picture);

        // non selected icon
        $file2 = $request->file('non_selected_icon');
        $picture2 = 'non-' . $picture;
        $destinationPath = base_path() . '/public/admin/icons';
        $file2->move($destinationPath, $picture2);

        $category_data = [
            'arabic' => $request->arabic_category,
            'english' => $request->english_category,
            'non_selected_icon' => $picture2,
            'selected_icon' => $picture,
            'created_at' => $this->current_date_time,
            'updated_at' => $this->current_date_time,
        ];

        $id = DB::table('categories')->insertGetId($category_data);
        $this->digitalOcean->table('categories')->insertGetId($category_data);

        if ($id) {
            Session::flash('success', 'Type successfully added');

            return redirect('admin/view-categories');
        }

        Session::flash('error', 'Type could not be added');

        return redirect()->back();
    }

    public function viewCategories() {

        $result['categories'] = DB::table('categories')->orderBy('id', 'DESC')->paginate(15);
        $result['sr'] = ($result['categories']->currentPage() > 1) ? ($result['categories']->currentPage()-1)*($result['categories']->perPage())+1 : 1;
        
        return view('admin.view-categories', $result);
    }

    public function updateCategory(Request $request) {

        if ($request->isMethod('get')) {

            $id = Crypt::decrypt($request->segment(3));

            $result['category'] = DB::table('categories')->where('id', '=', $id)->first();

            return view('admin.edit-category', $result);
        }

        $validation_data = [
            'arabic_category' => 'required',
            'english_category' => 'required',
        ];

        if (empty($request->old_icon)) {
            $icon = ['selected_icon' => 'required'];
            $validation_data = array_merge($icon, $validation_data);
        }
        if (empty($request->old_icon_non)) {
            $non_icon = ['non_selected_icon' => 'required'];
            $validation_data = array_merge($non_icon, $validation_data);
        }

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('selected_icon')) {
            $file = $request->file('selected_icon');
            $extension = $file->getClientOriginalExtension();
            $picture = uniqid() . '.' . $extension;
            $destinationPath = base_path() . '/public/admin/icons';
            $file->move($destinationPath, $picture);
        } else {
            $picture = $request->old_icon;
        }
        if ($request->hasFile('non_selected_icon')) {
            $file2 = $request->file('non_selected_icon');
            $picture2 = 'non_' . $picture;
            $destinationPath = base_path() . '/public/admin/icons';
            $file2->move($destinationPath, $picture2);
        } else {
            $picture2 = $request->old_icon_non;
        }

        $category_data = [
            'arabic' => $request->arabic_category,
            'english' => $request->english_category,
            'selected_icon' => $picture,
            'non_selected_icon' => $picture2,
            'updated_at' => $this->current_date_time,
        ];

        $id = Crypt::decrypt($request->cat_id);

        $res = DB::table('categories')->where('id', '=', $id)->update($category_data);
        $this->digitalOcean->table('categories')->where('id', '=', $id)->update($category_data);

        if ($res > 0) {
            Session::flash('success', 'Category successfully updated');

            return redirect('admin/view-categories');
        }

        Session::flash('error', 'Category could not be updated');

        return redirect()->back();
    }

    public function deleteCategory(Request $request) {

        $id = Crypt::decrypt($request->segment(3));
        $category = DB::table('categories')->where('id', '=', $id)->first();
        unlink(base_path() . '/public/admin/icons/' . $category->selected_icon);
        unlink(base_path() . '/public/admin/icons/non-' . $category->selected_icon);

        $res = DB::table('categories')->where('id', '=', $id)->delete();
        $this->digitalOcean->table('categories')->where('id', '=', $id)->delete();

        if ($res == 1) {
            Session::flash('success', 'Category successfully deleted');
        } else {
            Session::flash('error', 'Category could not be deleted');
        }

        return redirect('admin/view-categories');
    }

    /* ---------- Category CRUD End ---------- */

    /* Type CRUD start */

    public function addType(Request $request) {

        if ($request->isMethod('get')) {
            return view('admin.add-type');
        }

        $validation_data = [
            'arabic_type' => 'required',
            'english_type' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $type_data = [
            'arabic' => $request->arabic_type,
            'english' => $request->english_type,
            'created_at' => $this->current_date_time,
            'updated_at' => $this->current_date_time,
        ];

        $id = DB::table('types')->insertGetId($type_data);
        $this->digitalOcean->table('types')->insertGetId($type_data);

        if ($id) {
            Session::flash('success', 'Type successfully added');

            return redirect('admin/view-types');
        }

        Session::flash('error', 'Type could not be added');

        return redirect()->back();
    }

    public function viewTypes() {

        $result['types'] = DB::table('types')->orderBy('id', 'DESC')->paginate(15);
        $result['sr'] = ($result['types']->currentPage() > 1) ? ($result['types']->currentPage()-1)*($result['types']->perPage())+1 : 1;
        
        return view('admin.view-types', $result);
    }

    public function updateType(Request $request) {

        if ($request->isMethod('get')) {

            $id = Crypt::decrypt($request->segment(3));
            $result['type'] = DB::table('types')->where('id', '=', $id)->first();

            return view('admin.edit-type', $result);
        }

        $validation_data = [
            'arabic_type' => 'required',
            'english_type' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $type_data = [
            'arabic' => $request->arabic_type,
            'english' => $request->english_type,
            'updated_at' => $this->current_date_time,
        ];

        $id = Crypt::decrypt($request->type_id);

        $res = DB::table('types')->where('id', '=', $id)->update($type_data);
        $this->digitalOcean->table('types')->where('id', '=', $id)->update($type_data);

        if ($res > 0) {
            Session::flash('success', 'Type successfully updated');

            return redirect('admin/view-types');
        }

        Session::flash('error', 'Type could not be updated');

        return redirect()->back();
    }

    public function deleteType(Request $request) {

        $id = Crypt::decrypt($request->segment(3));
        $res = DB::table('types')->where('id', '=', $id)->delete();
        $this->digitalOcean->table('types')->where('id', '=', $id)->delete();

        if ($res == 1) {
            Session::flash('success', 'Type successfully deleted');
        } else {
            Session::flash('error', 'Type could not be deleted');
        }

        return redirect('admin/view-types');
    }

    /* ---------- Type CRUD End ---------- */

    /* Language CRUD start */

    public function addLanguage(Request $request) {

        if ($request->isMethod('get')) {
            return view('admin.add-language');
        }

        $validation_data = [
            'language' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $lang_data = [
            'title' => $request->language,
            'created_at' => $this->current_date_time,
            'updated_at' => $this->current_date_time,
        ];

        $id = DB::table('languages')->insertGetId($lang_data);
        $this->digitalOcean->table('languages')->insertGetId($lang_data);

        if ($id) {
            Session::flash('success', 'Language successfully added');

            return redirect('admin/view-languages');
        }

        Session::flash('error', 'Language could not be added');

        return redirect()->back();
    }

    public function viewLanguages() {

        $result['languages'] = DB::table('languages')->orderBy('title', 'ASC')->paginate(15);
        $result['sr'] = ($result['languages']->currentPage() > 1) ? ($result['languages']->currentPage()-1)*($result['languages']->perPage())+1 : 1;
        
        return view('admin.view-languages', $result);
    }

    public function updateLanguage(Request $request) {

        if ($request->isMethod('get')) {

            $id = Crypt::decrypt($request->segment(3));
            $result['language'] = DB::table('languages')->where('id', '=', $id)->first();

            return view('admin.edit-language', $result);
        }

        $validation_data = [
            'language' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $language_data = [
            'title' => $request->language,
            'updated_at' => $this->current_date_time,
        ];

        $id = Crypt::decrypt($request->language_id);

        $res = DB::table('languages')->where('id', '=', $id)->update($language_data);
        $this->digitalOcean->table('languages')->where('id', '=', $id)->update($language_data);

        if ($res > 0) {
            Session::flash('success', 'Language successfully updated');

            return redirect('admin/view-languages');
        }

        Session::flash('error', 'Language could not be updated');

        return redirect()->back();
    }

    public function deletelanguage(Request $request) {

        $id = Crypt::decrypt($request->segment(3));
        $res = DB::table('languages')->where('id', '=', $id)->delete();
        $this->digitalOcean->table('languages')->where('id', '=', $id)->delete();

        if ($res == 1) {
            Session::flash('success', 'Language successfully deleted');
        } else {
            Session::flash('error', 'Language could not be deleted');
        }

        return redirect('admin/view-languages');
    }

    /* ---------- Language CRUD End ---------- */

    /* User CRUD start */

    public function AddUser(Request $request) {

        if ($request->isMethod('get')) {
            return view('admin.add-user');
        }

        $validation_data = [
            'username' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required',
            'gender' => 'required',
            'dob' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $file = $request->file('picture');
        $extension = $file->getClientOriginalExtension();
        $picture = uniqid() . '.' . $extension;
        $destinationPath = base_path() . '/public/uploads';
        $file->move($destinationPath, $picture);

        $remember_token = uniqid();

        $user_data = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'image' => $picture,
            'remember_token' => $remember_token,
            'status' => 1,
            'is_verified' => 1,
            'created_at' => $this->current_date_time,
            'updated_at' => $this->current_date_time,
        ];

        $id = DB::table('users')->insertGetId($user_data);
        $this->digitalOcean->table('users')->insertGetId($user_data);

        if ($id) {
            Session::flash('success', 'User successfully added');

            return redirect('admin/view-verified-users');
        }

        Session::flash('error', 'User could not be added');

        return redirect()->back();
    }

    public function updateUser(Request $request) {

        if ($request->isMethod('get')) {

            $id = Crypt::decrypt($request->segment(3));
            $result['user'] = DB::table('users')->where('id', '=', $id)->first();

            return view('admin.edit-user', $result);
        }

        $validation_data = [
            'username' => 'required',
            'gender' => 'required',
        ];

        if (empty($request->old_picture)) {
            $picture = ['picture' => 'required'];
            $validation_data = array_merge($picture, $validation_data);
        }

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $id = Crypt::decrypt($request->user_id);

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $extension = $file->getClientOriginalExtension();
            $picture = uniqid() . '.' . $extension;
            $destinationPath = base_path() . '/public/uploads';
            $file->move($destinationPath, $picture);
        } else {
            $picture = $request->old_picture;
        }

        $user_data = [
            'username' => $request->username,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'image' => $picture,
            'is_verified' => $request->verified,
            'updated_at' => $this->current_date_time,
        ];

        $res = DB::table('users')->where('id', '=', $id)->update($user_data);
        $this->digitalOcean->table('users')->where('id', '=', $id)->update($user_data);

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

        $result['users'] = DB::table('users')->select('*', DB::raw('(
            CASE
            WHEN status = 0 THEN "Pending"
            WHEN status = 1 THEN "Active"
            ELSE "Deleted" END) AS status, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age')
                )->where('is_verified', '=', 0)->orderBy('username', 'asc')->paginate(15);
                
        $result['sr'] = ($result['users']->currentPage() > 1) ? ($result['users']->currentPage()-1)*($result['users']->perPage())+1 : 1;
        $result['uri_segment'] = 'view';
        
        return view('admin.view-users', $result);
    }

    public function viewVerifiedUsers() {

        $result['users'] = DB::table('users')->select('*', DB::raw('(
            CASE
            WHEN status = 0 THEN "Pending"
            WHEN status = 1 THEN "Active"
            ELSE "Deleted" END) AS status, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age')
                )->where('is_verified', '=', 1)->orderBy('username', 'asc')->paginate(15);
                
        $result['sr'] = ($result['users']->currentPage() > 1) ? ($result['users']->currentPage()-1)*($result['users']->perPage())+1 : 1;
        $result['uri_segment'] = 'search';
               
        return view('admin.view-verified-users', $result);
    }

    public function userStatus(Request $request) {

        $id = Crypt::decrypt($request->id);
        $status = ($request->status == 'Active') ? 1 : 2;
        $res = DB::table('users')->where('id', '=', $id)->update(['status' => $status]);
        $this->digitalOcean->table('users')->where('id', '=', $id)->update(['status' => $status]);

        if ($res == 1) {
            echo '1';
        } else {
            echo 'Error';
        }
    }
    
    public function searchUsers(Request $request) {

        $search = $request->search;

        if (empty($search)) {
            return redirect('admin/view-users');
        }

        $result['users'] = DB::select("select *,
            (CASE
            WHEN status = 0 THEN 'Pending'
            WHEN status = 1 THEN 'Active'
            ELSE 'Deleted' END) AS status, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age
            from users 
            where (username like '%$search%' or email like '%$search%' or gender like '%$search%')
            and is_verified = 0
            ");
               
        $result['sr'] = 1;
        $result['uri_segment'] = 'search';

        return view('admin.view-users', $result);
    }
    
    public function searchVerifiedUsers(Request $request) {

        $search = $request->search;

        if (empty($search)) {
            return redirect('admin/view-users');
        }

        $result['users'] = DB::select("select *,
            (CASE
            WHEN status = 0 THEN 'Pending'
            WHEN status = 1 THEN 'Active'
            ELSE 'Deleted' END) AS status, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age
            from users 
            where (username like '%$search%' or email like '%$search%' or gender like '%$search%')
            and is_verified = 1
            ");
        
        $result['sr'] = 1;
        $result['uri_segment'] = 'search';

        return view('admin.view-verified-users', $result);
    }
    /* ---------- User CRUD End ---------- */

    /* Event module start */

    public function searchAdminEvents(Request $request) {

        $search = $request->search;

        if (empty($search)) {
            return redirect('admin/view-admin-events/' . $request->sort);
        }
        if (!empty($request->sort)) {
            $sort = [
                'paid' => 'free_event ASC, start_date DESC',
                'free' => 'free_event DESC, start_date DESC',
                'name' => 'eng_name ASC',
                'company' => 'eng_company_name ASC',
                'date' => 'start_date DESC'
            ];

            $order = $sort[$request->sort];
        } else {
            $order = 'id DESC';
        }

        $result['events'] = Event::getSearchEvent($search, $order, 'admin');
        $result['sr'] = 1;
        $result['uri_segment'] = 'search';
        $result['search'] = $search;

        if (count($result['events']) > 0) {
            foreach ($result['events'] as $event) {
                $result['categories'][] = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();
                $result['locations'][] = DB::table('locations')->where('event_id', $event->id)->get();
            }
        } else {
            $result['categories'] = array();
            $result['locations'] = array();
        }

        return view('admin.view-events', $result);
    }

    public function searchUserEvents(Request $request) {

        $search = $request->search;

        if (empty($search)) {
            return redirect('admin/view-user-events/' . $request->sort);
        }
        if (!empty($request->sort)) {
            $sort = [
                'paid' => 'free_event ASC, start_date DESC',
                'free' => 'free_event DESC, start_date DESC',
                'name' => 'eng_name ASC',
                'company' => 'eng_company_name ASC',
                'date' => 'start_date DESC'
            ];

            $order = $sort[$request->sort];
        } else {
            $order = 'id DESC';
        }

        $result['events'] = Event::getSearchEvent($search, $order, 'user');
        $result['sr'] = 1;
        $result['uri_segment'] = 'search';
        $result['search'] = $search;

        if (count($result['events']) > 0) {
            foreach ($result['events'] as $event) {
                $result['categories'][] = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();
                $result['locations'][] = DB::table('locations')->where('event_id', $event->id)->get();
            }
        } else {
            $result['categories'] = array();
            $result['locations'] = array();
        }

        return view('admin.view-user-events', $result);
    }

    public function viewAdminEvents(Request $request) {

        if (!empty($request->sort)) {
            $sort = [
                'paid' => 'free_event ASC, start_date DESC',
                'free' => 'free_event DESC, start_date DESC',
                'name' => 'eng_name ASC',
                'company' => 'eng_company_name ASC',
                'date' => 'start_date DESC'
            ];

            $order = $sort[$request->sort];
        } else {
            $order = 'id DESC';
        }
        
        $result['events'] = DB::table('events')
                ->select(DB::raw('events.*, username'))
                ->join('users', 'users.id', '=', 'events.user_id', 'left')
                ->join('categories', 'users.id', '=', 'events.user_id', 'left')
                ->whereRaw('user_id = 0')
                ->orderByRaw($order)
                ->groupBy('events.id')
                ->paginate(15);
                
        $result['sr'] = ($result['events']->currentPage() > 1) ? ($result['events']->currentPage()-1)*($result['events']->perPage())+1 : 1;
        
        if (count($result['events']) > 0) {
            foreach ($result['events'] as $event) {
                $result['categories'][] = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();
                $result['locations'][] = DB::table('locations')->where('event_id', $event->id)->get();
            }
        }

        $result['uri_segment'] = 'view';

        return view('admin.view-events', $result);
    }

    public function viewUserEvents(Request $request) {

        if (!empty($request->sort)) {
            $sort = [
                'paid' => 'free_event ASC, start_date DESC',
                'free' => 'free_event DESC, start_date DESC',
                'name' => 'eng_name ASC',
                'company' => 'eng_company_name ASC',
                'date' => 'start_date DESC'
            ];

            $order = $sort[$request->sort];
        } else {
            $order = 'id DESC';
        }
        
        $result['events'] = DB::table('events')
                ->select(DB::raw('events.*, username'))
                ->join('users', 'users.id', '=', 'events.user_id', 'inner')
                ->join('categories', 'users.id', '=', 'events.user_id', 'left')
                ->orderByRaw($order)
                ->groupBy('events.id')
                ->paginate(15);
                
        $result['sr'] = ($result['events']->currentPage() > 1) ? ($result['events']->currentPage()-1)*($result['events']->perPage())+1 : 1;

        if (count($result['events']) > 0) {
            foreach ($result['events'] as $event) {
                $result['categories'][] = DB::table('categories')->whereIn('id', explode(',', $event->category_id))->get();
                $result['locations'][] = DB::table('locations')->where('event_id', $event->id)->get();
            }
        }

        $result['uri_segment'] = 'view';

        return view('admin.view-user-events', $result);
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

            $result['cities'] = DB::table('cities')->select('city_name', 'latitude', 'longitude')->where('country_id', 11)->orderBy('city_name', 'ASC')->get();
            $result['categories'] = DB::table('categories')->select('id', 'english', 'arabic')->get();
            $result['types'] = DB::table('types')->select('id', 'english', 'arabic')->get();
            $result['languages'] = DB::table('languages')->select('id', 'title')->get();
            $result['uri_segment'] = $request->segment(2);

            return view('admin.event-details', $result);
        }

        $validation_data = [
            'type' => 'required',
            'category' => 'required',
            'event_name' => 'required',
            'event_name_ar' => 'required',
            'event_company' => 'required',
            'event_company_ar' => 'required',
            'fee' => 'required',
            'venue' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ];

        if ($request->all_day != 1) {
            $event_dates = [
                'start_time' => 'required',
                'end_time' => 'required'
            ];

            $validation_data = array_merge($validation_data, $event_dates);
        }

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $reference_no = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 5; $i++) {
            $reference_no .= $characters[mt_rand(0, $max)];
        }

        $all_day = !empty($request->all_day) ? $request->all_day : 0;
        if($all_day == 1){
            $start_date = date('Y-m-d', strtotime($request->start_date));
            $end_date = date('Y-m-d', strtotime($request->end_date));
        } 
        else{
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' ' . date('H:i:s', strtotime($request->start_time));
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' ' . date('H:i:s', strtotime($request->end_time));
        }
        $fee = !empty($request->fee) ? $request->fee : 0;
        $is_kids = !empty($request->kids) ? $request->kids : 0;
        $is_disabled = !empty($request->disable) ? $request->disable : 0;
        $is_featured = !empty($request->featured) ? $request->featured : 0;
        $event_data = [
            'type_id' => implode(',', $request->type),
            'category_id' => implode(',', $request->category),
            'keyword' => $request->keyword,
            'eng_name' => $request->event_name,
            'ar_name' => $request->event_name_ar,
            'eng_company_name' => trim($request->event_company),
            'ar_company_name' => trim($request->event_company_ar),
            'phone' => $request->phone,
            'email' => $request->email,
            'weblink' => $request->url,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'all_day' => $all_day,
            'free_event' => $fee,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'event_language' => implode(',', $request->event_language),
            'eng_description' => $request->eng_description,
            'ar_description' => $request->ar_description,
            'venue' => $request->venue,
            'is_kids' => $is_kids,
            'is_disabled' => $is_disabled,
            'is_featured' => $is_featured,
            'share_count' => 1,
            'status' => 'Active',
            'created_at' => $this->current_date_time,
            'updated_at' => $this->current_date_time,
        ];

        if (!empty($request->event_id) && $request->uri != 'duplicate-event') {
            $event_id = Crypt::decrypt($request->event_id);
            $res = DB::table('events')->where('id', '=', $event_id)->update($event_data);
            $this->digitalOcean->table('events')->where('id', '=', $event_id)->update($event_data);

            if ($res == 1) {
                $id = $event_id;
            } else {
                return redirect()->back()->withInput()->withErrors(['Could not be updated']);
            }
        } else {
            $ref = ['reference_no' => $reference_no];
            $event_data = array_merge($ref, $event_data);
            $id = DB::table('events')->insertGetId($event_data);
            $this->digitalOcean->table('events')->insertGetId($event_data);
        }
        if ($id) {

            $destinationPath = base_path() . '/public/uploads';

            if ($request->uri == 'duplicate-event') {
                $event_id = Crypt::decrypt($request->event_id);
                $pic_res = DB::table('pictures')->where('event_id', $event_id)->get();
                if ($pic_res) {
                    foreach ($pic_res as $old) {
                        $img1 = $destinationPath . '/' . $old->picture;
                        $thumbnail1 = base_path() . '/public/thumbnail/' . $old->picture;

                        $extension = explode('.', $old->picture);
                        $img2_name = uniqid() . '.' . $extension[1];
                        $img2 = $destinationPath . '/' . $img2_name;
                        $thumbnail2 = base_path() . '/public/thumbnail/'.$img2_name;

                        copy($img1, $img2);
                        copy($thumbnail1, $thumbnail2);
                        $pic_data[] = ['event_id' => $id, 'picture' => $img2_name];
                    }
                }

                $attch_res = DB::table('attachments')->where('event_id', $event_id)->get();
                if ($attch_res) {
                    foreach ($attch_res as $old) {
                        $img1 = $destinationPath . '/' . $old->picture;

                        $extension = explode('.', $old->picture);
                        $img2_name = uniqid() . '.' . $extension[1];
                        $img2 = $destinationPath . '/' . $img2_name;

                        copy($img1, $img2);
                        $attch_data[] = ['event_id' => $id, 'picture' => $img2_name];
                    }
                }
            }

            if ($request->hasFile('picture')) {
                for ($pic = 1; $pic <= count($request->picture); $pic++) {
                    $file = $request->file('picture')[$pic - 1];
                    $extension = $file->getClientOriginalExtension();
                    $picture[$pic] = uniqid() . '.' . $extension;
                    $file->move($destinationPath, $picture[$pic]);

                    $this->create_watermark($destinationPath . '/' . $picture[$pic], $destinationPath . '/' . $picture[$pic]);
                    $this->compressImage($destinationPath.'/'.$picture[$pic], base_path() . '/public/thumbnail/'.$picture[$pic], 20);
                    $pic_data[] = ['event_id' => $id, 'picture' => $picture[$pic]];
                }
            }

            if (isset($pic_data)) {
                DB::table('pictures')->insert($pic_data);
                $this->digitalOcean->table('pictures')->insert($pic_data);
            }

            if ($request->hasFile('attachment')) {
                for ($attch = 1; $attch <= count($request->attachment); $attch++) {
                    $file = $request->file('attachment')[$attch - 1];
                    $extension = $file->getClientOriginalExtension();
                    $attachment[$attch] = uniqid() . '.' . $extension;
                    $file->move($destinationPath, $attachment[$attch]);

                    $this->create_watermark($destinationPath . '/' . $attachment[$attch], $destinationPath . '/' . $attachment[$attch]);
                    $this->compressImage($destinationPath.'/'.$attachment[$attch], base_path() . '/public/thumbnail/'.$attachment[$attch], 20);
                    $attch_data[] = ['event_id' => $id, 'picture' => $attachment[$attch]];
                }
            }

            if (isset($attch_data)) {
                DB::table('attachments')->insert($attch_data);
                $this->digitalOcean->table('attachments')->insert($attch_data);
            }

            if (count($request->city) > 0 && !empty($request->city[0])) {
                $cities = explode('~', $request->city);
                $locations = explode('~', $request->location);
                $latlngs = explode('~', $request->latlng);

                DB::table('locations')->where('event_id', $id)->delete();
                $this->digitalOcean->table('locations')->where('event_id', $id)->delete();

                for ($loc = 1; $loc < count($cities); $loc++) {

                    $latlng = str_replace(array('(', ')'), '', $latlngs[$loc]);
                    $latlng = explode(',', $latlng);
                    $city = explode(',', $cities[$loc]);

                    $loc_data[] = [
                        'event_id' => $id,
                        'city' => trim($city[0]),
                        'location' => trim($locations[$loc]),
                        'latitude' => $latlng[0],
                        'longitude' => $latlng[1]
                    ];
                }
                DB::table('locations')->insert($loc_data);
                $this->digitalOcean->table('locations')->insert($loc_data);
            }

            Session::flash('success', 'Event successfully saved');
            return redirect('admin/view-admin-events');
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

            $result['cities'] = DB::table('cities')->select('city_name', 'latitude', 'longitude')->where('country_id', 11)->orderBy('city_name', 'ASC')->get();
            $result['categories'] = DB::table('categories')->select('id', 'english', 'arabic')->get();
            $result['types'] = DB::table('types')->select('id', 'english', 'arabic')->get();
            $result['languages'] = DB::table('languages')->select('id', 'title')->get();
            $result['uri_segment'] = $request->segment(2);
            $result['old_event_languages'] = explode(',', $result['event']->event_language);
        }
        
        return view('admin.event-details', $result);
    }

    public function deleteEvent(Request $request) {
        
        $id = Crypt::decrypt($request->segment(3));        
        $attachments = DB::table('attachments')->where('event_id', '=', $id)->get();
        $pictures = DB::table('pictures')->where('event_id', '=', $id)->get();

        if(count($attachments) > 0){
            foreach ($attachments as $attch) {
                unlink(base_path() . '/public/uploads/' . $attch->picture);
                unlink(base_path() . '/public/thumbnail/' . $attch->picture);
            }
        }
        if(count($attachments) > 0){
            foreach ($pictures as $pic) {
                unlink(base_path() . '/public/uploads/' . $pic->picture);
                unlink(base_path() . '/public/thumbnail/' . $pic->picture);
            }
        }

        // $res = DB::table('events')->where('id', '=', $id)->delete();
        $res = DB::delete("delete events, attachments, pictures, locations
					from events
					left join attachments on attachments.event_id = events.id
					left join pictures on pictures.event_id = events.id
					left join locations on locations.event_id = events.id
					where events.id = $id
");
        
        $this->digitalOcean->delete("delete events, attachments, pictures, locations
					from events
					left join attachments on attachments.event_id = events.id
					left join pictures on pictures.event_id = events.id
					left join locations on locations.event_id = events.id
					where events.id = $id
");

        Session::flash('success', 'Event successfully deleted');
        if($request->segment(2) == 'delete-admin-event'){
            return redirect('admin/view-admin-events');
        }
        else{
            return redirect('admin/view-user-events');
        }
    }

    public function eventStatus(Request $request) {

        $id = Crypt::decrypt($request->id);
        $res = DB::table('events')->where('id', '=', $id)->update(['status' => $request->status]);
        $this->digitalOcean->table('events')->where('id', '=', $id)->update(['status' => $request->status]);

        if ($res == 1) {
            echo '1';
        } else {
            echo 'Error';
        }
    }

    public function deleteEventImage(Request $request) {

        $event_id = Crypt::decrypt($request->eventId);
        $image_id = Crypt::decrypt($request->imageId);
        $pictures = DB::table($request->type)->where('id', '=', $image_id)->where('event_id', '=', $event_id)->first();
        unlink(base_path() . '/public/uploads/' . $pictures->picture);

        $res = DB::table($request->type)->where('id', '=', $image_id)->where('event_id', '=', $event_id)->delete();
        $this->digitalOcean->table($request->type)->where('id', '=', $image_id)->where('event_id', '=', $event_id)->delete();

        if ($res == 1) {
            echo '1';
        } else {
            echo 'Error';
        }
    }

    public function pushNotification(Request $request) {
        
        if ($request->isMethod('get')) {
            $result['cities'] = DB::table('cities')->where('country_id', 11)->orderBy('city_name', 'ASC')->get();
            $result['languages'] = DB::table('languages')->get();
            $result['types'] = DB::table('types')->get();
            $result['categories'] = DB::table('categories')->get();

            return view('admin.push-notification', $result);
        } 
                
        $validation_data = [
            'title' => 'required',
            'message' => 'required'
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        
        if($request->chk_all != 'all'){
            $data = [
                'category' => $request->category,
                'type' => $request->type,
                'language' => $request->language,
                'city' => $request->city,
            ];

            $events = Event::getNotificationEvent($data);

            if(count($events) > 0){
                foreach($events as $row){
                    $user_ids[] = $row['user_id'];
                }

                $this->android_push($request->title, $request->message, $user_ids);
                $this->ios_notification($request->title, $request->message, $user_ids);

                Session::put('success', 'Request Sent!');
            }
            else{
                Session::put('error', 'No record found');
            }
        }
        else{
            $this->android_push($request->title, $request->message);
            $this->ios_notification($request->title, $request->message);            
        }        

        return redirect()->back();
    }

    public function ios_notification($noti_title, $msg, $user_ids = '') {

        if(empty($user_ids)){            
            $title = array(
                "en" => $noti_title,
            );
            $content = array(
                "en" => $msg,
            );

            $fields = array(
                'app_id' => "81c54624-340c-4ccd-907e-8fade9cbccb9",
                'included_segments' => array("All"),
                'contents' => $content,
                'heading' => $title,
                'data' => ['title' => $noti_title, 'body' => $msg],
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

            return $response;
        }
        else{
            $user_ids = implode(',', $user_ids);
            $tokens = DB::select("select object_id from tokens where object_id IS NOT NULL and user_id in ($user_ids)");
            
            if(count($tokens) > 0){

                foreach ($tokens as $tk) {
                    $ids[] = $tk->object_id;
                }

                $chunks = array_chunk($ids, 2000);
                foreach ($chunks as $chk) {
                    $player_ids = $chk;
                    $title = array(
                        "en" => $noti_title,
                    );
                    $content = array(
                        "en" => $msg,
                    );

                    $fields = array(
                        'app_id' => "81c54624-340c-4ccd-907e-8fade9cbccb9",
                        'include_player_ids' => $player_ids,
                        'contents' => $content,
                        'heading' => $title,
                        'data' => ['title' => $noti_title, 'body' => $msg],
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
                    
                    return $response;
                }
            }
            else{
                return 'no token found';
            }
        }
        
        return $response;
    }

    public function android_push($title, $body, $user_ids = '') {
        
        if(!empty($user_ids)){
            $tokens = DB::table('tokens')->select('token')->whereIn('user_id', $user_ids)->whereNull('object_id')->get();
        }
        else{
            $tokens = DB::table('tokens')->select('token')->whereNull('object_id')->get();
        }      
                
        if(count($tokens) > 0){
            foreach ($tokens as $tk) {
                $ids[] = $tk->token;
            }

            $chunks = array_chunk($ids, 1000);

            foreach ($chunks as $chk) {
                $registrationIds = $chk;
                define('API_ACCESS_KEY', 'AIzaSyA7qEzOETYNfXWADnLq_heXFXoSY25nr-k');

                $msg['notification'] = array
                    (
                    'title' => $title,
                    'message' => $body,
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
            }
        }else{
            return 'no token found';
        }
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
    
    
    function compressOldImages(){
        
        $pictures = DB::table('pictures')->get();
        
        $path = base_path() . '/public/';
        
        foreach ($pictures as $pic) {
            echo $res = $this->compressImage($path.'uploads/'.$pic->picture, $path.'thumbnail/'.$pic->picture, 20);
            echo $res.'<br/>';
        }
    }

}
