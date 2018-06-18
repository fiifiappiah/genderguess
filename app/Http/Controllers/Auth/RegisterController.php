<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use GenderApi\Client as GenderApiClient;
use GenderApi;
use Stevebauman\Location\Location;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $location;

    protected $genderClient;


    /**
     * RegisterController constructor.
     * @param Location $location
     * @param GenderApiClient $client
     */
    public function __construct(Location $location, GenderApiClient $client)
    {
        $this->location = $location;
        $this->genderClient = $client;
        $this->middleware('guest');
        $this->setGenderApi();
    }


    private function setGenderApi()
    {
        try {
            $this->genderClient->setApiKey(env('GENDER_API_KEY', ''));

        }catch (\Exception $exception){

        }

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'sometimes|string',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if($genderNameDetails = $this->getGenderNameDetails($data['name'])){
            $data = array_merge($data, $genderNameDetails);
            return User::create([
                'name' => $data['name'],
                'first-name' => $data['first-name'],
                'last-name' => $data['last-name'],
                'email' => $data['email'],
                'gender' => $data['gender'],
                'password' => Hash::make($data['password']),
            ]);
        }
    }

    protected function getGenderNameDetails($name)
    {
        try {
            $countryCode = $this->location->get()->countryCode;
            $genderResult = $this->genderClient->getByFirstNameAndLastNameAndCountry($name, $countryCode);

            if($genderResult->getAccuracy() > 70){
                $data['first-name'] = $genderResult->getFirstName();
                $data['last-name'] = $genderResult->getLastName();
                $data['gender'] = $genderResult->getGender();
            }
            else { //Manually Take care of names
                $nameParts = explode(' ', $name);

                $data['first-name'] = $genderResult->getFirstName()?:$nameParts[0];
                $data['last-name'] = $genderResult->getLastName()?:end($nameParts);
                $data['gender'] = '';

                session(['no_gender'=>'no_gender']);

            }
        } catch (GenderApi\Exception $e) {
            exit('Exception: ' . $e->getMessage());
        }
        return $data;
    }
}
