<div id="edit_guest" class="modal p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="page-header">
                    <h3 class="page-title position-relative">Edit Member
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-5px;top:-10px;" onclick="$('#edit_guest').modal('hide');">⨉</span>
                    </h3>
                </div>
                <ul class="text-danger pl-4">
                    <li id="edit-email-warn" class="d-none">Email "{{old('edit_email')}}" already exists.</li>
                    <li id="edit-username-warn" class="d-none">Username "{{old('edit_username')}}" already exists.</li>
                </ul>
                <form id="edit-form" method="POST">
                    @csrf
                    <div class="row formtype">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name</label>
                                <input class="form-control" type="text" id="first-name" name="first_name" required> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input class="form-control" type="text" id="last-name" name="last_name" required> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="edit-email">Email</label>
                                <input class="form-control" id="email" type="email" name="edit_email" required> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact</label>
                                <input class="form-control" type="tel" id="contact" name="edit_contact" pattern="^(09)\d{9}$" required> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="edit-username">Username</label>
                                <input class="form-control" type="text" id="username" name="edit_username" required> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Birthday</label>
                                <input class="form-control" type="date" id="birthday" name="birthday" onkeydown="return false" max="{{date('Y-m-d', strtotime('-18 year'))}}" onclick="this.showPicker()" disabled> 
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary ml-1" value="Save">
                </form>
            </div>
        </div>
    </div>
</div>