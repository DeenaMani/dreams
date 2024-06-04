

<header id="page-topbar" class="topbar-header">
         <div class="navbar-header">
            <div class="left-bar">
               <div class="navbar-brand-box">
                  <a href="{{url('admin/')}}" class="logo logo-dark">
                     <span class="logo-sm"><img src="{{url('/public/image/setting/'.$setting->logo)}}" alt="{{$setting->company_name}}"></span>
                     <span class="logo-lg"><img src="{{url('/public/image/setting/'.$setting->logo)}}" alt="{{$setting->company_name}}"></span>
                  </a>
                  <a href="{{url('admin/')}}" class="logo logo-light">
                     <span class="logo-sm"><img src="{{url('/public/image/setting/'.$setting->logo)}}" alt="{{$setting->company_name}}"></span>
                     <span class="logo-lg"><img src="{{url('/public/image/setting/'.$setting->logo)}}" alt="{{$setting->company_name}}"></span>
                  </a>
               </div>
               <button type="button" id="vertical-menu-btn" class="btn hamburg-icon">
                  <i class="mdi mdi-menu"></i>
               </button>
              
            </div>
            <div class="right-bar">
               <div class="d-inline-flex ml-0 ml-sm-2 d-lg-none dropdown">
                  <button data-toggle="dropdown" aria-haspopup="true" type="button" id="page-header-search-dropdown"
                     aria-expanded="false" class="btn header-item notify-icon">
                     <i class="bx bx-search"></i>
                  </button>
                  <div aria-labelledby="page-header-search-dropdown"
                     class="dropdown-menu-lg dropdown-menu-right p-0 dropdown-menu">
                     <form class="p-3">
                        <div class="search-box">
                           <div class="position-relative">
                              <input type="text" placeholder="Search..." class="form-control form-control-sm">
                              <i class="bx bx-search icon"></i>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="d-inline-flex ml-0 ml-sm-2 dropdown">
                 
               </div>
               
               <div class="d-none d-lg-inline-flex ml-2">
                  <button type="button" data-toggle="fullscreen" class="btn header-item notify-icon" id="full-screen">
                     <i class="bx bx-fullscreen"></i>
                  </button>
               </div>
              
               <div class="d-inline-flex ml-0 ml-sm-2 dropdown">
                  <button data-toggle="dropdown" aria-haspopup="true" type="button" id="page-header-profile-dropdown"
                     aria-expanded="false" class="btn header-item">
                    
                     <span class="d-none d-xl-inline-block ml-1">Admin</span>
                     <i class="bx bx-chevron-down d-none d-xl-inline-block"></i>
                  </button>
                  <div aria-labelledby="page-header-profile-dropdown" class="dropdown-menu-right dropdown-menu">
                     <a href="{{url('admin/profile')}}" class="dropdown-item">
                        <i class="bx bx-user mr-1"></i> Profile
                     </a>
                     <a href="{{url('/')}}/admin/change-password" class="dropdown-item">
                        <i class="bx bx-lock mr-1"></i> Change Password
                     </a>
                    
                     <div class="dropdown-divider"></div>
                     <a href="{{url('/')}}/admin/logout" class="text-danger dropdown-item">
                        <i class="bx bx-log-in mr-1 text-danger"></i> Logout
                     </a>
                  </div>
               </div>
               <div class="d-inline-flex">
                  <a href="{{url('admin/setting/1/edit')}}" id="layout" class="btn header-item notify-icon">
                     <i class="bx bx-cog bx-spin"></i>
                  </a>
               </div>
            </div>
         </div>
      </header>