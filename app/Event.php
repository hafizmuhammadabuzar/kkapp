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
		$query->join('locations', 'locations.event_id', '=', 'events.id');
		if ($is_featured == false) {
			$query->whereNull('is_featured');
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
		$query->groupBy('id');
		$result = $query->get();

		return $result;
	}

	public static function getEventsByRadius($latLng, $radius, $type = '') {

		$split_latLng = explode(',', $latLng);

		$query = Event::with('pictures', 'attachments', 'locations');
		$query->select(DB::raw("events.*, ( 3959 * acos( cos( radians(".$split_latLng[0].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$split_latLng[1].") ) + sin( radians(".$split_latLng[0].") ) * sin( radians( latitude ) ) ) ) `distance`"));
		$query->join('locations', 'locations.event_id', '=', 'events.id');
		if (!empty($type)) {
			$query->whereIn('type_id', explode(',', $type));
		}
		$query->havingRaw("distance < $radius");
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

		$query = Event::with('pictures', 'attachments', 'locations');
		$query->select(DB::raw("events.*"));
		$query->where('user_id', '=', $user_id);
		$query->groupBy("id");
		$result = $query->get();

		return $result;
	}

	public static function getSearchEvent($search, $sort = '') {

		// $query = Event::with('locations');
		$query = Event::select(DB::raw("events.*, username"));
		$query->join('users', 'users.id', '=', 'events.user_id', 'left');
		$query->where(DB::raw("keyword like '%$search%' or eng_name like '%$search%' or ar_name like '%$search%' or eng_company_name like '%$search%' or ar_company_name like '%$search%' or events.phone like '%$search%' or events.email like '%$search%' or events.start_date like '%$search%' or events.end_date like '%$search%'"));
		$query->orderByRaw($sort);
		$result = $query->get();

		return $result;
	}

}
