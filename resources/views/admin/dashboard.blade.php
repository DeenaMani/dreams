@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                    <div class="page-title">
                        <h3 class="mb-1 font-weight-bold">Admin Panel</h3>
                        <ol class="breadcrumb mb-3 mb-md-0">
                            <li class="breadcrumb-item active">Welcome to Admin Dashboard</li>
                        </ol>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-wrapper mt--45">
           <div class="container-fluid">
              <!-- Widget  -->
              <div class="row">
                 <div class="col-md-6 col-xl-3">
                    <div class="card">
                       <div class="card-body">
                          <div class="media align-items-center flex-wrap">
                             <div class="media-body">
                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Total
                                   Purchase</span>
                                <h2 class="mb-0 mt-1">{{$purchase}}</h2>
                             </div>
                             <div class="align-self-center">
                                <span class="text-primary">
                                   <i class="bx bxs-shopping-bags fs-lg"></i>
                                </span>
                             </div>
                             
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="col-md-6 col-xl-3">
                    <div class="card">
                       <div class="card-body">
                          <div class="media align-items-center flex-wrap">
                             <div class="media-body">
                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Total
                                   User</span>
                                <h2 class="mb-0 mt-1">{{$student}}</h2>
                             </div>
                             <div class="align-self-center">
                                <span class="text-primary">
                                   <i class="bx bx-user-plus fs-lg"></i>
                                </span>
                             </div>
                             
                          </div>
                       </div>
                    </div>
                 </div>
               
                 <div class="col-md-6 col-xl-3">
                    <div class="card">
                       <div class="card-body">
                          <div class="media align-items-center flex-wrap">
                             <div class="media-body">
                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Total
                                   Teachers</span>
                                <h2 class="mb-0 mt-1">{{$teacher}}</h2>
                             </div>
                             <div class="align-self-center">
                                <span class="text-primary">
                                   <i class="bx bxs-user-badge fs-lg"></i>
                                </span>
                             </div>
                            
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="col-md-6 col-xl-3">
                    <div class="card">
                       <div class="card-body">
                          <div class="media align-items-center flex-wrap">
                             <div class="media-body">
                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Total
                                   Mentor</span>
                                <h2 class="mb-0 mt-1">{{$mentor}}</h2>
                             </div>
                             <div class="align-self-center">
                                <span class="text-primary">
                                   <i class="bx bxs-user-badge fs-lg"></i>
                                </span>
                             </div>
                            
                          </div>
                       </div>
                    </div>
                 </div>

                   <div class="col-md-6 col-xl-3">
                    <div class="card">
                       <div class="card-body">
                          <div class="media align-items-center flex-wrap">
                             <div class="media-body">
                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Total
                                   Purchased Amount </span>
                                <h2 class="mb-0 mt-1">{{get_price($total_amount)}}</h2>
                             </div>
                             <div class="align-self-center">
                                <span class="text-primary">
                                   <i class="bx bxs-cart fs-lg"></i>
                                </span>
                             </div>
                            
                          </div>
                       </div>
                    </div>
                 </div>

              </div>
          </div>
      </div>
</div>
@endsection