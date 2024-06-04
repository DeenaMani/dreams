 
@php $current_url = request()->segment(1) @endphp
 <aside class="sidebar">
    <ul class="list-unstyled">
        <li class="{{@$current_url == 'dashboard' ? 'active' :'';}}">
            <a href="{{url('dashboard')}}">Dashboard</a>
        </li>
        <!-- <li class="{{@$current_url == 'profile' ? 'active' :'';}}">
            <a href="{{url('profile')}}">Account Details</a>
        </li> -->
        <li class="{{@$current_url == 'transaction' ? 'active' :'';}}">
            <a href="{{url('transaction')}}">Purchase History</a>
        </li>
        <li class="{{@$current_url == 'change-password' ? 'active' :'';}}">
            <a href="{{url('change-password')}}">Change Password</a>
        </li>
        <li class="{{@$current_url == 'logout' ? 'active' :'';}}">
            <a href="{{url('logout')}}">Logout</a>
        </li>
    </ul>
</aside>
