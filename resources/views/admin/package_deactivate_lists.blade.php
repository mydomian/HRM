@extends('layouts.admin_layouts.admin_dashboard')
@section('content')

<!-- Page Header-->
        <div class="bg-dash-dark-2 py-4">
            <div class="container-fluid">
            <h2 class="h5 mb-0">Packages Deactivated Lists</h2>
            </div>
        </div>
        <section>
          <div class="container-fluid">
            <div class="row gy-4">
              <div class="col-12">
                <div class="card" style="">
                  <div class="card-body">
                    <div class="table-responsive">

                        <table id="example" class="table table-dark" style="width:100%">
                                <thead style="color:#b2b5bd">
                                    <tr>
                                        <th scope="col" class="text-center">Id</th>
                                        <th scope="col" class="text-center">Name</th>
                                        <th scope="col" class="text-center">Email</th>
                                        <th scope="col" class="text-center">Password</th>
                                        <th scope="col" class="text-center">Package</th>
                                        <th scope="col" class="text-center">Payment</th>
                                        <th scope="col" class="text-center">Account No</th>
                                        <th scope="col" class="text-center">Transaction</th>
                                        <th scope="col" class="text-center">Date</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Action</th>
                                      </tr>
                                </thead>
                                <tbody style="color:#8e94a2">
                                  @php
                                      $i = 1;
                                  @endphp
                                  @foreach ($package_deactivated_lists as $package_deactivated_list)
                                      <tr>
                                          <td class="text-center">{{ $package_deactivated_list['id'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_deactivated_list['name'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_deactivated_list['email'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_deactivated_list['password'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_deactivated_list['packages']['package_name'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_deactivated_list['payment_type'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_deactivated_list['account_no'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_deactivated_list['transaction_id'] ?? ""}}</td>
                                          <td class="text-center">{{ $package_deactivated_list['date']->format('Y-m-d') ?? ""}}</td>
                                          <td class="text-center"><span class="badge badge-info">{{ $package_deactivated_list['status'] ?? ""}}</span></td>
                                          <td class="text-center">
                                              <div class="row">
                                                  <div class="col-sm-12 d-flex">
                                                    <div class="input-group">
                                                      <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Activation</button>
                                                      <ul class="dropdown-menu dropdown-menu-dark shadow-sm">
                                                        <li><a class="dropdown-item package_activation" href="javascript:;" recordid="{{ $package_deactivated_list['id'] }}">Activate</a></li>
                                                        <li><a class="dropdown-item package_deactivation" href="javascript:;" recordid="{{ $package_deactivated_list['id'] }}">Deactivate</a></li>
                                                      </ul>
                                                    </div>
                                                    <a href="javascript:;" class="btn btn-primary text-white package_view" recordid="{{ $package_deactivated_list['id'] }}" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fa fa-eye">View</i></a>
                                                  </div>
                                                </div>
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

        {{--  view details  --}}
        <div class="modal fade text-start" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="myModalLabel">User Package Details</h5>
                  <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                      <label class="form-label" for="company_name">Company Name</label>
                      <input class="form-control input_text_color" id="company_name" type="text">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="duration">Duration</label>
                        <input class="form-control input_text_color" id="duration" type="text">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="start_date">Start Date</label>
                        <input class="form-control input_text_color" id="start_date" type="text">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="end_date">End Date</label>
                        <input class="form-control input_text_color" id="end_date" type="text">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="database_name">Database Name</label>
                        <input class="form-control input_text_color" id="database_name" type="text">
                    </div>

                </div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

@endsection
