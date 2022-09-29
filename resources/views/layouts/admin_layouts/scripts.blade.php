<!-- JavaScript files-->
<script src="{{ url('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('admin/vendor/just-validate/js/just-validate.min.js') }}"></script>
<script src="{{ url('admin/vendor/chart.js/Chart.min.js') }}"></script>
<script src="{{ url('admin/vendor/choices.js/public/assets/scripts/choices.min.js') }}"></script>
<script src="{{ url('admin/js/charts-home.js') }}"></script>
<!-- Main File-->
<script src="{{ url('admin/js/front.js') }}"></script>
{{--  datatable  --}}
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.2.4/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/responsive.bootstrap4.min.js"></script>

<script>
function injectSvgSprite(path) {

   var ajax = new XMLHttpRequest();
   ajax.open("GET", path, true);
   ajax.send();
   ajax.onload = function(e) {
   var div = document.createElement("div");
   div.className = 'd-none';
   div.innerHTML = ajax.responseText;
   document.body.insertBefore(div, document.body.childNodes[0]);
   }
}
injectSvgSprite('https://bootstraptemple.com/files/icons/orion-svg-sprite.svg');
</script>
{{--  toastr message  --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
{{--  FontAwesome CSS  --}}
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
{{--  sweet alert  --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{--  datatable  --}}
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    } );
    {{--  toastr message  --}}
    @if(Session::has('message'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
            toastr.success("{{ session('message') }}");
    @endif

    @if(Session::has('error'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
            toastr.error("{{ session('error') }}");
    @endif

    @if(Session::has('info'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
            toastr.info("{{ session('info') }}");
    @endif

    @if(Session::has('warning'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
            toastr.warning("{{ session('warning') }}");
    @endif

    {{--  sweet alert  --}}
    {{--  package edit  --}}
    $('.package-edit').on('click', function () {
        var recordid = $(this).attr('recordid');
        $.ajax({
            type: "GET",
            url: "/admin/pacakge-edit/"+recordid,
            cache: false,
            success: function(data){
                document.getElementById("PackageId").value = data.id;
                document.getElementById("PackageName").value = data.package_name;
                document.getElementById("PackagePrice").value = data.package_price;
                document.getElementById("PackageFeature").value = data.package_feature;
                document.getElementById("PackageDuration").value = data.duration_days;
            }
            });
    });
    //package_activation
    $('.package_activation').on('click', function () {
        var recordid = $(this).attr('recordid');
        swal({
        title: "Do you want to activate this package?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Activated it!'
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href="/admin/package-activate/"+recordid;
        }
        });
        })

</script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
