<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model {
	protected $table = 'users';

	public static function getUserDetails($id = null, $email = null, $password = null) {

		$query = DB::table('users');
		$query = $query->select(DB::raw('id, username, email, gender, dob, interested_in_kids as kids, interested_in_disabled as disability, image, is_social, status'));

		if ($id) {
			$query = $query->where('id', '=', $id);
		} else {
			$query = $query->where('username', '=', "$email");
			$query = $query->orWhere('email', '=', "$email");
			$query = $query->where('password', '=', "$password");
		}
		$query = $query->first();

		return $query;
	}

	public static function getUserCategories($id) {

		return DB::table('user_categories')->select('categories.id', 'arabic', 'english')
		                                   ->join('categories', 'categories.id', '=', 'user_categories.category_id')
		                                   ->where('user_categories.user_id', '=', $id)
		                                   ->get();
	}
}
