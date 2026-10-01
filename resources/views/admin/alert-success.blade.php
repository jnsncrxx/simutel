<div x-data="{show: true}" x-init="setTimeout(() => show = false, 7000)" x-show="show" class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">⨉</button>
    <i class="fa-regular fa-square-check"></i> {{session()->get('message')}}
</div>