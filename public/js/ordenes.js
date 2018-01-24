$(document).ready(function() {

    var path=$('meta[name="base_url"]').attr('content'); //para el base url
    var datatable = $('#grid-ordenes').DataTable(
    {
        "processing": true,
        "scrollCollapse": true,
        "paging": true,
        //"destroy": true,
        "ajax":
        {
            "url": path+"/llenaGrid",
            "type": "POST",
            "data": { "_token": "{{ csrf_token() }}" }
        },
        
         aaSorting : [[0, 'desc']],
        "columns":
        [   
            { "data": "OrdenID" },
            { "data": "PersonaID" }
        ],
       
    });

});