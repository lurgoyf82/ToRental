<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Foundation\Auth\User as Auth;
	use Illuminate\Notifications\Notifiable;
	use Laravel\Sanctum\HasApiTokens;

	class User extends Auth
	{
		use HasApiTokens, HasFactory, Notifiable;

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<int, string>
		 */
		protected $fillable = [
			'name',
			'email',
			'password',
		];

		/**
		 * The attributes that should be hidden for serialization.
		 *
		 * @var array<int, string>
		 */
		protected $hidden = [
			'password',
			'remember_token',
		];

		/**
		 * The attributes that should be cast.
		 *
		 * @var array<string, string>
		 */
		protected $casts = [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
		];

		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array<string, string>
		 */
		public static function getRules(): array
		{
			return [
				'name' => 'required|string|max:255',
				'email' => 'required|string|email|max:255|unique:users',
				'password' => 'required|string|confirmed|min:8',
			];
		}

		/**
		 * Insert a new user, the $data['password'] will be hashed by this method.
		 *
		 * @param array<string, string> $data
		 * @return User
		 */
		public static function insertUser(array $data): User {
			return User::create([
				'name' => $data['name'],
				'email' => $data['email'],
				'password' => Hash::make($data['password']),
			]);
		}

	}
