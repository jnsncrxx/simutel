<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PUPSJ Hotel</title>
    @include('home.account.css')
    @include('home.rooms.css')
</head>
<body>
    @include('home.navbar')
    <section style="margin-top: 60px">
        <div class="container px-0 py-4">
            <div class="d-flex border-bottom">
                <h4 class="m-0" style="color: #cda934">My Account</h4>
                <div class="align-items-center d-flex ms-2 mt-1">
                    <a class="account-tab pb-1 px-0 mx-2 {{($_GET['tab'] == 'reservations')?'active':''}}" href='/account?tab=reservations&reservations=upcoming'>Reservations</a>
                    <a class="account-tab pb-1 px-0 mx-2 {{($_GET['tab'] == 'membership')?'active':''}}" href='/account?tab=membership'>Membership</a>
                </div>
            </div>
            @if($_GET['tab'] == 'reservations')
                <div class="reservations">
                    @include('home.account.reservations')
                </div>
            @elseif($_GET['tab'] == 'membership')
                <div class="membership">
                    @include('home.account.membership')
                </div>
            @endif
        </div>
    </section>
@include('home.navbar-script')
</body>
</html>