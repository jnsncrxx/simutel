<style>
    .cross-edit {
        position: absolute;
        right: 5px;
        top: 0;
        cursor: pointer;
    }
</style>
<div id="edit_room_type" class="modal p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container p-0">
            <div class="modal-body">
                <div class="page-header">
                    <h3 class="page-title position-relative">Edit Room Type
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-5px;top:-10px;" onclick="$('#edit_room_type').modal('hide');">⨉</span>
                    </h3>
                </div>
                <form id="edit-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row formtype">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label id="edit-room-name">Room Name</label>
                                <label id="edit-room-name-warn" class="text-danger d-none">Room name already exists.</label>
                                @error('edit_room_name')
                                    <script>sessionStorage.setItem("idRoomName", {{$message}});</script>
                                @enderror
                                <input class="form-control" type="text" id="room_name" name="edit_room_name" required> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Default Occupancy</label>
                                <input class="form-control" type="number" min="1" id="default_occupancy" name="edit_default_occupancy" required> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Max Occupancy</label>
                                <input class="form-control" type="number" min="0" id="max_occupancy" name="edit_max_occupancy" required> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Rent</label>
                                <input type="number" class="form-control" min="0" id="rent" name="edit_rent" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Extra Adult</label>
                                <input type="number" class="form-control" min="0" id="extra_adult" name="edit_extra_adult" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label id="edit-beds">Beds</label>
                                <input class="form-control" type="text" id="beds" name="edit_beds"> 
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Views</label>
                                <input class="form-control" type="text" id="views" name="edit_views"> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Points per stay</label>
                                <input type="number" class="form-control" min="0" id="points" name="edit_points">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Points required</label>
                                <input type="number" class="form-control" min="0" id="points_required" name="edit_points_required">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" id="status" name="edit_status" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="upload">Room Pictures</label>
                                <div class="custom-file mb-3">
                                    <input type="file" accept="image/*" multiple class="edit-pictures border border-gray-200 rounded p-2 w-100" name="pictures[]"/>
                                </div>
                                <div class="edit-gallery gallery">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="5" id="description" name="edit_description" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label id="edit-amenities">Amenities</label>
                                <label id="edit-amenities-warn" class="text-danger d-none">Amenities are required.</label>
                                @error('edit_amenities')
                                    <script>sessionStorage.setItem("idAmenities", {{$message}});</script>
                                @enderror
                                <input id="input-amenity-edit" class="input form-control w-100 d-inline position-relative" type="text"> 
                                <input id="hidden-amenities-edit" type="hidden" name="edit_amenities">
                                <button type="button" onclick="addAmenityEdit()" class="add btn btn-primary position-absolute">+</button>
                                <div id="amenity-edit" class="items mw-100">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="submit-edit" type="submit" class="btn btn-primary" value="Save">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('.edit-pictures').on('change', function() {
        imagesPreview(this, 'div.edit-gallery');
    });
const inputAmenityEdit = document.getElementById('input-amenity-edit')
      amenityEdit = document.getElementById('amenity-edit')
      hiddenAmenitiesEdit = document.getElementById('hidden-amenities-edit')
      closebtnsEdit = document.getElementsByClassName("cross-edit")
      submitEdit = document.getElementById("submit-edit");

      for (let i = 0; i < closebtnsEdit.length; i++) {
          closebtnsEdit[i].addEventListener("click", function() {
              this.parentElement.remove();
          });
      }

      function addAmenityEdit(){
        if(inputAmenityEdit.value && !(!inputAmenityEdit.value.trim().length)){
            inputAmenityEdit.value = inputAmenityEdit.value.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            amenityEdit.innerHTML += '<div>' + inputAmenityEdit.value + '<span class="cross-edit">&times;</span></div>';
            inputAmenityEdit.value = "";

            for (let i = 0; i < closebtnsEdit.length; i++) {
                closebtnsEdit[i].addEventListener("click", function() {
                    this.parentElement.remove();
                });
            }
        }
      }
      
      submitEdit.addEventListener('click', function(){
        let allAreFilled = true;
        document.getElementById("edit-form").querySelectorAll("[required]").forEach(function(i) {
            if (!allAreFilled) return;
            if (!i.value) { allAreFilled = false;  return; }
        })
        if (allAreFilled) {
            addAmenityEdit();
            let amenities = amenityEdit.children
                allAmenities = "";
            for (let i = 0; i < amenities.length; i++) {
            allAmenities += amenities[i].textContent.slice(0,-1) + '+';
            }
            hiddenAmenitiesEdit.value = allAmenities.slice(0,-1);
        }
      });

    function edit(id){
        let room_type = getRoomType(id);
        document.getElementById('room_name').value = room_type.room_name;
        document.getElementById('default_occupancy').value = room_type.default_occupancy;
        document.getElementById('max_occupancy').value = room_type.max_occupancy;
        document.getElementById('rent').value = room_type.rent;
        document.getElementById('extra_adult').value = room_type.extra_adult;
        document.getElementById('points').value = room_type.points;
        document.getElementById('points_required').value = room_type.points_required;
        document.getElementById('views').value = room_type.views;
        document.getElementById('beds').value = room_type.beds;
        options = ['Active', 'Inactive'];
            output = '';
            for(let i=0; i<options.length; i++) {
                if(options[i] == room_type.status)
                    output += '<option selected>'+options[i]+'</option>';
                else
                    output += '<option>'+options[i]+'</option>';
            }
        document.getElementById('status').innerHTML = output;
        document.getElementById('description').value = room_type.description;
        output = '';
            roomTypeBeds = room_type.beds.split("+");
            for(let i=0; i<roomTypeBeds.length; i++) {
                output += '<div>' + roomTypeBeds[i] + '<span class="cross-edit">&times;</span></div>';
            }
        output = '';
            roomTypeAmenities = room_type.amenities.split("+");
            for(let i=0; i<roomTypeAmenities.length; i++) {
                output += '<div>' + roomTypeAmenities[i] + '<span class="cross-edit">&times;</span></div>';
            }
        document.getElementById('amenity-edit').innerHTML = output;
        document.getElementById('edit-form').action = "/update_room_type/" +id;

        if(sessionStorage.getItem("idRoomName") == id){
            document.getElementById('edit-room-name').classList.add('d-none')
            document.getElementById('edit-room-name-warn').classList.remove('d-none');
        }
        else{
            document.getElementById('edit-room-name').classList.remove('d-none')
            document.getElementById('edit-room-name-warn').classList.add('d-none');
        }

        if(sessionStorage.getItem("idAmenities") == id){
            document.getElementById('edit-amenities').classList.add('d-none')
            document.getElementById('edit-amenities-warn').classList.remove('d-none');
        }
        else{
            document.getElementById('edit-amenities').classList.remove('d-none')
            document.getElementById('edit-amenities-warn').classList.add('d-none');
        }
        
        for (let i = 0; i < closebtnsEdit.length; i++) {
            closebtnsEdit[i].addEventListener("click", function() {
                this.parentElement.remove();
            });
        }
        $('div.edit-gallery')[0].innerHTML = "";
        room_type.pictures.forEach(picture => {
            const canvas = $($.parseHTML('<canvas class="mr-2" width="60" height="60"></canvas>')).appendTo('div.edit-gallery')[0];
            var p = 60, q = 60;

            var img = new Image;
            var ctx = canvas.getContext('2d');
            img.onload = function() {
                canvas.width = p;  // set canvas resolution
                canvas.height = q;

                ctx.drawImage(img, 0, 0, p, q); // draw image with given resolution
            };
            img.src = picture.picture;        
        });
    };
</script>