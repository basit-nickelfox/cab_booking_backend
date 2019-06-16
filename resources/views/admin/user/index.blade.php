@extends('admin.layouts.sidebar')
@section('content')
<div class="hk-pg-wrapper">
<!-- Breadcrumb -->
@include('admin.includes.breadcrumb')
            <!-- /Breadcrumb -->
         <!-- Container -->
         <div class="container">
<!-- Row -->
            <div class="row">
                <div class="col-xl-12">
                    <section class="hk-sec-wrapper">
                        <div class="row">
                            <div class="col-sm">
                                <div class="table-wrap">
                                    <table id="userTbl" class="table table-hover w-100 pb-30">
                                        <thead>
                                            <tr>
                                            <th>ID</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                            <th>ID</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
<!-- /Row -->

         </div>
<!-- /Container -->
    @section('page-script')
      <!-- jQuery -->
      <script src="{{asset('vendors/jquery/dist/jquery.min.js')}}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{asset('vendors/popper.js/dist/umd/popper.min.js')}}"></script>
<script src="{{asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

<!-- Slimscroll JavaScript -->
<script src="{{asset('dist/js/jquery.slimscroll.js')}}"></script>

<!-- Data Table JavaScript -->
<script src="{{asset('vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-dt/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('vendors/jszip/dist/jszip.min.js')}}"></script>
<script src="{{asset('vendors/pdfmake/build/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/pdfmake/build/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<!-- <script src="{{asset('dist/js/dataTables-data.js')}}"></script> -->

<!-- FeatherIcons JavaScript -->
<script src="{{asset('dist/js/feather.min.js')}}"></script>
<!-- Fancy Dropdown JS -->
<script src="{{asset('dist/js/dropdown-bootstrap-extended.js')}}"></script>
<!-- SweetAlert JavaScript -->
<script src="{{asset('dist/js/sweetalert.min.js')}}"></script>
<!-- Toggles JavaScript -->
<script src="{{asset('vendors/jquery-toggles/toggles.min.js')}}"></script>
<script src="{{asset('dist/js/toggle-data.js')}}"></script>

<!-- Init JavaScript -->
<script src="{{asset('dist/js/init.js')}}"></script>
<!-- Custom JavaScript -->
<script type="text/javascript">
    // my custom script
    $(document).ready(function() {
        console.log("hello");
        // Fetch Users
        fetchUsers();
// Delete Function
$(document).on('click','.delete-button',function(){
    deleteUser($(this).attr('id'));
        });
$(document).on('click','.makeadmin-button',function(){
    makeadmin($(this).attr('id'));
        });
});
function fetchUsers(){
    var token =  '{{ Session::get('access_token') }}';
    $("#userTbl").DataTable().destroy()
    $('#userTbl').DataTable({
                        ajax:{
                            url: "{{ url('/api/user') }}",
                            type: 'GET',
                            dataType: 'JSON',
                            headers: {"Authorization": 'Bearer ' + token},
                            dataSrc: function ( json ) {
                                console.log(json);
                                return json.data;
                            }
                            },
                            scrollX: true,
                            autoWidth: false,
                            "order": [[ 0, "desc" ]],
		language: { search: "",
		searchPlaceholder: "Search"},
    	sLengthMenu: "_MENU_items",
columns: [
{ "data": "id" },
{ "data": "firstName"},
{ "data": "lastName" },
{ "data": "email" },
{ "data": "phone" },
{ "data": "null", 
"render": function ( data, type, full, meta ) { 
    var url = '{{ url("/admin/user/edit", "id") }}';
    url = url.replace('id', full.id);
    return '<div class="d-flex">'+
                '<a href="javascript:void(0);" id="'+full.id+'" class="text-primary makeadmin-button mr-15">Make Admin</a>'+
                '<a href="javascript:void(0);" id="'+full.id+'" class="text-primary delete-button mr-15">Delete</a>'+
            '</div>';
}
},
]
});
}
function deleteUser(id) {
    var token =  '{{ Session::get('access_token') }}';
    var url = '{{ url("/api/user", "id") }}';
    url = url.replace('id', id);
    swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this user!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
    })
.then((willDelete) => {
  if (willDelete) {
    $.ajax({
                url: url,
                type: 'POST',
                dataType: 'JSON',
                headers: {"Authorization": 'Bearer ' + token},
                data: {
                    '_method':'delete'
                },
                success: function (data) {
                    switch(data['result']){
                            case 'success':
                                         _isDirty = false;
                                         swal({
                                                    title: data['title'],
                                                text: data['message'],
                                                icon: data['result'],
                                                button: "OK",
                                                timer: 2000,
                                                }).then(function() {
                                                    fetchUsers();
                                                });  
                                        break;
                            case 'error':
                            swal({
                                title: data['title'],
                                text: data['message'],
                                icon: data['result'],
                                button: "OK",
                                });
                                break;        
                        }
                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });
  } 
});
}
function makeadmin(id) {
    var token =  '{{ Session::get('access_token') }}';
    var url = '{{ url("/api/updateadmin", "id") }}';
    url = url.replace('id', id);
        swal({
            title: "Are you sure?",
            text: "Make this user as ADMIN!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willUpdate) => {
        if (willUpdate) {    
            console.log("in dele");
                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {"Authorization": 'Bearer ' + token},
                    dataType: 'JSON',
                    data: {
                    isAdmin: 1,
                        '_method': 'PUT'
                    },
                    success: function(data) {
                        console.log("jejje");
                        console.log(data);
                        switch (data['result']) {
                            case 'success':
                                _isDirty = false;
                                swal({
                                    title: data['title'],
                                    text: data['message'],
                                    icon: data['result'],
                                    button: "OK",
                                    timer: 2000,
                                }).then(function() {
                                    // document.location.href = "{!! URL::to('admin/user'); !!}";
                                    fetchUsers();
                                });
                                break;
                            case 'error':
                                swal({
                                    title: data['title'],
                                    text: data['message'],
                                    icon: data['result'],
                                    button: "OK",
                                });
                                break;
                        }
                    }
                });
            } 
        });
}
</script>
@stop
    <!-- Footer -->
    <div class="hk-footer-wrap container">
        @include('admin.includes.footer')
    </div>
    <!-- /Footer -->
</div>
@stop