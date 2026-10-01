<style>
    .input {
        padding-right: 40px;
    }
    .add{
        right: 16px;
        padding-bottom: 7px;
    }
    .items div {
        border: 1px solid #ddd;
        background-color: #f6f6f6;
        padding: 5px 20px 5px 5px;
        word-break: break-word;
        position: relative;
    }
    .cross {
        position: absolute;
        right: 5px;
        top: 0;
        cursor: pointer;
    }
    .gallery img {
        height: 50px;
        width: 50px;
        margin: 0 5px 5px 5px;
        object-fit: cover;
    }
</style>
<div id="add_room_type" class="modal p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container p-0">
            <div class="modal-body">
                <div class="page-header">
                    <h3 class="page-title position-relative">Add Room Type
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-5px;top:-10px;" onclick="$('#add_room_type').modal('hide');">⨉</span>
                    </h3>
                </div>
                <form id="add-form" action="{{url('/add_room_type')}}" method="POST"  enctype="multipart/form-data">
                    @csrf
                    <div class="row formtype">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label id="add-room-name">Room Name</label>
                                @error('room_name')
                                    <label class="text-danger">Room name already exists.</label>
                                    <script>
                                        document.getElementById('add-room-name').classList.add('d-none');
                                        sessionStorage.setItem("addRoomType", "true");
                                    </script>
                                @enderror
                                <input class="form-control" type="text" name="room_name" value="{{old('room_name')}}" required> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Default Occupancy</label>
                                <input class="form-control" type="number" min="1" name="default_occupancy" value="{{old('default_occupancy')}}" required> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Max Occupancy</label>
                                <input class="form-control" type="number" min="0" name="max_occupancy" value="{{old('max_occupancy')}}" required> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Rent</label>
                                <input type="number" class="form-control" min="0" name="rent" value="{{old('rent')}}" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Extra Adult</label>
                                <input type="number" class="form-control" min="0" name="extra_adult" value="{{old('extra_adult')}}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label id="edit-beds">Beds</label>
                                <input class="form-control" type="text" name="beds" value="{{old('beds')}}"> 
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Views</label>
                                <input class="form-control" type="text" name="views" value="{{old('views')}}"> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Points per stay</label>
                                <input type="number" class="form-control" min="0" name="points" value="{{old('points')}}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Points required</label>
                                <input type="number" class="form-control" min="0" name="points_required" value="{{old('points_required')}}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status" required>
                                    @if(old('status') != null)
                                        @php 
                                            $options = array('Active', 'Inactive');
                                            $output = '';
                                            for( $i=0; $i<count($options); $i++ ) {
                                                $output .= '<option ' . ( old('status') == $options[$i] ? 'selected' : '' ) . '>' . $options[$i] . '</option>';
                                            }
                                            echo $output;
                                        @endphp
                                    @else
                                        <option value="">Select</option>
                                        <option>Active</option>
                                        <option>Inactive</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="upload">Room Pictures</label>
                                <div class="custom-file mb-3">
                                    <input type="file" accept="image/*" multiple class="add-pictures border border-gray-200 rounded p-2 w-100" name="pictures[]"/>
                                </div>
                                <div class="add-gallery gallery"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="5" name="description" required>{{old('description')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label id="amenities">Amenities</label>
                                @error('amenities')
                                    <label class="text-danger">{{$message}}</label>
                                    <script>
                                        document.getElementById('amenities').classList.add('d-none');
                                        sessionStorage.setItem("addRoomType", "true");
                                    </script>
                                @enderror
                                <input id="input-amenity" class="input form-control w-100 d-inline position-relative" type="text"> 
                                <input id="hidden-amenities" type="hidden" name="amenities"> 
                                <button type="button" onclick="addAmenity()" class="add btn btn-primary position-absolute">+</button>
                                <div id="amenity" class="items mw-100">
                                    @php
                                        if(old('amenities') != null){
                                            $amenities = explode('+',old('beds'));

                                            foreach($amenities as $amenity){
                                               echo'<div>' . $amenity . '<span class="cross">&times;</span></div>';
                                            }
                                        }
                                    @endphp
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="submit" type="submit" class="btn btn-primary ml-1" value="Add Room Type">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {
        if (input.files) {
            var filesAmount = input.files.length;
            $(placeToInsertImagePreview)[0].innerHTML = "";
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    const canvas = $($.parseHTML('<canvas class="mr-2" width="60" height="60"></canvas>')).appendTo(placeToInsertImagePreview)[0];
                    var p = 60, q = 60;

                    var img = new Image;
                    var ctx = canvas.getContext('2d');
                    img.onload = function() {
                        canvas.width = p;  // set canvas resolution
                        canvas.height = q;

                        ctx.drawImage(img, 0, 0, p, q); // draw image with given resolution
                    };
                    img.src = event.target.result;   
                }
                reader.readAsDataURL(input.files[i]);   
            }
        }
    };

    $('.add-pictures').on('change', function() {
        imagesPreview(this, 'div.add-gallery');
    });
const inputAmenity = document.getElementById('input-amenity')
      amenity = document.getElementById('amenity')
      hiddenAmenities = document.getElementById('hidden-amenities')
      closebtns = document.getElementsByClassName("cross")
      submit = document.getElementById("submit");

      for (let i = 0; i < closebtns.length; i++) {
            closebtns[i].addEventListener("click", function() {
                this.parentElement.remove();
            });
      }
      function addAmenity(){
        if(inputAmenity.value && !(!inputAmenity.value.trim().length)){
            inputAmenity.value = inputAmenity.value.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            amenity.innerHTML += '<div>' + inputAmenity.value + '<span class="cross">&times;</span></div>';
            inputAmenity.value = "";

            for (let i = 0; i < closebtns.length; i++) {
                closebtns[i].addEventListener("click", function() {
                    this.parentElement.remove();
                });
            }
        }
      }

      submit.addEventListener('click', function(){
        let allAreFilled = true;
        document.getElementById("add-form").querySelectorAll("[required]").forEach(function(i) {
            if (!allAreFilled) return;
            if (!i.value) { allAreFilled = false;  return; }
        })
        if (allAreFilled) {
            addAmenity();
            let amenities = amenity.children
                allAmenities = "";
            for (let i = 0; i < amenities.length; i++) {
                allAmenities += amenities[i].textContent.slice(0,-1) + '+';
            }
            hiddenAmenities.value = allAmenities.slice(0,-1);
        }
      });
</script>