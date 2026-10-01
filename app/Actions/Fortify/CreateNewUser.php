<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Guests;
use App\Models\Members;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact' => 'required',
            'username' => ['required', 'string', 'min:3', 'max:255', 'unique:users'],
            'birthday' => 'required',
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'email' => $input['email'],
            'username' => $input['username'],
            'password' => Hash::make($input['password'])
        ]);

        $guest = new Guests;
        $guest->first_name = $input['first_name'];
        $guest->last_name = $input['last_name'];
        $guest->email = $input['email'];
        $guest->contact = $input['contact'];
        $guest->birthday = $input['birthday'];
        $guest->save();
        
        $member = new Members;
        $member->user_id = $user->id;
        $member->guest_id = $guest->id;
        $member->save();

        return $user;
    }
}
