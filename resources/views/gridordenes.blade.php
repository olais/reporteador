@extends('app')

@section('content')
<style>
.vertical-menu {
    width: 200px;
}

.vertical-menu a {
    background-color: #eee;
    color: black;
    display: block;
    padding: 12px;
    text-decoration: none;
}

.vertical-menu a:hover {
    background-color: #ccc;
}

.vertical-menu a.active {
    background-color: #025A85;
    color: white;
}

.blockUI{
  z-index: 2000 !important; 
}
td{
  padding: 5px !important;
}
thead{

 background-color: #025A85 !important;
 color:#fff;
}
#botonesControl{

  width: 200px;
  height: 50px;
 

}
.boton_accion
{
    float: left;
    margin: 2.5px;
}

.boton_accion[type=checkbox]
{
    float: left;
    margin: 0.5px;
    zoom: 3;
}
#inputNumOrdenCanal{
  color:#025A85;
  font-weight: bold;
  font-size: 17px;
}
#inputMontoTotal{
  color:red;
  font-weight: bold;
  font-size: 24px;
}
#guardarArticulo{

  float: right;
  margin-top: -60px;
}



</style>

<script type="text/javascript">
$(document).ready(function() {
	$("#modoeditararticulo").hide();




var jsonObj = '{"columnas" : [{"data" : "Id"},{"data" : "Nombre"}]}';
var obj = $.parseJSON(jsonObj);

console.log(obj);
//alert(obj);





    var datatable = $('#grid-ordenes').DataTable(
    {
        "processing": true,
        //"scrollCollapse": true,
        "paging": true,
        //"destroy": true,
        "ajax":
        {
            "url": path+"/llenaGrid",
            "type": "POST",
            "data": { "_token": token }
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
        "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
        "columns":
        [   
            { "data": "OrdenID" },
            { "data": "NoOrden" },
            { "data": "FechaHoraEmision" }, 
           // { "data": "PersonaID" },
            { "data": "Estatus" },
            { "data": "Monto" },
            { "data": "Adicional" },
            { "data": "Descuento" },
            { "data": "Total" },
            { "data": "Cliente" },
            { "data": "Acciones" }

        ],
        "columnDefs": [
          {  "className": "text-center", "width": "30%", "targets": 9}
        ]
       
    });

   $('#grid-ordenes tbody').on('click','tr',function()
        {
            if ($(this).hasClass('selected'))
            {
                $(this).removeClass('selected');
            }
            else
            {
                datatable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
             
            }
        });

  //grid de articulos
  var banderaEdicionArticulo="false";
  function gridArticulos(OrdenID){
     var dataarticulos = $('#grid-articulos').DataTable(
    {
        "processing": true,
        //"scrollCollapse": true,
        "paging": true,
        "destroy": true,
        "ajax":
        {
            "url": path+"/consultaArticulos?OrdenID="+OrdenID,
            "type": "POST",
            "data": { "_token": token }
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
        "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
        "columns":
        [   
            { "data": "ID" },
            { "data": "Nombre" },
            { "data": "Precio" },
            { "data": "Cantidad" },
            { "data": "Total" },
            { "data": "Acciones" },
           

        ],
        "columnDefs": [
          { "width": "15%", "targets": 5}
        ]
       
    });
  $('#grid-articulos tbody').on('click','tr',function()
        {
            if ($(this).hasClass('selected'))
            {
                $(this).removeClass('selected');
            }
            else
            {
            
            dataarticulos.$('tr.selected').removeClass('selected');
           $(this).addClass('selected');
            Id = $(this).find('td').eq(0).html();
            //alert(Id);
             
            }
        });


        //FUNCIONALIDAD PARA ELIMINAR ARTÍCULO
        var eliminaArticulo= function(tbody, table)
        {
            $(tbody).on("click","button.eliminarArticulo", function()
            {
                if(table.row(this).child.isShown())
                {
                    var data = table.row(this).data();
                }
                else
                {
                    
                   var data = table.row($(this).parents("tr")).data();
                   var idArticulo=data.ID;
                   var nombre=data.Nombre;

                   var cadena="OrdenID="+OrdenID+"&articuloID="+idArticulo+"&_token={{ csrf_token() }}";
                  
                swal( {
                title: "¿Estás seguro de eliminar "+nombre+" de la orden "+OrdenID,
                text: "No podrás realizar cambios",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm)
            {
                
                if(isConfirm)
                {
                    $.post(path+"/eliminaArticuloPropiedades",cadena,function(data)
                    {
                      if(data.ord=="true"){
                        swal("Artículo eliminado con éxito", "", "success");
                      }else{
                         swal("Artículo no puede ser eliminado porque se encuentra en producción", "", "error");
                      }
                        $('#grid-articulos').dataTable()._fnAjaxUpdate();
                        $('#grid-ordenes').dataTable()._fnAjaxUpdate();
                    
                    },'json');
                }
                else
                {
                  swal("Operación Cancelada", "Los cambios no se aplicaron", "error");
                   
                }
            });


                }
             
            });
        }

        //EDITAR ARTÍCULO
           var editarArticulo= function(tbody, table)
        {
            $(tbody).on("click","button.editarArt", function()
            {
                if(table.row(this).child.isShown())
                {
                    var data = table.row(this).data();
                }
                else
                {
                    
                   var data = table.row($(this).parents("tr")).data();
                   var ID=data.ID;
                
                   //CONSULTAR DATOS DEL ARTÍCULO
                  banderaEdicionArticulo="true";
                  $("#modoeditararticulo").show();
                  var cadena="OrdenID="+OrdenID+"&id="+ID+"&_token={{ csrf_token() }}";



                   $.post(path+"/consultaArticulosOrden",cadena,
                      function(data){


                         var cadena="_token={{ csrf_token() }}";
                         $("#articuloss").empty();
                        $.post(path+"/llenaSelectArticulos",cadena,
                          function(data){
                            for(i=0;i<data.rows.length;i++){

                              selected=(data.rows[i].CatArticuloID==ID) ? 'selected' : '';

      $("#articuloss").append("<option value='"+data.rows[i].CatArticuloID+"' "+selected+">"+data.rows[i].Nombre+"</option>");
                            }
                             $("#articuloss").selectpicker("refresh");
                        },'json');


                        $("#selectorArticulo").val(ID);

                        $("#copias").val(data.Cantidad);
                        $("#paginas").val(data.paginas);
                        $("#color").val(data.color);
                        $("#jobid").val(data.JobID);
                        $("#articuloss").val(data.Nombre);
                        $("#observaciones").val(data.observaciones);

                      },'json');
    

                }
             
            });

          $(".modal #modoeditararticulo").click(function(){

              banderaEdicionArticulo="false";
               $("#detalle-articulos").find('form')[0].reset();
               $("#modoeditararticulo").hide();

          });
      
        }

          eliminaArticulo("#grid-articulos",dataarticulos);
          editarArticulo("#grid-articulos",dataarticulos);



  }
  var banderaEdicionOrden="false";
  var auxOrden;
   var editarOrden= function(tbody, table)
        {
            $(tbody).on("click","button.editarOrden", function()
            {
                if(table.row(this).child.isShown())
                {
                    var data = table.row(this).data();
                }
                else
                {
                    
                   var data = table.row($(this).parents("tr")).data();
                   var OrdenID=data.OrdenID;
                   auxOrden=data.OrdenID;
                  $("#myModal .modal-title").html("Editar Orden: "+OrdenID);
                  $("#guardar").hide();
                  $("#actualizar").show();
                 // $("#articuloss").selectpicker("refresh");
                   //consultar datos de la orden
                   banderaEdicionOrden="true";
                   var cadena="OrdenID="+OrdenID+"&_token={{ csrf_token() }}";
                   $.post(path+"/detalleOrden",cadena,
                    function(data){
                        //llenar campos de la orden
                        $("#muestraTotal").show();
                        $("#descuento").val(data.Descuento);
                        $("#fechaEmision").attr("disabled", true);
                        $("#fechaEmision").val(data.FechaHoraEmision);
                        //data.Monto=data.Monto;
                        $("#inputMontoTotal").val("$"+data.Monto);
                        $("#inputNumOrdenCanal").attr("disabled", true);
                        $("#inputNumOrdenCanal").val(data.NoOrden);
                        $("#inputCuenta").val(data.Cuenta);
                        $("#gastoEnvio").val(data.costoEnvio);
                        $("#gastoEnvio").attr("disabled", true);
                        $("#costoAdicional").val(data.Adicional);
                        $("#inputRazonSocial").val(data.RZ);
                        $("#inputRfc").val(data.RFC);
                        $("#inputCalleEntrega").attr("disabled", true);
                        $("#inputCalleEntrega").val(data.ECalle);
                        $("#inputNumExtEntrega").attr("disabled", true);
                        $("#inputNumExtEntrega").val(data.ENumExterior);
                        $("#inputNumIntEntrega").attr("disabled", true);
                        $("#inputNumIntEntrega").val(data.ENumInterior);
                        $("#inputCpEntrega").attr("disabled", true);
                        $("#inputCpEntrega").val(data.ECP);
                        $("#inputEstadoEntrega").attr("disabled", true);
                        $("#inputEstadoEntrega").val(data.EEstado);
                        $("#inputMunicipioEntrega").attr("disabled", true);
                        $("#inputMunicipioEntrega").val(data.EMunicipio);
                        $("#inputColoniaEntrega").attr("disabled", true);
                        $("#inputColoniaEntrega").val(data.EColonia);
                        $("#inputApellidosPersona").val(data.Apellido);
                        $("#inputNombresPersona").val(data.Nombre);
                        $("#inputLada").val(data.Lada);
                        $("#inputTelefono").val(data.Telefono);
                        $("#inputCelular").val(data.Celular);
                        $("#inputFax").val(data.Fax);
                        $("#inputEmail").val(data.Correo);
                        $("#inputDescripcionOrden").val(data.Descripcion);
                        $("#inputMontoTotal").show();
                        data.metodoEnvio=(data.metodoEnvio==null) ? 5 : data.metodoEnvio;
                        $("#envio").attr("disabled", true);
                        $("#envio").val(data.metodoEnvio);

                        if(data.metodoEnvio==1){
                          $("#paradir").show();
                          $("#gastoEnvio").show();
                        }else{
                          $("#paradir").hide();
                          $("#gastoEnvio").hide();
                        }


                    },'json' );

                   


                  $('#myModal').modal('show');

                }
             
            });
        }
      var autorizaOrden= function(tbody, table)
        {
            $(tbody).on("click","button.autorizaOrden", function()
            {
                if(table.row(this).child.isShown())
                {
                    var data = table.row(this).data();
                }
                else
                {
                    
                   var data = table.row($(this).parents("tr")).data();
                   var OrdenID=data.OrdenID;
                   var cadena="OrdenID="+OrdenID+"&_token={{ csrf_token() }}";
                  
            swal( {
                title: "¿Estás seguro de pasar la orden "+OrdenID+" a administración?",
                text: "No podrás realizar cambios",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm)
            {
                
                if(isConfirm)
                {
                    $.post(path+"/actualizaEstatus",cadena,function(data)
                    {
                      if(data.valida=="true"){
                        $('#grid-ordenes').dataTable()._fnAjaxUpdate();
                        mensaje="La orden "+OrdenID+ " ha pasado a administración";
                        avisoAlerta="success";
                      }else{
                        mensaje="La orden "+OrdenID+ " No se procesó con éxito , no tiene Artículos";
                        avisoAlerta="error";
                      }


                       swal(mensaje, "", avisoAlerta);
                  
                    },'json');
                }
                else
                {
                  swal("Operación Cancelada", "Los cambios no se aplicaron", "error");
                   
                }
            });





                }//fin
             
            });
        }
   var cancelaOrden= function(tbody, table)
        {
            $(tbody).on("click","button.cancelaOrden", function()
            {
                if(table.row(this).child.isShown())
                {
                    var data = table.row(this).data();
                }
                else
                {
                   var data = table.row($(this).parents("tr")).data();
                   var OrdenID=data.OrdenID;
                   var cadena="OrdenID="+OrdenID+"&_token={{ csrf_token() }}";

                    swal( {
                title: "¿Estás seguro de cancelar la Orden "+$("#OrdenID").val()+" ?",
                text: "No podrás realizar cambios",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm)
            {
                
                if(isConfirm)
                {
                   
                    $.post(path+"/cancelarOrdenDots",cadena,
                          function(data){
                  swal("Aviso: ", "La Orden "+$("#OrdenID").val()+" ha sido cancelada" , "success"); 
                  $('#grid-ordenes').dataTable()._fnAjaxUpdate();
                  $("#OrdenID").val("");
                  $("#OrdenID").focus();        
                           
                          },'json' );
                }
                else
                {
                  swal("Operación Cancelada", "Los cambios no se aplicaron", "error");
                   
                }
            });







                }//fin
            });
        }


    var detalleArticulo = function(tbody, table)
        {
            $(tbody).on("click","button.detalle", function()
            {
                if(table.row(this).child.isShown())
                {
                    var data = table.row(this).data();
                }
                else
                {
                    var data = table.row($(this).parents("tr")).data();
                    var OrdenID=data.OrdenID;
                    $("#detalle-articulos .modal-title").html("Agregar Artículos: "+OrdenID);
                    gridArticulos(OrdenID);
                    //cargamos el selector de artículos
                     $("#okart").css('display', 'none');
                     var cadena="_token={{ csrf_token() }}";
                        
                        $.post(path+"/llenaSelectArticulos",cadena,
                          function(data){
                            for(i=0;i<data.rows.length;i++){

                             // selected=(data.rows[i].CatArticuloID==24) ? 'selected' : '';

      $("#articuloss").append("<option value='"+data.rows[i].CatArticuloID+"'>"+data.rows[i].Nombre+"</option>");
                            }
                             $("#articuloss").selectpicker("refresh");
                        },'json');
                     $('#detalle-articulos').modal('show');


                     //lógica para guardar artículos
                     $("#guardarArticulos").submit(function(){
                      

                      var datosArticulo=$(this).serialize()+"&OrdenID="+OrdenID+"&editar="+banderaEdicionArticulo+"&_token={{ csrf_token() }}";
                      

                         $.blockUI({ css: { 
                            border: 'none', 
                            padding: '15px', 
                            backgroundColor: '#000', 
                            '-webkit-border-radius': '10px', 
                            '-moz-border-radius': '10px', 
                            opacity: .3, 
                            color: '#fff' 
                        } }); 

                        setTimeout($.unblockUI, 30000); 
                        $.post(path+"/insertaArticulos",datosArticulo,
                          function(data){
                              setTimeout($.unblockUI,0); 
                              $("#articuloss").selectpicker("refresh");
                              $("#modoeditararticulo").hide();
                              $("#detalle-articulos").find('form')[0].reset();
                              $('#grid-articulos').dataTable()._fnAjaxUpdate();
                              $('#grid-ordenes').dataTable()._fnAjaxUpdate();


                              if(data.estatus=="true"){
                                   $("#okart").css('display', 'block');
                                }else{
                                 swal("No se puede actualizar el artículo", "La orden se encuentra en producción", "error");
                                }




                          },'json'  );

                        

                        return false;

                     });





                }
             
            });
        }


        detalleArticulo("#grid-ordenes",datatable);
        editarOrden("#grid-ordenes",datatable);
        autorizaOrden("#grid-ordenes",datatable);
        cancelaOrden("#grid-ordenes",datatable);
      $("#id-form-ordenProduccion").submit(function(){


          $.blockUI({ css: { 
                    border: 'none', 
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .3, 
                    color: '#fff' 
                } }); 

           setTimeout($.unblockUI, 30000); 

          var cadena=$(this).serialize()+"&bandera="+banderaEdicionOrden+"&auxOrden="+auxOrden+"&_token={{ csrf_token() }}";
           //{ "_token": "{{ csrf_token() }}" }
          $.post(path+"/insertaOrden",cadena,
            function(data){
                setTimeout($.unblockUI, 0);
                $('#grid-ordenes').dataTable()._fnAjaxUpdate(); 
                  if(banderaEdicionOrden=="false"){
                    $("#ord").html(data.ord);
                    $("#ok").css('display', 'block');
                 
                 }else{
                 
                 $("#ordactu").html(data.ord);
                 $("#ok2").css('display', 'block');
                  
                  }

                  $("#inputDescripcionOrden").focus();

                 


            },'json');

          return false;

      });
  $('.modal').on('hidden.bs.modal', function(){ 
        $(this).find('form')[0].reset(); 
        $("#ok").css('display', 'none');
        $("#ok2").css('display', 'none');
        $("#guardar").show();
        $("#actualizar").hide();
        $("#articuloss").selectpicker("refresh");
        $("#myModal .modal-title").html("Ingreso de nueva Orden");
  });
       
  $("#carga").click(function(){
    
                        $("#inputDescripcionOrden").focus();
                        $("#actualizar").hide();
                        $("#muestraTotal").hide();
                        $("#fechaEmision").attr("disabled", false);
                        $("#inputNumOrdenCanal").attr("disabled", false);
                        $("#inputCalleEntrega").attr("disabled", false);
                        $("#inputNumExtEntrega").attr("disabled", false);
                        $("#inputNumIntEntrega").attr("disabled", false);
                        $("#inputCpEntrega").attr("disabled", false);
                        $("#inputEstadoEntrega").attr("disabled", false);
                        $("#inputMunicipioEntrega").attr("disabled", false);
                        $("#inputColoniaEntrega").attr("disabled", false);
                        $("#inputMontoTotal").hide();
                        $("#gastoEnvio").attr("disabled", false);
                        $("#envio").attr("disabled", false);

                        //consultamos NoOrden propuesto por el sistema
                        var cadena="_token={{ csrf_token() }}";
                        $.post(path+"/consultaNoOrden",cadena,
                          function(data){
                           
                            $("#inputNumOrdenCanal").val("INT-"+data.NoOrden);
                          },'json' );
                         



  });
//ocultamos la segundaParte por default
$("#segundaParte").hide();
$("#gastoEnvio").hide();
//$("#guardar").hide();
  $("#siguiente1").click(function(){
      $("#primeraParte").hide();
      $("#segundaParte").show();
      $("#guardar").show();

  });
  $("#atras1").click(function(){
      $("#primeraParte").show();
      $("#segundaParte").hide();
      //$("#guardar").hide();

  });

  $("#envio").change(function(){
      if($(this).val()==1){
        //mostrar
        $("#paradir").show();
        $("#gastoEnvio").show();

      }else{
        $("#paradir").hide();
        $("#gastoEnvio").hide();
        //ocultar
      }

  });

  //cancelar Orden
  $("#form-cancela-orden").submit(function(){


           swal( {
                title: "¿Estás seguro de cancelar la Orden "+$("#OrdenID").val()+" ?",
                text: "No podrás realizar cambios",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm)
            {
                
                if(isConfirm)
                {
                     var cadena=$("#form-cancela-orden").serialize()+"&_token={{ csrf_token() }}";

                        $.post(path+"/cancelarOrdenDots",cadena,
                          function(data){
                  swal("Aviso: ", "La Orden "+$("#OrdenID").val()+" ha sido cancelada" , "success"); 
                  $('#grid-ordenes').dataTable()._fnAjaxUpdate();
                  $("#OrdenID").val("");
                  $("#OrdenID").focus();        
                           
                          },'json' );
                }
                else
                {
                  swal("Operación Cancelada", "Los cambios no se aplicaron", "error");
                   
                }
            });

      return false;



  });
 


});

</script>
<br><br>
<div class="col-xs-12">
    <div class="row">
 <div class="col-xs-2">
        <div class="vertical-menu">
          <a href="#" class="active">Panel de control</a>
          <a href="#" id='carga' data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-shopping-cart"></span>Nueva Orden</a>
          <a href="#" id='cancelarOrden' data-toggle="modal" data-target="#cancela-orden-dts"><span class="glyphicon glyphicon-remove"></span>Cancelar Orden</a>
        </div>
 </div>
     <div class="col-xs-10">
	<table id="grid-ordenes" class="display compact"  cellspacing="0" width="100%">
			        <thead style="width: 100%;">
			            <tr>
			                <th>OrdenID</th>
                      <th>NoOrden</th>
                      <th>FechaHoraEmision</th>
			                <!--th>Cliente</th-->
                      <th>Estatus</th>
                      <th>SubTotal</th>
                      <th>Adicional</th>
                      <th>Descuento</th>
                      <th>Total</th>
                      <th>Cliente Interno</th>
                      <th class="text-center">Acciones</th>
			             </tr>
			        </thead>
			    </table>
		</div>
	</div>
</div>
 <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ingreso de nueva orden</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-info" id='ok' style='display:none'>
           <strong>Aviso:  </strong> Orden <b id='ord'></b> generada con éxito.
           </div>
          <div class="alert alert-info" id='ok2' style='display:none'>
           <strong>Aviso:  </strong> Orden <b id='ordactu'></b> Actualizada con éxito.
         </div>
<div id='primeraParte'>          
<form id="id-form-ordenProduccion" method='post'>
    <table id="id-table-ordenProduccion">
      <tbody><tr> 
           <td>
            <fieldset class="marco">
            <!--legend align="left" class="marco-legend"> INFORMACIÓN DE LA ORDEN</legend-->
            <table>
              <tbody><tr>
                <td colspan="1" > 
                  <input name="inputDescripcionOrden" type="text" id="inputDescripcionOrden"  placeholder="Descripción" required size='30'> </td>
                <td colspan="1">
                  <input name="fechaEmision" type="date" id="fechaEmision" required></td>
               
                  <td colspan="1">
                  <input name="descuento" type="number" id="descuento" placeholder="Descuento global" value='0' required> </td>
                <td colspan="1">
                  <input name="inputNumOrdenCanal" type="text" id="inputNumOrdenCanal" placeholder="Número de orden asignado por el canal" required> </td>
                  <td>
                    <!--label>Método de envio</label-->
                    <select name='envio' id='envio'>
                      <option>Seleccionar...</option>
                      <option value='1'>Envío</option>
                      <option value='5'>Recoger en tienda</option>

                    </select>
                  </td>
              </tr>
              <tr>
                 <td>
                  <input name="inputCuenta" type="text" id="inputCuenta" value="0" placeholder="Número de cuenta bancaria" required size='30'> </td>
                 <td>
                  <input name="gastoEnvio" type="text" id="gastoEnvio" placeholder="Costo Gastos de envío" > </td>
                <td>
                  <input name="costoAdicional" type="text" id="costoAdicional" placeholder="Costo Adicional"> </td>
                
              </tr>
            </tbody></table>
          </fieldset>
          </td>
         </tr>
          <tr>
          <td>
            <fieldset class="marco">
            <legend align="left" class="marco-legend"><b>PERSONA</b></legend>
            <table style="width: 100%;">
              <tbody><tr>
                <td align="left" style="width: 20%;"> 
                  <input name="inputNombresPersona" type="text" id="inputNombresPersona" size="20px" placeholder="Nombres" required>    </td>
                <td align="left" style="width: 20%;"> 
                  <input name="inputApellidosPersona" type="text" id="inputApellidosPersona" size="20px" placeholder="Apellidos" required> </td>
                <td align="left" style="width: 20%;">
                  <select id="inputTipoPersona" style="width: 130px" required name='inputTipoPersona'>                   
                    <option value="1">Física</option>
                    <option value="2">Moral</option>
                  </select>
                </td>
                <td align="left" style="width: 20%;"> <input type="text" name='inputRazonSocial' id="inputRazonSocial" size="20px" placeholder="Razón Social"></td>
                <td align="left" style="width: 20%;"> <input name="inputRfc" type="text" id="inputRfc" size="20px" placeholder="RFC">    </td>
              </tr>
              <tr>
                <td align="left" style="width: 20%;"> 
                  <input name="inputLada" type="text" id="inputLada" size="20px" placeholder="Lada">    </td>
                <td align="left" style="width: 20%;"> 
                  <input name="inputTelefono" type="text" id="inputTelefono" size="20px" placeholder="Teléfono">    </td>
                <td align="left" style="width: 20%;"> 
                  <input name="inputCelular" type="text" id="inputCelular" size="20px" placeholder="Celular">    </td>
                <td align="left" style="width: 20%;"> 
                  <input name="inputFax" type="text" id="inputFax" size="20px" placeholder="Fax">    </td>
                <td align="left" style="width: 20%;"> 
                  <input name="inputEmail" type="text" id="inputEmail" size="20px" placeholder="Correo electrónico"> </td>
              </tr>
            </tbody></table>
          </fieldset>
          </td>
        </tr>

        <tr>
        <!--td>
            <fieldset class="marco">
            <legend align="left" class="marco-legend"><b>DIRECCIÓN DE FACTURACIÓN</b></legend>
            <table style="width: 100%;">
              <tbody><tr>
                <td align="left" style="width: 20%;">
                  <input name="inputTipoDireccionFact" type="hidden" id="inputTipoDireccionFact" value="1">
                  <input name="inputCalleFact" type="text" id="inputCalleFact" size="20px" placeholder="Calle">
                </td>
                <td align="left" style="width: 20%;"> 
                  <input name="inputNumExtFact" type="text" id="inputNumExtFact" size="20px" placeholder="Número exterior"> </td>
                <td align="left" style="width: 20%;"> 
                  <input name="inputNumIntFact" type="text" id="inputNumIntFact" size="20px" placeholder="Número interior"> </td>
                <td align="left" style="width: 20%;"> 
                  <input name="inputCpFact" type="text" id="inputCpFact" size="20px" placeholder="Código postal"> </td>
                <td align="left" style="width: 20%;"> 
                  <input name="inputEstadoFact" type="text" id="inputEstadoFact" size="20px" placeholder="Estado"> </td>                    
              </tr>
              <tr>
                <td align="left" style="width: 20%;"> 
                  <input name="inputMunicipioFact" type="text" id="inputMunicipioFact" size="20px" placeholder="Municipio"> </td>
                <td align="left" style="width: 20%;" colspan="4"> 
                  <input name="inputColoniaFact" type="text" id="inputColoniaFact" size="20px" placeholder="Colonia"> </td> 
              </tr>
            </tbody></table>
          </fieldset>             
        </td-->
         </tr>
          <tr>
            <td>
              <div id='paradir'>
              <fieldset class="marco">
              <legend align="left" class="marco-legend"><b>DIRECCIÓN DE ENTREGA</b></legend>
              <table style="width: 100%;">
                <tbody><tr>
                  <td align="left" style="width: 20%;">
                    <input name="inputTipoDireccionEntrega" type="hidden" id="inputTipoDireccionEntrega" value="2"> 
                    <input name="inputCalleEntrega" type="text" id="inputCalleEntrega" size="20px" placeholder="Calle">
                  </td>
                  <td align="left" style="width: 20%;"> 
                    <input name="inputNumExtEntrega" type="text" id="inputNumExtEntrega" size="20px" placeholder="Número exterior"> </td>
                  <td align="left" style="width: 20%;"> 
                    <input name="inputNumIntEntrega" type="text" id="inputNumIntEntrega" size="20px" placeholder="Número interior"> </td>
                  <td align="left" style="width: 20%;">
                    <input name="inputCpEntrega" type="text" id="inputCpEntrega" size="20px" placeholder="Código postal"> </td>
                  <td align="left" style="width: 20%;">
                    <input name="inputEstadoEntrega" type="text" id="inputEstadoEntrega" size="20px" placeholder="Estado"> </td>                    
                </tr>
                <tr>
                  <td align="left" style="width: 20%;">
                    <input name="inputMunicipioEntrega" type="text" id="inputMunicipioEntrega" size="20px" placeholder="Municipio"> </td>
                  <td align="left" style="width: 20%;" colspan="3">
                    <input name="inputColoniaEntrega" type="text" id="inputColoniaEntrega" size="20px" placeholder="Colonia"> </td> 
                
                </tr>
              </tbody></table>
            </fieldset>             
            </td>
          </div>
       </tr>
       <tr>
            <td>
             
              <fieldset class="marco">
              <legend align="left" class="marco-legend" id='muestraTotal'><b>TOTAL</b></legend>
              <table style="width: 100%;">
                <tbody>

                <tr>
                  
                 <td align="left" style="width: 20%;" colspan="1">
                  <input name="inputMontoTotal" type="text" id="inputMontoTotal" placeholder="Monto"  reandoly> </td>
                </tr>
              </tbody></table>
            </fieldset> 

            </td>
         
       </tr>
    </tbody></table>
    </div>
</div>

       <div class="modal-footer">
          <!--button type="button" class="btn btn-default" id='atras1'>Atrás</button>
          <button type="button" class="btn btn-primary" id='siguiente1'>Siguiente</button-->
          <button id='cancelar' type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success" id='guardar'>Guardar</button>
          <button type="submit" class="btn btn-success" id='actualizar'>Actualizar</button>
        </div>
     </form>
   
           
       </div>
    </div>
  </div>
</div>
</div>
<!-- Modal -->
  <div class="modal fade" id="detalle-articulos" role="dialog" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
        <div class="alert alert-info" id='okart' style='display:none'>
        <strong>Aviso:  </strong>Artículo agregado con éxito.
      </div>
          <form name='guardarArticulos' id='guardarArticulos' method='POST' class="form-inline">
            <div class="form-group">
                 <select class="selectpicker form-control" name='articuloss' id='articuloss'  data-style="btn-default" required>
                  <option>Seleccionar...</option>
                </select>
            </div>
             <div class="form-group">
                <input type='hidden' id='selectorArticulo' name='selectorArticulo'>
              
                <input type="number" class="form-control" id="copias" name='copias' required placeholder='Copias'>
            </div>
             <div class="form-group">
            
                <input type="number" class="form-control" id="paginas" name='paginas' placeholder='Páginas' >
            </div>
             <div class="form-group">
               
                <input type="text" class="form-control" id="color" name='color' placeholder='Color'>
            </div>
            <br><br>
             <div class="form-group">
                
                <input type="text" class="form-control" id="jobid" name='jobid' required placeholder='Url de archivo (JobId)'>
            </div>
             <div class="form-group">
            
              <textarea class="form-control" rows="5" cols="50" id="observaciones" name='observaciones' required placeholder='Observaciones'></textarea>
            </div>
           <br><br>
              <!--mostrar los artículos -->
            <button type="submit" class="btn btn-success" id='guardarArticulo'>Guardar</button>
              <table id="grid-articulos"  cellspacing="0" width="100%">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Precio</th>
                      <th>Cantidad</th>
                      <th>Total</th>
                      <th>Acciones</th>
                    </tr>
              </thead>
             
          </table>





         </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" >Cerrar</button>
           <button type="button" class="btn btn-default"  id='modoeditararticulo'>Salir de edición</button>
          
        </div>
        </form>
       
      
    </div>
  </div>
</div>
  <!-- Modal -->
  <div class="modal fade" id="cancela-orden-dts" role="dialog" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Cancelar Orden</h4>
        </div>
        <div class="modal-body">
            
          <form name='form-cancela-orden' id='form-cancela-orden' method='POST' class="form-inline">
            
            <center>
             <div class="form-group">
                <label for="orden"><b>OrdenID Dots:</b></label>
                <input type="number" class="form-control" id="OrdenID" name='OrdenID' required>
            </div></center>

         </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal" >Cerrar</button>
          <button type="submit" class="btn btn-warning" id='cancela-orden-dots'>Cancelar</button>
        </div>
        </form>
    
      
    </div>
  </div>
  
  



    @endsection