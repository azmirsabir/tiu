<?php use Illuminate\Support\Facades\Auth;?>
<!-- Sidebar -->
<ul class="navbar-nav fixed-sidebar sidebar sidebar-dark accordion toggled korek_blue" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center app_container_nav" data-route="/reviews">
        <div class="sidebar-brand-icon">
            <img src="https://tiu.edu.iq/wp-content/uploads/2019/11/188-57.png" width="100px" style="background-color: white"/>
{{--            <i class="fal fa-passport"></i>--}}
        </div>
        <div class="sidebar-brand-text mx-3">TIU <sup>1</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0 mt-2">

    <li class="nav-item">
        <a id="reviews-nav-item" class="nav-link app_container_nav" data-route="/reviews">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Reviews</span></a>
    </li>

    @if(Auth::user()->type=="admin")
        <li class="nav-item">
            <a id="feedbacks-nav-item" class="nav-link app_container_nav" data-route="/feedbacks">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Feedbacks</span></a>
        </li>
        <li class="nav-item">
            <a id="cards-nav-item" class="nav-link app_container_nav" data-route="/cards">
                <i class="fas fa-list-ol"></i>
                <span>Cards</span></a>
        </li>
    <hr class="sidebar-divider my-0 mt-2">
    <li class="nav-item">
        <a id="users-nav-item" class="nav-link app_container_nav" data-route="/users">
            <i class="fa fa-users"></i>
            <span>User Management</span></a>
    </li>
    @endif

</ul>

<script>

</script>
<!-- End of Sidebar -->

