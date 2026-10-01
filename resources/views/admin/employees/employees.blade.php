@include('admin.css')
<style>
    @media only screen and (min-width: 576px) {
        .modal-dialog {
            max-width: 720px;
        }
    }
</style>
<body>
    @include('admin.employees.add_employees')
    @include('admin.employees.edit_employees')
    @include('admin.delete')
	<div class="main-wrapper">
	@include('admin.header')
	@include('admin.sidebar')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left">Employees</h4>
                            <a class="btn btn-primary float-right view button" data-toggle="modal" data-target="#add_employee">Add Employee</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form id="search-form" action="employees" method="GET">
                        <div class="row formtype">
                            <div class="col-md-2 px-md-0">
                                <div class="form-group">
                                    <select class="form-control" name="column">
                                        @if(isset($_GET["column"]))
                                            @php 
                                                $options = array('All', 'Employee ID', 'Name', 'Role');
                                                $output = '';
                                                for( $i=0; $i<count($options); $i++ ) {
                                                    $output .= '<option ' . ( $_GET["column"] == $options[$i] ? 'selected' : '' ) . '>' . $options[$i] . '</option>';
                                                }
                                                echo $output;
                                            @endphp
                                        @else
                                            <option>All</option>
                                            <option>Employee ID</option>
                                            <option>Name</option>
                                            <option>Role</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group position-relative">
                                    <input type="text" id="search-input" class="form-control" name="search" style="padding-right: 33px;" value="@php if(isset($_GET["search"])) echo $_GET["search"]; @endphp"/>
                                    <a href="/employees" id="clear" class="btn btn-outline-secondary d-none position-absolute py-0 px-1 w-auto mt-0 ml-2 col-md-6" style="bottom: 7px; right: 5px;">⨉</a>
                                    <script>
                                        const searchInput = document.getElementById('search-input')
                                              btnClear = document.getElementById('clear');

                                            if(searchInput.value)
                                                btnClear.classList.add('d-flex');

                                            searchInput.addEventListener('focus', ()=>{
                                                btnClear.classList.add('d-flex');
                                            });

                                            searchInput.addEventListener('blur', ()=>{
                                                if(!searchInput.value)
                                                    btnClear.classList.remove('d-flex');
                                            });
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3 pl-md-0">
                                <div class="form-group d-flex">
                                    <input type="button" onclick="validateForm()" class="btn btn-secondary mt-0 col-md-6 search_button" value="Search"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if(session()->has('message'))
                @include('admin.alert-success')
            @endif
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>Employee ID</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Contact</th>
                                            <th>Role</th>
                                            <th class="actions text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            if(isset($_GET["search"]) && $_GET["search"] != null){
                                                $employees = $employees->filter(function ($item) {
                                                    if($_GET["column"] == 'All'){
                                                        return 
                                                        $item->id == $_GET["search"] || 
                                                        str_contains(strtolower($item->first_name), strtolower($_GET["search"])) || 
                                                        str_contains(strtolower($_GET["search"]), strtolower($item->first_name)) || 
                                                        str_contains(strtolower($item->last_name), strtolower($_GET["search"])) ||
                                                        str_contains(strtolower($_GET["search"]), strtolower($item->last_name)) ||
                                                        str_contains(strtolower($item->role), strtolower($_GET["search"])) || 
                                                        str_contains(strtolower($_GET["search"]), strtolower($item->role));
                                                    }
                                                    else if($_GET["column"] == 'Employee ID'){
                                                        return 
                                                        $item->id == $_GET["search"];
                                                    }
                                                    else if($_GET["column"] == 'Name'){
                                                        return 
                                                        str_contains(strtolower($item->first_name), strtolower($_GET["search"])) || 
                                                        str_contains(strtolower($_GET["search"]), strtolower($item->first_name)) || 
                                                        str_contains(strtolower($item->last_name), strtolower($_GET["search"])) ||
                                                        str_contains(strtolower($_GET["search"]), strtolower($item->last_name));
                                                    }
                                                    else if($_GET["column"] == 'Role'){
                                                        return 
                                                        str_contains(strtolower($item->role), strtolower($_GET["search"])) || 
                                                        str_contains(strtolower($_GET["search"]), strtolower($item->role));
                                                    }
                                                })->values();
                                            }
                                        @endphp
                                        @foreach($employees as $employee)
                                        <tr> 
                                            <td>{{$employee->id}}</td>
                                            <td>{{ucfirst($employee->last_name)}}</td>
                                            <td>{{ucfirst($employee->first_name)}}</td>
                                            <td>{{$employee->contact}}</td>
                                            <td>
                                                @if($employee->role == 'Admin') {!!'<p class="btn btn-sm w-100 bg-success-light">'.$employee->role.'</p> '!!}
                                                @elseif($employee->role == 'Manager') {!!'<p class="btn btn-sm w-100 bg-info-light">'.$employee->role.'</p> '!!}
                                                @elseif($employee->role == 'Staff') {!!'<p class="btn btn-sm w-100 bg-primary-light">'.$employee->role.'</p> '!!}
                                                @elseif($employee->role == 'Accountant') {!!'<p class="btn btn-sm w-100 bg-warning-light">'.$employee->role.'</p> '!!}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v ellipse_color"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#edit_employee" onclick="
                                                            document.getElementById('edit-email-warn').classList.add('d-none');
                                                            document.getElementById('edit-username-warn').classList.add('d-none');
                                                            edit({{$employee->id}},'{{ucwords($employee->first_name)}}','{{ucwords($employee->last_name)}}','{{$employee->user->email}}','{{$employee->contact}}','{{$employee->user->username}}','{{$employee->birthday}}','{{$employee->role}}');
                                                        "><i class="fas fa-pencil-alt"></i> Edit</a>
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#delete" onclick="deleteEmployee({{$employee->id}},'{{ucwords($employee->first_name).' '.ucwords($employee->last_name)}}','{{$employee->user->profile_photo_url}}')"><i class="fas fa-trash-alt"></i> Delete</a> 
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
    </div>
