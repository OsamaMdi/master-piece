@extends('layouts.owner.app')

@section('content')
<!-- MAIN -->
<div class="main">
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- OVERVIEW -->
            <div class="panel panel-headline">
                <div class="panel-heading">
                    <h3 class="panel-title">Weekly Overview</h3>
                    <p class="panel-subtitle">Period: Oct 14, 2016 - Oct 21, 2016</p>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="metric">
                                <span class="icon"><i class="fa fa-download"></i></span>
                                <p>
                                    <span class="number">1,252</span>
                                    <span class="title">Downloads</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric">
                                <span class="icon"><i class="fa fa-shopping-bag"></i></span>
                                <p>
                                    <span class="number">203</span>
                                    <span class="title">Sales</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric">
                                <span class="icon"><i class="fa fa-eye"></i></span>
                                <p>
                                    <span class="number">274,678</span>
                                    <span class="title">Visits</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric">
                                <span class="icon"><i class="fa fa-bar-chart"></i></span>
                                <p>
                                    <span class="number">35%</span>
                                    <span class="title">Conversions</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                            <div id="headline-chart" class="ct-chart"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="weekly-summary text-right">
                                <span class="number">2,315</span>
                                <span class="percentage"><i class="fa fa-caret-up text-success"></i> 12%</span>
                                <span class="info-label">Total Sales</span>
                            </div>
                            <div class="weekly-summary text-right">
                                <span class="number">$5,758</span>
                                <span class="percentage"><i class="fa fa-caret-up text-success"></i> 23%</span>
                                <span class="info-label">Monthly Income</span>
                            </div>
                            <div class="weekly-summary text-right">
                                <span class="number">$65,938</span>
                                <span class="percentage"><i class="fa fa-caret-down text-danger"></i> 8%</span>
                                <span class="info-label">Total Income</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END OVERVIEW -->

            <div class="row">
                <div class="col-md-6">
                    <!-- RECENT PURCHASES -->
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Recent Purchases</h3>
                            <div class="right">
                                <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                                <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
                            </div>
                        </div>
                        <div class="panel-body no-padding">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order No.</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Date &amp; Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="#">763648</a></td>
                                        <td>Steve Rogers</td>
                                        <td>$122</td>
                                        <td>Oct 21, 2016</td>
                                        <td><span class="label label-success">COMPLETED</span></td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">763649</a></td>
                                        <td>Bruce Banner</td>
                                        <td>$850</td>
                                        <td>Oct 20, 2016</td>
                                        <td><span class="label label-warning">PENDING</span></td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">763650</a></td>
                                        <td>Peter Parker</td>
                                        <td>$540</td>
                                        <td>Oct 17, 2016</td>
                                        <td><span class="label label-danger">FAILED</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-6"><span class="panel-note"><i class="fa fa-clock-o"></i> Last 24 hours</span></div>
                                <div class="col-md-6 text-right"><a href="#" class="btn btn-primary">View All Purchases</a></div>
                            </div>
                        </div>
                    </div>
                    <!-- END RECENT PURCHASES -->
                </div>

                <div class="col-md-6">
                    <!-- TO DO LIST -->
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">To-Do List</h3>
                            <div class="right">
                                <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                                <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled todo-list">
                                <li>
                                    <label class="fancy-checkbox">
                                        <input type="checkbox"><span>Make appointment</span>
                                    </label>
                                    <span class="todo-time">Today</span>
                                </li>
                                <li>
                                    <label class="fancy-checkbox">
                                        <input type="checkbox"><span>Send email</span>
                                    </label>
                                    <span class="todo-time">Today</span>
                                </li>
                                <li>
                                    <label class="fancy-checkbox">
                                        <input type="checkbox"><span>Create new task</span>
                                    </label>
                                    <span class="todo-time">Tomorrow</span>
                                </li>
                                <li>
                                    <label class="fancy-checkbox">
                                        <input type="checkbox"><span>Complete project</span>
                                    </label>
                                    <span class="todo-time">This week</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- END TO DO LIST -->
                </div>
            </div>

            <!-- RECENT USER ACTIVITY -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Recent User Activity</h3>
                            <div class="right">
                                <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                                <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled activity-list">
                                <li>
                                    <img src="assets/img/user1.png" alt="Avatar" class="img-circle pull-left avatar">
                                    <p><a href="#">Michael</a> has completed the task <a href="#">#New Design</a>.</p>
                                    <span class="timestamp">2 minutes ago</span>
                                </li>
                                <li>
                                    <img src="assets/img/user2.png" alt="Avatar" class="img-circle pull-left avatar">
                                    <p><a href="#">Sarah</a> uploaded a new file to <a href="#">Google Drive</a>.</p>
                                    <span class="timestamp">10 minutes ago</span>
                                </li>
                                <li>
                                    <img src="assets/img/user3.png" alt="Avatar" class="img-circle pull-left avatar">
                                    <p><a href="#">Michele</a> subscribed to your newsletter.</p>
                                    <span class="timestamp">1 hour ago</span>
                                </li>
                                <li>
                                    <img src="assets/img/user4.png" alt="Avatar" class="img-circle pull-left avatar">
                                    <p><a href="#">Diana</a> added a new comment to <a href="#">Website Feedback</a>.</p>
                                    <span class="timestamp">Yesterday</span>
                                </li>
                            </ul>
                            <div class="margin-top-30 text-center"><a href="#" class="btn btn-default">See all activity</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END RECENT USER ACTIVITY -->
        </div>
    </div>
</div>
<!-- END MAIN -->
@endsection
