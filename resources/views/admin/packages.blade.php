@extends('layouts.admin_layouts.admin_dashboard')
@section('content')
    <div class="bg-dash-dark-2 py-4">
        <div class="container-fluid">
            <h2 class="h5 mb-0">PACKAGES</h2>
        </div>
    </div>
        <div class="row m-5">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-success float-right" type="button" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fas fa-plus"></i>ADD NEW</button>
                </div>
                <div class="card-body">
                    <table id="example" class="table table-dark">
                        <thead style="color:#b2b5bd">
                          <tr>
                            <th scope="col" class="text-center">SL</th>
                            <th scope="col" class="text-center">Package Name</th>
                            <th scope="col" class="text-center">Package Price</th>
                            <th scope="col" class="text-center">Package Feature</th>
                            <th scope="col" class="text-center">Duration</th>
                            <th scope="col" class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody style="color:#8e94a2">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($packages as $package)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td class="text-center">{{ $package['package_name'] }}</td>
                                    <td class="text-center">{{ $package['package_price'] }}</td>
                                    <td class="text-center">{{ $package['package_feature'] }}</td>
                                    <td class="text-center">{{ $package['duration_days'] }}</td>
                                    <td class="text-center">
                                        <a href="javascript:;" class="btn btn-outline-info btn-sm package-edit" recordid="{{$package['id']}}" title="Package Edit" data-bs-toggle="modal" data-bs-target="#myModal1"><i class="fas fa-edit"></i>Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
        {{--  package create  --}}
        <!-- Modal-->
        <div class="modal fade text-start" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Create a new package</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form  action="{{ url('/admin/package-create') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                    <label class="form-label" for="PackageName">Package Name</label>
                    <input class="form-control" type="text" name="package_name" placeholder="Enter package name..." required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="PackagePrice">Package Price</label>
                    <input class="form-control" type="number" name="package_price" placeholder="Enter package price..." required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="PackagePrice">Package Feature</label>
                    <input class="form-control" type="text" name="package_feature" placeholder="Enter package feature..." required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="PackagePrice">Duration Days</label>
                    <input class="form-control" type="number" name="duration_days" placeholder="Enter package duration days..." required>
                    </div>
                </div>
                <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit">Create</button>
                </div>
                </form>
            </div>
            </div>
        </div>
        {{--  package Update  --}}
        <!-- Modal-->
        <div class="modal fade text-start" id="myModal1" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Create a new package</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form  action="{{ url('/admin/package-update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="package_id" id="PackageId">
                    <div class="mb-3">
                    <label class="form-label" for="PackageName">Package Name</label>
                    <input class="form-control" type="text" name="package_name" id="PackageName" placeholder="Enter package name..." required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="PackagePrice">Package Price</label>
                    <input class="form-control" type="number" name="package_price" id="PackagePrice" placeholder="Enter package price..." required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="PackageFeature">Package Feature</label>
                    <input class="form-control" type="text" name="package_feature" id="PackageFeature" placeholder="Enter package feature..." required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="PackageDuration">Duration Days</label>
                    <input class="form-control" type="number" name="duration_days" id="PackageDuration" placeholder="Enter package duration days..." required>
                    </div>
                </div>
                <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit">Save Changes</button>
                </div>
                </form>
            </div>
            </div>
        </div>
@endsection
