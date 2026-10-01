@include('home.css')
<link rel="stylesheet" href="admin/assets/plugins/datatables/datatables.min.css">
<link rel="stylesheet" href="admin/assets/css/style.css"> 
<style>
    h4,h5 {
        font-family: initial;
    }
    .account-tab {
        text-decoration: none;
        color: black;
        padding: 0 10px;
        cursor: default;
        transition: none !important;
        position: relative;
    }
    .account-tab:hover {
        color: black;
    }
    .account-tab.active::before {
        content:'';
        position:absolute;
        bottom: -1px;
        height: 4px;
        width: 100%;
        background:#cda934;
        left:50%;
        transform:translateX(-50%);
        -webkit-transform:translateX(-50%);
    }
    .account-tab:hover:not(.active) {
        cursor: pointer;
    }
</style>