@extends( 'layouts.admin' )

@section('title','dashboard')

@section('style')

@endsection

@section('content')
<!--main content start-->
<div id="content" class="ui-content ui-content-aside-overlay">
                <div class="ui-content-body">

                    <div class="ui-container">

                        <!--page title and breadcrumb start -->
                        <div class="row">
                            <div class="col-md-8">
                                <h1 class="page-title"> Admin Dashboard
                                    <small></small>
                                </h1>
                            </div>
                            <div class="col-md-4">
                                <ul class="breadcrumb pull-right">
                                    <li>Home</li>
                                    <li><a class="active">Dashboard</a></li>
                                </ul>
                            </div>
                        </div>
                        <!--page title and breadcrumb end -->

                        
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="panel short-states bg-primary">
                                    <div class="pull-right state-icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <div class="panel-body">
                                        <h1 class="light-txt">{{$totalSubscription}}</h1>
                                        <div class=" pull-right">{{$totalSubscription}} <i class="fa fa-bolt"></i></div>
                                        <strong class="text-uppercase">All Subscriptions</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="panel short-states bg-success">
                                    <div class="pull-right state-icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <div class="panel-body">
                                        <h1 class="light-txt">{{$activeSubscription}}</h1>
                                        <div class=" pull-right">{{$activeSubscription}} <i class="fa fa-level-up"></i></div>
                                        <strong class="text-uppercase">Active Subscriptions</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="panel short-states bg-warning">
                                    <div class="pull-right state-icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <div class="panel-body">
                                        <h1 class="light-txt">{{$inactiveSubscription}}</h1>
                                        <div class=" pull-right">{{$inactiveSubscription}} <i class="fa fa-level-down"></i></div>
                                        <strong class="text-uppercase">Inactive Subscriptions</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        


                        <div class="row">
                            <div class="col-md-12">
                                @if(count($data) >= 1)
                                <div class="panel">
                                    <header class="panel-heading panel-border">
                                        Latest Subscriptions
                                        <span class="tools pull-right">
                                            <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                                            <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                                            <a class="close-box fa fa-times" href="javascript:;"></a>
                                        </span>
                                    </header>
                                    <div class="panel-body">
                                        <div class="order-short-info">
                                            <a href="{{url('/admin/newsletter-subscriptions')}}" class="pull-right pull-left-xs btn btn-primary btn-sm">View All Subscription</a>
                                        </div>
                                        <hr/>
                                        <div class="table-responsive">
                                            <table  class="table table-hover latest-order">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Created Date</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($data as $dt)
                                                <tr>
                                                    <td>{{$dt->name}}</td>
                                                    <td>{{$dt->email}}</td>
                                                    <td>{{$dt->created_at}}</td>
                                                    <td>
                                                        @if($dt->status==ACTIVE)
                                                        <span class="label label-success">active</span></td>
                                                        @else <span class="label label-warning">inactive</span> @endif
                                                    </td>
                                                </tr>
                                                @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>


                    </div>


                </div>
            </div>
            <!--main content end-->
@endsection

@section('script')

@endsection
