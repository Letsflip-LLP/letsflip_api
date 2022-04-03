@extends ('admin.layout',array())
@section('wrapper')

<!-- ALL TOTAL -->
<!-- ============================================================================================================== -->
<div class="row">

    <!-- USERS -->
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-account-multiple text-danger icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Users</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$allUsers->all()->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Total Users
                </p>
            </div>
        </div>
    </div>

    <!-- SUBSCRIBERS -->
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-star text-warning icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Subscribers</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$allSubcribers->all()->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Total Subscribers
                </p>
            </div>
        </div>
    </div>

    <!-- COMPANIES -->
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-desktop-mac text-success icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Companies</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$allCompanies->all()->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Total Companies
                </p>
            </div>
        </div>
    </div>

    <!-- ACTIVE CLASSROOMS -->
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-bulletin-board text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Active Classrooms</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$allClassrooms->all()->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Total Active Classrooms
                </p>
            </div>
        </div>
    </div>

    <!-- MISSIONS -->
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-motorbike text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Missions</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$allMissions->all()->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Total Missions
                </p>
            </div>
        </div>
    </div>

    <!-- MISSION RESPONSES -->
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-reply-all text-danger icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Mission Responses</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$allMissionResponses->all()->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Total Mission Responses
                </p>
            </div>
        </div>
    </div>

    <!-- ANSWERS -->
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-lightbulb-on-outline text-warning icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Answers</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$allAnswers->all()->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Total Answers
                </p>
            </div>
        </div>
    </div>

    <!-- COMMENTS -->
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-comment-multiple-outline text-success icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Comments</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$allComments->all()->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Total Mission Comments
                </p>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5 d-flex align-items-center">
                        <canvas id="MissionsPieChart" class="400x160 mb-4 mb-md-0" height="200"></canvas>
                    </div>
                    <div class="col-md-7">
                        <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Missions</h4>

                        <!-- PUBLIC MISSION -->
                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$allMissions->where('type', '1')->count()}}</p>
                                    <small class="text-muted ml-2">Public Mission</small>
                                </div>
                                <p class="mb-0 font-weight-medium">&plusmn; {{$publicMissionPercentageInt}}%</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{$publicMissionPercentage}}%" aria-valuenow="{{$publicMissionPercentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- PRIVATE MISSION -->
                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$allMissions->where('type', '2')->count()}}</p>
                                    <small class="text-muted ml-2">Private Mission</small>
                                </div>
                                <p class="mb-0 font-weight-medium">&plusmn; {{$privateMissionPercentageInt}}%</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{$privateMissionPercentage}}%" aria-valuenow="{{$privateMissionPercentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- MASTER MISSION -->
                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$allMissions->where('type', '3')->count()}}</p>
                                    <small class="text-muted ml-2">Master Mission</small>
                                </div>
                                <p class="mb-0 font-weight-medium">&plusmn; {{$masterMissionPercentageInt}}%</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{$masterMissionPercentage}}%" aria-valuenow="{{$masterMissionPercentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-6 col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5 d-flex align-items-center">
                        <canvas id="ClassroomsPieChart" class="400x160 mb-4 mb-md-0" height="200"></canvas>
                    </div>
                    <div class="col-md-7">
                        <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Classrooms</h4>

                        <!-- PUBLIC CLASSROOM -->
                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$allClassrooms->where('type', '1')->count()}}</p>
                                    <small class="text-muted ml-2">Public Classroom</small>
                                </div>
                                <p class="mb-0 font-weight-medium">&plusmn; {{$publicClassroomPercentageInt}}%</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{$publicClassroomPercentage}}%" aria-valuenow="{{$publicClassroomPercentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- PRIVATE CLASSROOM -->
                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$allClassrooms->where('type', '2')->count()}}</p>
                                    <small class="text-muted ml-2">Private Classroom</small>
                                </div>
                                <p class="mb-0 font-weight-medium">&plusmn; {{$privateClassroomPercentageInt}}%</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{$privateClassroomPercentage}}%" aria-valuenow="{{$privateClassroomPercentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- MASTER CLASSROOM -->
                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$allClassrooms->where('type', '3')->count()}}</p>
                                    <small class="text-muted ml-2">Master Classroom</small>
                                </div>
                                <p class="mb-0 font-weight-medium">&plusmn; {{$masterClassroomPercentageInt}}%</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{$masterClassroomPercentage}}%" aria-valuenow="{{$masterClassroomPercentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-7">
                        <h4 class="card-title font-weight-medium mb-3">Amount Due</h4>
                        <h1 class="font-weight-medium mb-0">$5998</h1>
                        <p class="text-muted">Milestone Completed</p>
                        <p class="mb-0">Payment for next week</p>
                    </div>
                    <div class="col-md-5 d-flex align-items-end mt-4 mt-md-0">
                        <canvas id="conversionBarChart" height="150"></canvas>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<script>
    var ctxP = document.getElementById("MissionsPieChart").getContext('2d');
    var myPieChart = new Chart(ctxP, {
        type: 'pie',
        data: {
            labels: ["Public", "Private", "Master"],
            datasets: [{
                data: [
                    <?php echo $allMissions->where('type', '1')->count() ?>,
                    <?php echo $allMissions->where('type', '2')->count() ?>,
                    <?php echo $allMissions->where('type', '3')->count() ?>,
                ],
                backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C"],
                hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870"]
            }]
        },
        options: {
            responsive: true,
            legend: false,
            plugins: {
                datalabels: {
                    formatter: (value, context) => {
                        return 100 + '%';
                    },
                    color: 'white',
                    labels: {
                        title: {
                            font: {
                                size: '10'
                            }
                        }
                    }
                }
            }
        }
    });
</script>

<script>
    var ctxP = document.getElementById("ClassroomsPieChart").getContext('2d');
    var myPieChart = new Chart(ctxP, {
        type: 'pie',
        data: {
            labels: ["Public", "Private", "Master"],
            datasets: [{
                data: [
                    <?php echo $allClassrooms->where('type', '1')->count() ?>,
                    <?php echo $allClassrooms->where('type', '2')->count() ?>,
                    <?php echo $allClassrooms->where('type', '3')->count() ?>,
                ],
                backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C"],
                hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870"]
            }]
        },
        options: {
            responsive: true,
            legend: false,
        },
    });
</script>
@endsection