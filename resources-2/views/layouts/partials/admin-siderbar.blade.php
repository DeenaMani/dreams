
<aside class="side-navbar">
         <div class="scroll-content" id="metismenu">
            <ul id="side-menu" class="metismenu list-unstyled">
               <li class="side-nav-title side-nav-item menu-title">Menu</li>
               <li>
                  <a href="{{url('admin/dashboard')}}" class="side-nav-link" aria-expanded="false">
                     <i class="bx bx-home-circle"></i>
                     <span> Dashboard</span>
                  </a>
               </li>
               <li>
                  <a class="side-nav-link" href="{{url('admin/bookings')}}">
                     <i class="bx bx-cart"></i>
                     <span> Purchased  </span>
                  </a>
               </li> 
               <li>
                  <a class="side-nav-link" href="{{url('admin/student')}}">
                     <i class="bx bx-user"></i>
                     <span> Users </span>
                  </a>
               </li> 
               <li>
                  <a href="{{url('admin/banner')}}" class="side-nav-link" aria-expanded="false">
                     <i class="bx bx-layout"></i>
                     <span> Banner </span>
                  </a>
               </li>
              
               <li class="">
		            <a href="javascript:void(0);" class="side-nav-link" aria-expanded="false">
                     <i class="bx bxs-server"></i>
		               <span> Course Management</span>
		               <span class="menu-arrow"></span>
		            </a>
		            <ul aria-expanded="false" class="nav-second-level mm-collapse">
                     <li class="side-nav-item">
                        <a class="side-nav-link" href="{{ url('admin/category') }}"> Categories </a>
                     </li>
		               <li class="side-nav-item">
		                  <a class="side-nav-link" href="{{ url('admin/course') }}"> Course List</a>
		               </li>
                     
		               <li class="side-nav-item">
		                  <a class="side-nav-link" href="{{ url('admin/topic') }}"> Topics </a>
                     </li>
		               <!-- <li class="side-nav-item">
		                  <a class="side-nav-link" href="{{ url('admin/resource') }}"> Resources  </a>
		               </li> -->
		            </ul>
		         </li> 
               <!-- <li>
                  <a class="side-nav-link" href="{{url('admin/curricullum')}}">
                     <i class="bx bx-calendar"></i>
                     <span> Curricullum</span>
                  </a>
               </li> -->
               <li>
                  <a href="{{ url('admin/instructor') }}" class="side-nav-link" aria-expanded="false">
                     <i class="bx bxs-contact"></i>
                     <span> Teachers </span>
                  </a>
               </li> 
               <li>
                  <a href="{{ url('admin/faq') }}" class="side-nav-link" aria-expanded="false">
                     <i class="bx bx-chat"></i>
                     <span> Faqs </span>
                  </a>
               </li>
              <!--  <li>
                  <a class="side-nav-link" href="{{url('admin/forms')}}">
                     <i class="bx bx-file"></i>
                     <span> Forms</span>
                  </a>
               </li> -->
               <li class="">
                  <a href="javascript:void(0);" class="side-nav-link" aria-expanded="false">
                     <i class="bx bxs-server"></i>
                     <span>Home Page</span>
                     <span class="menu-arrow"></span>
                  </a>
                  <ul aria-expanded="false" class="nav-second-level mm-collapse">
                     <li class="side-nav-item">
                        <a href="{{ url('admin/about') }}" class="side-nav-link" aria-expanded="false">
                           <!-- <i class="fe-package"></i> -->
                           <span> About </span>
                        </a>
                     </li>
                     <li class="side-nav-item">
                         <a href="{{ url('admin/whychoose') }}" class="side-nav-link" aria-expanded="false">
                        <!-- <i class="bx bx-circle"></i> -->
                        <span>Why Choose </span>
                        </a>
    
                     </li>
                     <li class="side-nav-item">
                          <a href="{{ url('admin/our-values') }}" class="side-nav-link" aria-expanded="false">
                           <!-- <i class="bx bx-circle"></i> -->
                           <span> Our Values </span>
                        </a>

                     </li>
                  </ul>
               </li> 
               <li>
                  <a class="side-nav-link" href="{{url('admin/live-class')}}">
                     <i class="bx bx-stop"></i>
                     <span> Live Class</span>
                  </a>
               </li>
               <li>
                  <a class="side-nav-link" href="{{url('admin/competitite-exam')}}">
                     <i class="bx bx-spreadsheet"></i>
                     <span> Competitite Exams </span>
                  </a>
               </li>  
               <li>
                  <a class="side-nav-link" href="{{url('admin/terms')}}">
                     <i class="bx bx-info-circle"></i>
                     <span> Legal Terms</span>
                  </a>
               </li>              
            </ul>
         </div>
      </aside>