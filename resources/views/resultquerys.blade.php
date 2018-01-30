@extends('app')

@section('content')

<script>
  $(document).ready(function(){

    //configuraciones:
    $("#consulta").focus();
    //horientación de papel den pdf landscape
    localStorage.setItem("horientacion", "landscape");
    localStorage.setItem("logo",'data:image/png;base64,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'
    );
    localStorage.setItem("titulo","Reporte de scccceee");
                       

    //formar la tabla
    llenamenuvistas();
  function llenamenuvistas(){
    $.post(path+"/consultavistas",{ "_token": token},
      function(data){
        $("#vistasMenu").html(data.vistas);
      },'json');

  }


    $("#enviaconsulta").submit(function(){
       var query=$("#consulta").val();
        enviarconsulta(query);
        return false;

    });


    function enviarconsulta(query){

        $.blockUI({ css: { 
                    border: 'none', 
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .3, 
                    color: '#fff' 
                } }); 

     setTimeout($.unblockUI, 90000000);

      $.post(path+"/querys",{ "_token": token,"consulta": query},
      function(data){
       setTimeout($.unblockUI,0);
       if(data.error=="true"){

          $("#msjError").html(data.msj);
        }else{
         $("#msjError").html("");
         $("#headertable").html(data.columnas);
          resultados(query);
         }  
        
      },'json');
    }


    //llenar el grid

    function resultados(query){

       var resultados = $('#grid-resultado').DataTable(
      {
        
        "processing": true,
        //"scrollCollapse": true,
        "paging": true,
        "destroy": true,
        "ajax":
        {
            "url": path+"/llenaGridQuery",
            "type": "POST",
            "data": { "_token": token,"consulta":query}
        },
        "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "search": "Buscar",
                    "zeroRecords": "No existe Orden Manual",
                    "info": "Página _PAGE_ de _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(Encontrado de _MAX_ registros)",
                    "paginate": {
                        "first":      "Inicio",
                        "last":       "Último",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    }
                },
         aaSorting : [[0, 'desc']],
        "lengthMenu": [[20, 25, 50,100,1000, -1], [20, 25, 50,100,1000, "All"]],
        dom: 'Bfrtip',
        buttons: [
           //'copy',
            {
                extend: 'excel', text:'Exportar a excel',
                title: localStorage.getItem("titulo"),
                messageTop: 'Reporte de empresa.'
            },

            {
                extend: 'pdfHtml5', text: 'Exportar a PDF',
               // download: 'open',
                messageTop: 'Reporte de empresa',
                title: localStorage.getItem("titulo"),
                orientation: localStorage.getItem("horientacion"),
                pageSize: 'letter',

                customize: function ( doc ) {
                    doc.content.splice( 1, 0, {
                        margin: [ 0, 0, 0, 12 ],
                        alignment: 'left',
                        image : localStorage.getItem("logo")
                           } );
                }
            }


        ]
       
       
    });
    }


    $('#paraconsultas').on('shown.bs.modal', function () {
    $("#nombreReporte").focus();
    });
    $("#msjconfirmar").css("display",'none');

    $("#generarvista").click(function(){

         consulta=$("#consulta").val();
         reporte=$("#nombreReporte").val();
         $.post(path+"/generarvista",{ "_token": token,"consulta":consulta,"reporte":reporte},
            function(data){
                llenamenuvistas();
                $("#desplegaVistas").addClass("open");
                $("#msjconfirmar").css("display",'block');
            },'json');

          

         return false;

    });

    //extraer id de reporte
     $('body #vistasMenu').on('click', 'li', function(){
       // alert($(this).attr('id'))
       var query=$(this).attr('id');
        enviarconsulta(query);
        $("#resulvista").html("para reporte: "+$(this).attr('class'));

      });

     $("#grid-resultado.buttons-pdf").click(function(){

        alert("hola");


     });

     $("#consulta").bind('keydown', 'ctrl+x', function(){
             var query=$("#consulta").val();
             enviarconsulta(query);
             return false;
      });

     $("#limpiar").click(function(){

      location.reload();
    });


  });

</script>
<style type="text/css">
  .color-grids{

    background-color: #025A85;
    color:#fff;
  }
</style>
<div id='mensaje'></div>

<div class="col-xs-12">
    <div class="row">
      <div class="col-xs-8">
        <form id='enviaconsulta' method='POST'>
         <div class="form-group">
          <label for="consulta"><b style='color:#337ab7;'>Consulta:</b></label>
          <textarea class="form-control" rows="5" cols="100" id="consulta" name='consulta' required></textarea>
        </div>
        <button type="submit" class="btn btn-primary" id='ejecutarquery'><span class="glyphicon glyphicon-saved"></span>Ejecutar</button>
        </form>
        <button type="submit" class="btn btn-default" style='margin-left:100px;margin-top:-55px;' data-toggle="modal" data-target="#paraconsultas" id='guardar'><span class="glyphicon glyphicon-floppy-saved"></span>Guardar</button>
         <button type="submit" class="btn btn-default" style='margin-left:8px;margin-top:-55px;'  id='limpiar'><span class="glyphicon glyphicon-floppy-remove"></span>Limpiar</button>
      </div>
      <div class="col-xs-2">
          <!--ul class="nav nav-pills nav-stacked" id='vistasMenu'>
          <!--li class="active"><a href="#">Reportes</a></li>
          <li><a href="#"><span class="glyphicon glyphicon-folder-open"></span> Menu 1</a></li-->
         
         <!--/ul-->

    <div class="dropdown open" id='desplegaVistas'>
    <button class="btn btn-primary dropdown-toggle" type="button" >Reportes
    <span class="caret"></span></button>
    <ul class="dropdown-menu" id='vistasMenu'>
         
    </ul>
  </div>




      </div>
</div>
</div>
<div id='msjError' style='color:red;'></p></div>
<h4 style='margin-left:10px;'>Resultado <b id='resulvista'></b></h4>
<div id='separador' style='width:100%;height:1px;background-color:#337ab7;'></div>
<div class="col-xs-10" style='margin-top:50px;'>
  <!--div id='filtrosFechas'>
    <input type='date' name='fechaInicio' id='fechaInicio'><input type='date' name='fechaFin' id='fechaFin'>

  </div-->

  <table id="grid-resultado" class="display compact"  cellspacing="0" width="100%">
              <thead style="width: 100%;"  class='color-grids'>
                  <tr id='headertable'>
                  </tr>
              </thead>
               <tbody id="bdy-grid">
            </tbody>
          </table>
    </div>

<!-- Modal -->
<div id="paraconsultas" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Guardar consulta como</h4>
      </div>
      <div class="modal-body">
            <div class="form-group">
              <label for="nombre-reporte"><b>Nombre de reporte</b></label>
              <input type="nombreReporte" class="form-control" id="nombreReporte">
            </div>
        <div class="alert alert-success alert-dismissable" id='msjconfirmar' style='display:none;'>
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Aviso!</strong> Reporte generado con éxito.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary"  id='generarvista'>Ok</button>
      </div>
    </div>

  </div>
</div>

 @endsection