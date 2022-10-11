@extends('layouts.admin_layouts.admin_dashboard')
@section('content')
    <div class="bg-dash-dark-2 py-4">
        <div class="container-fluid">
            <h2 class="h5 mb-0">ROLE PERMISSION LISTS</h2>
        </div>
    </div>
        <div class="row m-5">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-success float-right" type="button" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fas fa-plus"></i>ADD NEW</button>
                </div>
                <div class="card-body">
                    <table id="example" class="table table-dark" style="width:100%">
                        <thead style="color:#b2b5bd">
                          <tr>
                            <th scope="col" class="text-center">SL</th>
                            <th scope="col" class="text-center">Name</th>
                            <th scope="col" class="text-center">Email</th>
                            <th scope="col" class="text-center">Phone</th>
                            <th scope="col" class="text-center">Role</th>
                            <th scope="col" class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody style="color:#8e94a2">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($roles as $role)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td class="text-center">{{ $role['name'] }}</td>
                                    <td class="text-center">{{ $role['email'] }}</td>
                                    <td class="text-center">{{ $role['phone'] }}</td>
                                    <td class="text-center">{{ $role['role_as'] }}</td>
                                    <td class="text-center">
                                        <a href="javascript:;" class="btn btn-outline-info btn-sm role-edit" recordid="{{$role['id']}}" title="Role Edit" data-bs-toggle="modal" data-bs-target="#myModal1"><i class="fas fa-edit"></i>Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
        {{--  Role create  --}}
        <!-- Modal-->
        @if (count($errors) > 0)
        <div class="modal fade text-start show" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" style="display: block; padding-left: 0px;" aria-modal="true" role="dialog">
        @else
        <div class="modal fade text-start" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        @endif

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Role Assign</h5>
                    <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form  action="{{ url('/admin/role-create') }}" method="POST">
                        @csrf

                        @if (count($errors) > 0)
                        <div class = "alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="mb-3">
                        <label class="form-label" for="RoleSelect">Role Select</label>
                        <select class="form-control" id="role_as" name="role_as" required>
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Moderator">Moderator</option>
                        </select>
                        </div>
                        <div class="mb-3">
                        <label class="form-label" for="RoleName">Name</label>
                        <input class="form-control" type="text" name="name" placeholder="Enter name..." required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label" for="RoleEmail">Email</label>
                        <input class="form-control" type="email" name="email" placeholder="Enter email..." required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label" for="RolePhone">Phone</label>
                        <input class="form-control" type="number" name="phone" placeholder="Enter phone..." required>
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
        {{--  role Update  --}}
        <!-- Modal-->
        <div class="modal fade text-start" id="myModal1" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Create a new package</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form  action="{{ url('/admin/role-update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role_id" id="role_id">
                    <div class="mb-3">
                    <label class="form-label" for="role_select">Role Select</label>
                    <select class="form-control" name="role_as" id="role_select" required>
                        <option value="">Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Moderator">Moderator</option>
                    </select>
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="RoleName">Name</label>
                    <input class="form-control" type="text" name="name" id="role_name" placeholder="Enter name..." required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="RoleEmail">Email</label>
                    <input class="form-control" type="email" name="email" id="role_email" placeholder="Enter email..." required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="RolePhone">Phone</label>
                    <input class="form-control" type="number" name="phone" id="role_phone" placeholder="Enter phone..." required>
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
