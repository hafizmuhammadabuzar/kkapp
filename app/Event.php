<?php

namespace App;

use DB;
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

    public static function getEvents($city = '', $type = '', $offset = '', $is_featured = false) {

        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*"));
        $query->join('locations', 'locations.event_id', '=', 'events.id', 'left');
        if ($is_featured == false) {
            $query->where('is_featured', '=', '0');
//            $query->whereNull('is_featured');
        } else {
            $query->where('is_featured', '=', '1');
        }
        if (!empty($city)) {
            $query->where('city', '=', $city);
        }
        if (!empty($type)) {
            $query->whereIn('type_id', explode(',', $type));
        }
        $query->take(50)->skip($offset);
        $query->orderBy('id');
        $query->groupBy('id');
        $result = $query->get();

        return $result;
    }

    public static function getEventsByRadius($latLng, $radius, $type = '') {

        $split_latLng = explode(',', $latLng);

        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*, ( 3959 * acos( cos( radians(" . $split_latLng[0] . ") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(" . $split_latLng[1] . ") ) + sin( radians(" . $split_latLng[0] . ") ) * sin( radians( latitude ) ) ) ) `distance`"));
        $query->join('locations', 'locations.event_id', '=', 'events.id');
        if (!empty($type)) {
            $query->whereIn('type_id', explode(',', $type));
        }
        $query->havingRaw("distance < $radius");
        $query->orderBy("id");
        $query->groupBy("id");
        $result = $query->get();

        return $result;
    }

    public static function getMostLike() {

        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*, count(user_favourite_events.event_id) as likes"));
        $query->join('user_favourite_events', 'user_favourite_events.event_id', '=', 'events.id');
        $query->groupBy('user_favourite_events.event_id');
        $query->orderBy('likes', 'DESC');
        $query->take(5)->skip(0);
        $result = $query->get();

        return $result;
    }

    public static function getMostShared() {

        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*"));
        $query->where('share_count', '!=', 0);
        $query->orderBy('share_count', 'DESC');
        $query->take(5)->skip(0);
        $result = $query->get();

        return $result;
    }

    public static function getFavouriteEvents($user_id) {

        $query = Event::with('pictures', 'attachments', 'locations');
        $query->select(DB::raw("events.*"));
        $query->join('user_favourite_events', 'user_favourite_events.event_id', '=', 'events.id');
        $query->where('user_favourite_events.user_id', '=', $user_id);
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
        $query->select(DB::raw("events.*"));
        $query->where('user_id', '=', $user_id);
        if($categories[0]->interested_in_kids == 1){
            $query->where('is_kids', '=', 1);
        }
        if($categories[0]->interested_in_disabled == 1){
            $query->where('is_disabled', '=', 1);
        }
        if($categories[0]->category_id){
            foreach($categories as $key => $cat){
                if($key == 0){
                    $query->whereRaw('FIND_IN_SET('.$cat->category_id.',category_id)');
                }
                else{
                    $query->orWhereRaw('FIND_IN_SET('.$cat->category_id.',category_id)');
                }
            }
        }
        $query->groupBy("id");
        $result = $query->get();

        return $result;
    }

    public static function getSearchEvent($search, $sort = '') {

        // $query = Event::with('locations');
        $query = Event::select(DB::raw("events.*, username"));
        $query->join('users', 'users.id', '=', 'events.user_id', 'left');
        $query->join('locations', 'locations.event_id', '=', 'events.id', 'left');
        $query->join('categories', 'categories.id', '=', 'events.category_id', 'inner');
        $query->where(DB::raw("reference_no like '%$search%' or keyword like '%$search%' or eng_name like '%$search%' or ar_name like '%$search%' or eng_company_name like '%$search%' or ar_company_name like '%$search%' or events.phone like '%$search%' or events.email like '%$search%' or events.start_date like '%$search%' or events.end_date like '%$search%' or city like '%$search%' or english like '%$search%' or arabic like '%$search%' or events.id like '%$search%'"));
        $query->orderByRaw($sort);
        $result = $query->get();

        return $result;
    }

    public static function getAppSearchEvent($search_data) {

        $query = Event::with('pictures', 'attachments', 'locations');
        $query->join('users', 'users.id', '=', 'events.user_id', 'left');
        $query->join('locations', 'locations.event_id', '=', 'events.id', 'left');     
        $query->select(DB::raw("events.*"));
        if(!empty($search_data['eng_company'])){
            $query->where('eng_company_name', '=', $search_data['eng_company']);
        }
        if(!empty($search_data['ar_company'])){
            $query->where('ar_company_name', '=', $search_data['ar_company']);
        }
        if($search_data['is_kids'] == 1){
            $query->where('is_kids', '=', 1);
        }
        if($search_data['is_disabled'] == 1){
            $query->where('is_disabled', '=', 1);
        }
        if(!empty($search_data['venue'])){
            $query->whereIn('venue', explode(',', $search_data['venue']));
        }        
        if(!empty($search_data['free_event'])){
            $query->whereIn('free_event', explode(',', $search_data['free_event']));
        }
        if(!empty($search_data['category'])){
            $categories = explode(',', $search_data['category']);
            foreach($categories as $key => $cat){
                if($key == 0){
                    $query->whereRaw('FIND_IN_SET('.$cat.',category_id)');
                }
                else{
                    $query->orWhereRaw('FIND_IN_SET('.$cat.',category_id)');
                }
            }
        }
        if(!empty($search_data['type'])){
            $types = explode(',', $search_data['type']);
            $key = '';
            foreach($types as $key => $type){
                if($key == 0){
                    $query->whereRaw('FIND_IN_SET('.$type.',type_id)');
                }
                else{
                    $query->orWhereRaw('FIND_IN_SET('.$type.',type_id)');
                }
            }
        }
        if(!empty($search_data['language'])){
            $languages = explode(',', $search_data['language']);
            $key = '';
            foreach($languages as $key => $lang){
                if($key == 0){
                    $query->whereRaw('FIND_IN_SET('.$lang.',event_languages)');
                }
                else{
                    $query->orWhereRaw('FIND_IN_SET('.$lang.',event_languages)');
                }
            }
        }
        if(!empty($search_data['keyword'])){
            $keywords = explode(',', $search_data['keyword']);
            $key = '';
            foreach($keywords as $key => $kw){
                if($key == 0){
                    $query->whereRaw('FIND_IN_SET('.$kw.',keyword)');
                }
                else{
                    $query->orWhereRaw('FIND_IN_SET('.$kw.',keyword)');
                }
            }
        }
        if(!empty($search_data['city'])){
            $cities = explode(',', $search_data['city']);
            $query->whereIn('city', explode(',', $search_data['city']));
        }
        if(!empty($search_data['verified_user'])){
            $query->where('username',$search_data['verified_user']);
        }
        if(!empty($search_data['start_date']) && !empty($search_data['end_date'])){
            $query->where('start_date','>=',$search_data['start_date']);
            $query->where('end_date','<=',$search_data['end_date']);
        }
        else if(!empty($search_data['start_date']) && empty($search_data['end_date'])){
            $query->where('start_date','=',$search_data['start_date']);
        }
        $query->groupBy("id");
        $result = $query->get();

        return $result;
    }

}
