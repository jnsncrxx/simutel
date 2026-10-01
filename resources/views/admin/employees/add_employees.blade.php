<div id="add_employee" class="modal p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="page-header">
                    <h3 class="page-title position-relative">Add Employee
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-5px;top:-10px;" onclick="$('#add_employee').modal('hide');">⨉</span>
                    </h3>
                </div>
                <form id="add-form" action="/add_employee" method="POST">
                    @csrf
                    <div class="row formtype">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name</label>
                                <input class="form-control" type="text" name="first_name" pattern="[^0-9@]+" value="{{old('first_name')}}" required> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input class="form-control" type="text" name="last_name" pattern="[^0-9@]+" value="{{old('last_name')}}" required> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="add-email">Email</label>
                                @error('email')
                                <label class="text-danger">Email already exists.</label>
                                    <script>
                                        document.getElementById('add-email').classList.add('d-none');
                                        sessionStorage.setItem("addEmployee", "true");
                                    </script>
                                @enderror
                                <input class="form-control" type="email" name="email" value="{{old('email')}}" required> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact</label>
                                <input class="form-control" type="tel" name="contact" pattern="^(09)\d{9}$" value="{{old('contact')}}" required> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="add-username">Username</label>
                                @error('username')
                                <label class="text-danger">Username already exists.</label>
                                    <script>
                                        document.getElementById('add-username').classList.add('d-none');
                                        sessionStorage.setItem("addEmployee", "true");
                                    </script>
                                @enderror
                                <input class="form-control" type="text" name="username" value="{{old('username')}}" required> 
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Birthday</label>
                                <input class="form-control" type="date" name="birthday" value="{{old('birthday')}}" onkeydown="return false" max="{{date('Y-m-d', strtotime('-18 year'))}}" onclick="this.showPicker()" required> 
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Role</label>
                                <select class="form-control" name="role" required>
                                    @if(old('role') != null)
                                        @php 
                                            $options = array('Admin', 'Staff');
                                            $output = '';
                                            for( $i=0; $i<count($options); $i++ ) {
                                                $output .= '<option ' . ( old('role') == $options[$i] ? 'selected' : '' ) . '>' . $options[$i] . '</option>';
                                            }
                                            echo $output;
                                        @endphp
                                    @else
                                        <option value="">Select</option>
                                        <option>Admin</option>
                                        <option>Staff</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary ml-1" value="Add Employee">
                </form>
            </div>
        </div>
    </div>
</div>