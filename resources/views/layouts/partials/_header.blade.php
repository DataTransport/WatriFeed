{{--header--}}
<div class="row" style="margin-bottom: 5px;">
    <div class="col-md-12" style="text-align: center; color: black; font-weight: bold; font-size: 14px">
       Your API-KEY : <span style="color: #158cea">{{\Illuminate\Support\Facades\Auth::user()->key_api}}</span>
    </div>
    <!-- Profile Info and Notifications -->
    <div class="col-md-6 col-sm-8 clearfix">

        <ul class="user-info pull-left pull-none-xsm">

            <!-- Profile Info -->
            <li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->

                <a href="#" class="dropdown-toggle" data-toggle="dropdown">

{{--                    <img src="/neon/images/logo_billet_express.png" alt="" class="img-circle" width="44" />--}}
                    <strong style="color: #0078d8;font-size: 16px;">{{ Auth::user()->name }}</strong>
                </a>

                <ul class="dropdown-menu">

                    <!-- Reverse Caret -->
                    <li class="caret"></li>

                    <!-- Profile sub-links -->
                    <li>
                        <a href="{{route('users.edit')}}">
                            <i class="entypo-user"></i>
                            Edit Profile
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

    </div>



    <!-- Raw Links -->
    <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right">


            <li>


                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    {{ __('Log Out') }} <i class="entypo-logout right"></i>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>

    </div>

</div>