</div>
    @include('admin.script')
<script>
    function validateForm(){
        var isValid = false;

        var elements = document.getElementById('search-form').querySelectorAll('input[type=text]');
        for(var i=0; i < elements.length; i++){
           if(elements[i].value && !(!elements[i].value.trim().length)){
               isValid= true;
           }
        }

        if(isValid){
            document.getElementById('search-form').submit();
        }
    }

document.getElementById('side-employees').classList.add('active');

    window.onload = function() {
        if (sessionStorage.getItem("addEmployee")) {
            $('#add_employee').modal('show');
            sessionStorage.removeItem("addEmployee");
        }
    }

    @error('edit_email')
        edit({{$message}},'{{ucwords($employees->find($message)->first_name)}}','{{ucwords($employees->find($message)->last_name)}}','{{$employees->find($message)->user->email}}','{{$employees->find($message)->contact}}','{{ucwords($employees->find($message)->user->username)}}','{{$employees->find($message)->birthday}}','{{$employees->find($message)->role}}');
        document.getElementById('edit-email-warn').classList.remove('d-none');
        $('#edit_employee').modal('show');
    @enderror

    @error('edit_username')
        edit({{$message}},'{{ucwords($employees->find($message)->first_name)}}','{{ucwords($employees->find($message)->last_name)}}','{{$employees->find($message)->user->email}}','{{$employees->find($message)->contact}}','{{ucwords($employees->find($message)->user->username)}}','{{$employees->find($message)->birthday}}','{{$employees->find($message)->role}}');
        document.getElementById('edit-username-warn').classList.remove('d-none');
        $('#edit_employee').modal('show');
    @enderror

    function edit(id,firstName,lastName,email,contact,username,birthday,role){
        document.getElementById('first-name').value = firstName;
        document.getElementById('last-name').value = lastName;
        document.getElementById('email').value = email;
        document.getElementById('contact').value = contact;
        document.getElementById('username').value = username;
        document.getElementById('birthday').value = birthday;
        options = ['Admin', 'Staff'];
        output = '';
        options.forEach(option => {
            if(role == option)
                output += '<option selected>'+option+'</option>';
            else
                output += '<option>'+option+'</option>';
        });
        document.getElementById('role').innerHTML = output;
        document.getElementById('edit-form').action = "/update_employee/"+id;
    };

    function deleteEmployee(id, name, img){
        deletePicture.src = img;
        deleteName.innerHTML = name;
        deleteBtn.href = "/delete_employee/"+id;
    }
</script>
</body>
</html>