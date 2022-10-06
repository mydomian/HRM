@extends('layouts.admin_layouts.admin_dashboard')
@section('content')



<!-- Page Header-->
        <div class="bg-dash-dark-2 py-4">
            <div class="container-fluid">
            <h2 class="h5 mb-0">Packages Request</h2>
            </div>
        </div>
        <section>
          <div class="container-fluid">
            <div class="row gy-4">
              <div class="col-12">
                <div class="card" style="">
                  <div class="card-body">
                    <div class="table-responsive">

                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">SL</th>
                                        <th scope="col" class="text-center">User</th>
                                        <th scope="col" class="text-center">Package</th>
                                        <th scope="col" class="text-center">Company</th>
                                        <th scope="col" class="text-center">Name</th>
                                        <th scope="col" class="text-center">Email</th>
                                        <th scope="col" class="text-center">Password</th>
                                        <th scope="col" class="text-center">Database</th>
                                        <th scope="col" class="text-center">Payment</th>
                                        <th scope="col" class="text-center">Account</th>
                                        <th scope="col" class="text-center">Transaction</th>
                                        <th scope="col" class="text-center">Amount</th>
                                        <th scope="col" class="text-center">Duration</th>
                                        <th scope="col" class="text-center">Start</th>
                                        <th scope="col" class="text-center">End</th>
                                        <th scope="col" class="text-center">Date</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Action</th>
                                      </tr>
                                </thead>
                                <tbody>
                                  @php
                                      $i = 1;
                                  @endphp
                                  @foreach ($package_requests as $package_request)
                                      <tr>
                                          <td class="text-center">{{ $i++ }}</td>
                                          <td class="text-center">{{ $package_request['id'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['packages']['package_name'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['company_name'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['name'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['email'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['password'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['database_name'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['payment_type'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['account_no'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['transaction_id'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['packages']['package_price'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['duration'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['start_date'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['end_date'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_request['date'] ?? ""}}</td>
                                          <td class="text-center"><span class="badge badge-info">{{ $package_request['status'] ?? ""}}</span></td>
                                          <td class="text-center">
                                              <div class="row">
                                                  <div class="col-sm-9">
                                                    <div class="input-group">
                                                      <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Activation</button>
                                                      <ul class="dropdown-menu dropdown-menu-dark shadow-sm">
                                                        <li><a class="dropdown-item package_activation" href="javascript:;" recordid="{{ $package_request['id'] }}">Activate</a></li>
                                                        <li><a class="dropdown-item" href="#">Deactivate</a></li>
                                                      </ul>
                                                    </div>
                                                  </div>
                                                </div>
                                              {{--  <a href="javascript:;" class="btn btn-outline-warning btn-sm package_activation" recordid="{{ $package_request['id'] }}" title="Package Activation"><i class="fa fa-check"></i></a>  --}}
                                          </td>
                                      </tr>
                                  @endforeach

                                </tbody>
                        </table>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>




@endsection
