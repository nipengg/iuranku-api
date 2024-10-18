@extends('layouts.admin')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <form class="d-flex">
                        <div class="input-group">
                            <div class="mb-3 position-relative" id="datepicker6">
                                <label class="form-label">Year View</label>
                                <input id="yearSelect" type="text" value="{{ $year }}" class="form-control"
                                    data-provide="datepicker" data-date-min-view-mode="2" data-date-container="#datepicker6"
                                    onchange="handleSelectChange()">
                            </div>
                        </div>
                    </form>
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-5 col-lg-6">

            <div class="row">
                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-account-multiple widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Users</h5>
                            <h3 class="mt-3 mb-3">{{ $userCount }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-nowrap">Total User Count</span>
                            </p>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->

                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-cart-plus widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Groups</h5>
                            <h3 class="mt-3 mb-3">{{ $groupCount }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-nowrap">Total Group Count</span>
                            </p>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div> <!-- end row -->

            <div class="row">
                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-currency-usd widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Average Revenue">Tuition</h5>
                            <h3 class="mt-3 mb-3">0</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-nowrap">All Total Tuition</span>
                            </p>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->

                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-pulse widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Growth">Invite</h5>
                            <h3 class="mt-3 mb-3">0</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-nowrap">All Invitation Count</span>
                            </p>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div> <!-- end row -->

        </div> <!-- end col -->

        <div class="col-xl-7 col-lg-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <h4 class="header-title mb-3">Graphic Chart</h4>
                    <div dir="ltr">
                        <div id="high-performing-product" class="apex-charts" data-colors="#727cf5,#e3eaef"></div>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->

        </div> <!-- end col -->
    </div>
    <!-- end row -->
@endsection

@section('script')
    <script type="text/javascript">
        function handleSelectChange(event) {
            let valueYear = document.getElementById("yearSelect").value;
            var splitArray = new Array();
            splitArray = valueYear.split("/");
            window.location.href = "{{ url('/admin?year=') }}" + valueYear;
        }
    </script>
@endsection
