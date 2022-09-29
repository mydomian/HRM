@extends('layouts.admin_layouts.admin_dashboard')
@section('content')
    <div class="bg-dash-dark-2 py-4">
        <div class="container-fluid">
            <h2 class="h5 mb-0">PACKAGE REQUEST</h2>
        </div>
    </div>
        <div class="row m-5">
            <div class="card">
                <div class="card-body">
                    <table id="example" class="table table-dark">
                        <thead style="color:#b2b5bd">
                          <tr>
                            <th scope="col" class="text-center">SL</th>
                            <th scope="col" class="text-center">User Id</th>
                            <th scope="col" class="text-center">Package Id</th>
                            <th scope="col" class="text-center">Company Name</th>
                            <th scope="col" class="text-center">Payment Type</th>
                            <th scope="col" class="text-center">Account No</th>
                            <th scope="col" class="text-center">Transaction Id</th>
                            <th scope="col" class="text-center">Amount</th>
                            <th scope="col" class="text-center">Duration</th>
                            <th scope="col" class="text-center">Start Date</th>
                            <th scope="col" class="text-center">End Date</th>
                            <th scope="col" class="text-center">Date</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody style="color:#8e94a2">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($package_requests as $package_request)
                                @php
                                 $start_date = \Carbon\Carbon::now()->format('Y-m-d');
                                 $end_date = \Carbon\Carbon::parse($start_date)->addDays($package_request['duration'])->format('Y-m-d');
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td class="text-center">{{ $package_request['user_id'] }}</td>
                                    <td class="text-center">{{ $package_request['package_id'] }}</td>
                                    <td class="text-center">{{ $package_request['company_name'] }}</td>
                                    <td class="text-center">{{ $package_request['payment_type'] }}</td>
                                    <td class="text-center">{{ $package_request['account_no'] }}</td>
                                    <td class="text-center">{{ $package_request['transaction_id'] }}</td>
                                    <td class="text-center">{{ $package_request['amount'] }}</td>
                                    <td class="text-center">{{ $package_request['duration'] }}</td>
                                    <td class="text-center">{{ $start_date }}</td>
                                    <td class="text-center">{{ $end_date }}</td>
                                    <td class="text-center">{{ $package_request['date'] }}</td>
                                    <td class="text-center">{{ $package_request['status'] }}</td>
                                    <td class="text-center">
                                        <a href="javascript:;" class="btn btn-outline-warning btn-sm package_activation" recordid="{{ $package_request['id'] }}" title="Package Activation"><i class="fa fa-check"></i></a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                      </table>
                </div>
            </div>
        </div>

@endsection
