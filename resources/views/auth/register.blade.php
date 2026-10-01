<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚜️</text></svg>">
    <title>Register</title>
    @include('home.css')
    <link rel="stylesheet" href="home/css/register.css">
</head>
<body>
@include('home.navbar')
<section class="hero d-none d-md-flex">
</section>
<section class="description">
    <div class="container text-center my-3">
        <h3>Join PUPSJ hotel. It’s easy and rewarding.</h3>
        <p class="m-auto">Join PUPSJ hotel, your new loyalty program. It’s a world filled with thoughtful perks, personal connections and amazing experiences. And it revolves around you. Beyond great locations, luxurious rooms and top-notch amenities, Our hotel connects you to the people, places and stories at the heart of your world. For more information see our FAQs.</p>
    </div>
</section>
<section>
    <div class="form container">
        <form method="POST" action="{{ route('register') }}" class="card row pt-3 mb-3">
            @csrf
            <h2>Register</h2>
            <div class="row mb-0" style="box-sizing: content-box">
                <div class="col-sm-6 mt-2">
                    <label for="first_name">{{ __('First Name') }}</label>
                    <input type="text" class="col-12" name="first_name" pattern="[^0-9@]+" value="{{old('first_name')}}"/>
    
                    @error('first_name')
                        <p>{{$message}}</p>
                    @enderror
                </div>
                <div class="col-sm-6 mt-2">
                    <label for="last_name">{{ __('Last Name') }}</label>
                    <input type="text" class="col-12" name="last_name" pattern="[^0-9@]+" value="{{old('last_name')}}"/>
    
                    @error('last_name')
                        <p>{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class="row mb-0" style="box-sizing: content-box">
                <div class="col-sm-6 mt-2">
                    <label for="email">{{ __('Email') }}</label>
                    <input type="email" class="col-12" name="email" value="{{old('email')}}"/>
    
                    @error('email')
                        <p>{{$message}}</p>
                    @enderror
                </div>
                <div class="col-sm-6 mt-2">
                    <label for="contact">{{ __('Contact Number') }}</label>
                    <input type="tel" class="col-12" name="contact" pattern="^(09)\d{9}$" value="{{old('contact')}}"/>

                    @error('contact')
                        <p>{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class="row mb-0" style="box-sizing: content-box">
                <div class="col-sm-6 mt-2">
                    <label for="username">{{ __('Username') }}</label>
                    <input type="text" class="col-12" name="username" value="{{old('username')}}"/>
    
                    @error('username')
                        <p>{{$message}}</p>
                    @enderror
                </div>
                <div class="col-sm-6 mt-2">
                    <label for="birthday">{{ __('Birthday') }}</label>
                    <input type="date" class="col-12" id="birthday" name="birthday" value="{{old('birthday')}}" onkeydown="return false" onclick="this.showPicker()" max="{{date('Y-m-d', strtotime('-18 year'))}}"/>

                    @error('birthday')
                        <p>{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class="row mb-0 border-bottom pb-4" style="box-sizing: content-box">
                <div class="col-sm-6 mt-2">
                    <label for="password">{{ __('Password') }}</label>
                    <input type="password" class="col-12" name="password"/>
    
                    @error('password')
                        <p>{{$message}}</p>
                    @enderror
                </div>
                <div class="col-sm-6 mt-2">
                    <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                    <input type="password" class="col-12" name="password_confirmation"/>
    
                    @error('password_confirmation')
                        <p>{{$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="row">
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <x-jet-checkbox name="terms" id="terms"/>
                <p class="text-dark mt-2">
                    By joining, you are agreeing to the PUPSJ Hotel <a href="">Terms & Conditions</a>. We collect and use your personal information in accordance<br>with our <a href="">Privacy Policies</a>.
                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                            ]) !!}
                </p>
                @endif
                <div class="col-12 col-md-3 mt-3">
                    <button type="submit" class="btn btn-secondary rounded-0 w-100">{{ __('Register') }}</button>
                </div>
                <div class="col-12 col-sm-6 mt-sm-2 pt-3 ps-sm-0">
                    <p>Already a member?
                        <a href="{{ route('login') }}">Login</a>
                    </p>
                </div>
            </div>
        </form>
    </div>
</section>
@include('home.navbar-script')
</body>
</html>
