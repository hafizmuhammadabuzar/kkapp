<?php

namespace App;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {

    protected $table = 'events';

    public function pictures() {
        return $this->hasMany('App\Picture');
    }

    public function attachments() {
        return $this->hasMany('App\Attachment');
    }

    public function locations() {
        return $this->hasMany('App\Location');
    }

    public static function getEvents($city = '', $type = '', $offset = '', $is_featured = false, $categories='', $languages='', $kids='', $disable='') {
        
        $cur_date = date('Y-m-d');

        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*, DATE_FORMAT(events.start_date,'%d-%m-%Y    -    %h:%i %p') as start_date, DATE_FORMAT(events.end_date,'%d-%m-%Y    -    %h:%i %p') as end_date, DAYNAME(events.start_date) as start_day"));
        $query->join('locations', 'locations.event_id', '=', 'events.id', 'left');
        $query->where('events.status', '=', 'Active');
        $query->where('end_date', '>=', $cur_date);
        if ($is_featured == false) {
            $query->where('is_featured', '=', '0');
        } else {
            $query->where('is_featured', '=', '1');
        }
        if (!empty($city)) {
            $query->where('city', '=', $city);
        }
        if (!empty($type)) {
            $types = explode(',', $type);
            $query->where(function ($query) use ($types) {
                foreach($types as $key => $type){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$type.',type_id)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$type.',type_id)');
                    }
                }
            });            
        }
        if($categories){
            $categories = explode(',', $categories);            
            $query->where(function ($query) use ($categories) {
                foreach($categories as $key => $cat){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$cat.',category_id)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$cat.',category_id)');
                    }
                }
            });            
        }
        if($languages){
            $languages = explode(',', $languages);            
            $query->where(function ($query) use ($languages) {
                foreach($languages as $key => $lang){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$lang.',event_language)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$lang.',event_language)');
                    }
                }
            });            
        }
        $query->take(5)->skip($offset);
        $query->orderBy('events.start_date', 'ASC');
        $query->groupBy('id');
        $result = $query->get();

        return $result;
    }

    public static function getEventsByRadius($latLng, $radius, $type = '', $categories='', $languages='') {

        $cur_date = date('Y-m-d');
        $split_latLng = explode(',', $latLng);

        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*, DATE_FORMAT(events.start_date,'%d-%m-%Y    -    %h:%i %p')as start_date, DATE_FORMAT(events.end_date,'%d-%m-%Y    -    %h:%i %p') as end_date, ( 3959 * acos( cos( radians(" . $split_latLng[0] . ") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(" . $split_latLng[1] . ") ) + sin( radians(" . $split_latLng[0] . ") ) * sin( radians( latitude ) ) ) ) `distance`"));
        $query->join('locations', 'locations.event_id', '=', 'events.id');
        $query->where('events.status', '=', 'Active');
        $query->where('end_date', '>=', $cur_date); 
        if (!empty($type)) {
            $types = explode(',', $type);
            $query->where(function ($query) use ($types) {
                foreach($types as $key => $type){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$type.',type_id)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$type.',type_id)');
                    }
                }
            });            
        }
        if($categories){
            $categories = explode(',', $categories);            
            $query->where(function ($query) use ($categories) {
                foreach($categories as $key => $cat){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$cat.',category_id)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$cat.',category_id)');
                    }
                }
            });            
        }
        if($languages){
            $languages = explode(',', $languages);            
            $query->where(function ($query) use ($languages) {
                foreach($languages as $key => $lang){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$lang.',event_language)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$lang.',event_language)');
                    }
                }
            });            
        }
        $query->havingRaw("distance < $radius");
        $query->orderBy('events.start_date', 'ASC');
        $query->groupBy("id");
        $result = $query->get();

        return $result;
    }

    public static function getMostLike() {

        $cur_date = date('Y-m-d');
        
        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*, count(user_favourite_events.event_id) as likes, DATE_FORMAT(events.start_date,'%d-%m-%Y    -    %h:%i %p')as start_date, DATE_FORMAT(events.end_date,'%d-%m-%Y    -    %h:%i %p') as end_date"));
        $query->join('user_favourite_events', 'user_favourite_events.event_id', '=', 'events.id');
        $query->where('events.status', '=', 'Active');
        $query->where('end_date', '>=', $cur_date);
        $query->groupBy('user_favourite_events.event_id');
        $query->orderBy('likes', 'DESC');
        $query->take(5)->skip(0);
        $result = $query->get();

        return $result;
    }

    public static function getMostShared() {

        $cur_date = date('Y-m-d');
        
        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*, DATE_FORMAT(events.start_date,'%d-%m-%Y    -    %h:%i %p')as start_date, DATE_FORMAT(events.end_date,'%d-%m-%Y    -    %h:%i %p') as end_date"));
        $query->where('events.status', '=', 'Active');
        $query->where('share_count', '!=', 0);
        $query->where('end_date', '>=', $cur_date);
        $query->orderBy('share_count', 'DESC');
        $query->take(5)->skip(0);
        $result = $query->get();

        return $result;
    }

    public static function getFavouriteEvents($user_id) {

        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*, DATE_FORMAT(events.start_date,'%d-%m-%Y    -    %h:%i %p')as start_date, DATE_FORMAT(events.end_date,'%d-%m-%Y    -    %h:%i %p') as end_date"));
        $query->join('user_favourite_events', 'user_favourite_events.event_id', '=', 'events.id');
        $query->where('user_favourite_events.user_id', '=', $user_id);
        $query->where('events.status', '=', 'Active');
        $query->groupBy("id");
        $result = $query->get();

        return $result;
    }

    public static function getUserEvents($user_id) {

        $categories = DB::table('users')
                ->select('interested_in_kids','interested_in_disabled','category_id')
                ->join('user_categories','user_categories.user_id','=','users.id','left')
                ->where('users.id',$user_id)
                ->get();
                
        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*, DATE_FORMAT(events.start_date,'%d-%m-%Y    -    %h:%i %p')as start_date, DATE_FORMAT(events.end_date,'%d-%m-%Y    -    %h:%i %p') as end_date"));
        $query->where('user_id', '=', $user_id);
        if($categories[0]->interested_in_kids == 1){
            $query->where('is_kids', '=', 1);
        }
        if($categories[0]->interested_in_disabled == 1){
            $query->where('is_disabled', '=', 1);
        }
        if($categories[0]->category_id){            
            $query->where(function ($query) use ($categories) {
                foreach($categories as $key => $cat){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$cat->category_id.',category_id)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$cat->category_id.',category_id)');
                    }
                }
            });            
        }
        $query->groupBy("id");
        $result = $query->get();

        return $result;
    }

    public static function getSearchEvent($search, $sort = '', $type = '') {

        $query = Event::select(DB::raw("events.*, username"));
        $query->join('users', 'users.id', '=', 'events.user_id', 'left');
        $query->join('locations', 'locations.event_id', '=', 'events.id', 'left');
        $query->join('categories', 'categories.id', '=', 'events.category_id', 'inner');
        if(Session::has('user_id')){
            $query->where('user_id', Session::get('user_id'));
        }
        if($type == 'admin'){
            $query->whereRaw('user_id = 0');
        }
        else{
            $query->whereRaw('user_id > 0');
        }
        $query->whereRaw('(reference_no like "%'.$search.'%" or keyword like "%'.$search.'%" or eng_name like "%'.$search.'%" or ar_name like "%'.$search.'%" or eng_company_name like "%'.$search.'%" or ar_company_name like "%'.$search.'%" or events.phone like "%'.$search.'%" or events.email like "%'.$search.'%" or events.start_date like "%'.$search.'%" or events.end_date like "%'.$search.'%" or city like "%'.$search.'%" or english like "%'.$search.'%" or arabic like "%'.$search.'%")');
        $query->orderByRaw($sort);
        $result = $query->get();

        return $result;
    }

    public static function getAppSearchEvent($search_data) {
        
        $cur_date = date('Y-m-d');

        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*, DATE_FORMAT(events.start_date,'%d-%m-%Y    -    %h:%i %p')as start_date, DATE_FORMAT(events.end_date,'%d-%m-%Y    -    %h:%i %p') as end_date"));
        if(!empty($search_data['verified_user'])){
            $query->join('users', 'users.id', '=', 'events.user_id');
        }
        if(!empty($search_data['city'])){
            $query->join('locations', 'locations.event_id', '=', 'events.id');     
        }
        $query->where('events.status', '=', 'Active');
        if(empty($search_data['start_date']) && empty($search_data['end_date'])){
            $query->where('end_date', '>=', $cur_date);
        }
        if(!empty($search_data['eng_company'])){
            $query->where('eng_company_name', '=', $search_data['eng_company']);
        }
        
        if(!empty($search_data['ar_company'])){
            $query->where('ar_company_name', '=', $search_data['ar_company']);
        }
        
        if(!empty($search_data['venue'])){            
//            $venue = explode(',', $search_data['venue']);
//            $query->where(function ($query) use ($venue) {
//                foreach($venue as $key => $v){
//                    if($key == 0){
//                        $query->whereRaw('FIND_IN_SET("'.$v.'",venue)');
//                    }
//                    else{
//                        $query->orWhereRaw('FIND_IN_SET("'.$v.'",venue)');
//                    }
//                }
//            });
            $query->whereIn('venue', explode(',', $search_data['venue']));
        }
        
        if($search_data['is_free'] == 1 && $search_data['is_paid'] != 1){
            $query->where('free_event', '=', 1);
        }
        else if($search_data['is_free'] != 1 && $search_data['is_paid'] == 1){
            $query->where('free_event', '=', 0);
        }        
        
        if(!empty($search_data['city'])){
            $query->whereIn('city', explode(',', $search_data['city']));
        }
        if(!empty($search_data['verified_user'])){
            $query->where('user_id', '=', $search_data['verified_user']);
        }
        if(!empty($search_data['start_date']) && !empty($search_data['end_date'])){
            $query->where('start_date','>=',date('Y-m-d', strtotime($search_data['start_date'])));
            $query->where('end_date','<=',date('Y-m-d', strtotime($search_data['end_date'])));
        }
        else if(!empty($search_data['start_date']) && empty($search_data['end_date'])){
            $query->where('start_date','=',date('Y-m-d', strtotime($search_data['start_date'])));
        }
        
        if(!empty($search_data['category'])){
            $categories = explode(',', $search_data['category']);
            
            $query->where(function ($query) use ($categories, $search_data) {
                foreach($categories as $key => $cat){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$cat.',category_id)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$cat.',category_id)');
                    }
                }
            });            
        }
        if(!empty($search_data['type'])){
            $types = explode(',', $search_data['type']);
            $key = '';
            $query->where(function ($query) use ($types, $search_data) {
                foreach($types as $key => $type){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$type.',type_id)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$type.',type_id)');
                    }
                }
            });
        }
        if(!empty($search_data['language'])){
            $languages = explode(',', $search_data['language']);
            $key = '';
            $query->where(function ($query) use ($languages, $search_data) {
                foreach($languages as $key => $lang){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$lang.',event_language)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$lang.',event_language)');
                    }
                }
            });
        }
        if(!empty($search_data['keyword'])){
            $keywords = explode(',', $search_data['keyword']);
            $key = '';
            $query->where(function ($query) use ($keywords, $search_data) {
                foreach($keywords as $key => $kw){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET("'.$kw.'",keyword)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET("'.$kw.'",keyword)');
                    }
                }
            });
        }
        $query->orderBy('events.start_date', 'ASC');
        $query->groupBy("id");
        $result = $query->get();

        return $result;
    }

    public static function getNotificationEvent($search_data) {

        $query = Event::select('user_id');
        $query->join('users', 'users.id', '=', 'events.user_id');
        if(count($search_data['city']) > 0){
            $query->join('locations', 'locations.event_id', '=', 'events.id');
            $query->whereIn("city", $search_data['city']);
        }
        
        if(!empty($search_data['category'])){
            
            
            $query->where(function ($query) use ($search_data) {
                foreach($search_data['category'] as $key => $cat){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$cat.',category_id)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$cat.',category_id)');
                    }
                }
            });            
        }
        if(!empty($search_data['language'])){
            
            $query->where(function ($query) use ($search_data) {
                foreach($search_data['language'] as $key => $cat){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$cat.',event_language)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$cat.',event_language)');
                    }
                }
            });            
        }
        if(!empty($search_data['type'])){
            $key = '';
            $query->where(function ($query) use ($search_data) {
                foreach($search_data['type'] as $key => $type){
                    if($key == 0){
                        $query->whereRaw('FIND_IN_SET('.$type.',type_id)');
                    }
                    else{
                        $query->orWhereRaw('FIND_IN_SET('.$type.',type_id)');
                    }
                }
            });
        }
        
        $query->groupBy("user_id");
        $result = $query->get();

        return $result;
    }

}
