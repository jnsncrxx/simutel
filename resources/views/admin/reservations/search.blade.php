<div class="row">
    <div class="col-lg-12">
        <form id="search-form" action="all_reservations" method="GET">
            <div class="row formtype">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="search" class="form-control search"  name="search" placeholder="&#xf002 Search" style=" font-family: 'Helvetica', FontAwesome, sans-serif;" value="@php if(isset($_GET["search"])) echo $_GET["search"]; @endphp"/>
                    </div>
                </div>
                {{-- <div class="col-md-2 px-md-0">
                    <div class="form-group">
                        <select class="form-control" name="column">
                            @if(isset($_GET["column"]))
                                @php 
                                    $options = array('All', 'ID', 'Room No.', 'Name', 'Payment', 'Status');
                                    $output = '';
                                    for( $i=0; $i<count($options); $i++ ) {
                                        $output .= '<option ' . ( $_GET["column"] == $options[$i] ? 'selected' : '' ) . '>' . $options[$i] . '</option>';
                                    }
                                    echo $output;
                                @endphp
                            @else
                                <option>All</option>
                                <option>ID</option>
                                <option>Room No.</option>
                                <option>Name</option>
                                <option>Payment</option>
                                <option>Status</option>
                            @endif
                        </select>
                    </div>
                </div> --}}
                
                <div class="col-md-3 pl-md-0">
                    <div class="form-group d-flex">
                        <input type="button" onclick="validateForm()" class="btn btn-secondary mt-0 col-md-6 search_button" value="Search"/>
                        <a href="/all_reservations" class="btn btn-secondary mt-0 ml-2 col-md-6">Clear</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@php
    function strContains($str1, $str2) {
        return str_contains(strtolower($str1), strtolower($str2)) || str_contains(strtolower($str2), strtolower($str1));
    }
    if(isset($_GET["search"]) && $_GET["search"] != null){
        $reservations = $reservations->filter(function ($item) {
                return 
                $item->id == $_GET["search"] ||
                optional($item->rooms)->room_no == $_GET["search"] ||
                strContains($item->first_name, $_GET["search"]) || strContains($item->last_name, $_GET["search"]) ||
                strContains($item->status, $_GET["search"]);
        })->values();
    }
@endphp