<div id="delete" class="modal fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img id="delete-picture" alt="" width="55" height="55" />
                <h3 class="delete_class">Are you sure want to delete <span id="delete-name"></span>?</h3>
                <div class="m-t-20">
                    <a href="#" class="btn btn-white" data-dismiss="modal" >Close</a>
                    <a id="delete-btn" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
 const deletePicture = document.getElementById('delete-picture')
    deleteName = document.getElementById('delete-name')
    deleteBtn = document.getElementById("delete-btn");
</script>