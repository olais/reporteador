@extends('app')
@section('content')
<!-- Trigger the modal with a button -->
<style type="text/css">

 #contenedor {
    width: 443px;
    margin: auto;
    position: fixed;
    top: 45%;
    left: 30%;
    z-index: 9999;
    box-shadow: 4px 4px 13px black;
}

 #progressbar{
     position: absolute;
     width: 600px;
     margin: auto;
     z-index: 2000;
 }
.ui-progressbar {
    position: relative;
}
.progress-label {
    position: absolute;
    left: 45%;
    top: 4px;
    font-weight: bold;
    text-shadow: 1px 1px 0 #fff;
}
#barra {
    width: 0;
    height: 100%;
    -webkit-animation: progreso 5s linear infinite;
            animation: progreso 5s linear infinite;
    background: #eb7260;
}

#texto {
    font-size: 2em;
    font-weight: bold;
    line-height: 2em;
    width: 12.5em;
    height: 100%;
    text-align: center;
    color: #eb7260;
    mix-blend-mode: multiply;
}

#texto:after {
    content: 'Verificando...';
    -webkit-animation: porcentaje 5s linear infinite;
            animation: porcentaje 5s linear infinite;
}

@-webkit-keyframes progreso {
    0% {
        width: 0;
    }
    100% {
        width: 100%;
    }
}

@keyframes progreso {
    0% {
        width: 0;
    }
    100% {
        width: 100%;
    }
  }
}


</style>

<script type="text/javascript">
	$(document).ready(function(){
		 $("#parametros-base#servidor").focus();
		 $('#parametros-base').modal('show');

		 $("#parametrosbase").submit(function(){

		 	var parametros=$(this).serialize()+"&_token={{ csrf_token() }}";

		 	$.post(path+"/generaconexion",parametros,
		 		function(data){

		 		},'json')

		 		
		 	return false;

		 });
		 document.getElementById('contenedor').style.display = 'none';

		 $("#probarConexion").click(function(){

		 	var parametros=$("#parametrosbase").serialize()+"&_token={{ csrf_token() }}";
		 	
		    document.getElementById('contenedor').style.display = 'block';
		
		 	$.post(path+"/probarconexion",parametros,
		 		function(data){	
		 			if(data.valida=="true"){
		 				$("#msjconfirmaconexion").css("display",'block');
		 				$("#msjerrorconexion").css("display",'none');
 					   }else{
 					   	$("#msjconfirmaconexion").css("display",'none');
  						$("#msjerrorconexion").css("display",'block');
 					   }
		 			
		 			document.getElementById('contenedor').style.display = 'none';
		 			

		 		},'json')

		 		
		 	return false;
		 });

	});


</script>

<!-- Modal -->
<div id="contenedor" style='display:none';>
  <div id="barra">
    <div id="texto"></div>
  </div>
</div>
<div id="parametros-base" class="modal fade" role="dialog"  data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Parámetros para la conexión de base de datos(mysql,sqlserver)</h4>
      </div>
      <div class="modal-body">
       				<form name='parametrosbase' id='parametrosbase' METHOD='POST'>
				  <div class="form-group">
				    <label for="servidor">Servidor</label>
				    <input type="text" class="form-control" id="servidor" name='servidor' placeholder='localhost' required>
				  </div>
				  <div class="form-group">
				    <label for="servidor">Motor de base de datos</label>
				    <select class="form-control" id="motor" name='motor' required>
				    	<option value=''>Seleccionar...</option>
				    	<option value='mysql'>Mysql</option>
						<option value='sqlsrv'>sqlsrv</option>
				   </select>
				  </div>
				  <div class="form-group">
				    <label for="base">Nombre de base de datos:</label>
				    <input type="text" class="form-control" id="base" name='base' placeholder='base de datos' required>
				  </div>
				  <div class="form-group">
				    <label for="usuario">Usuario:</label>
				    <input type="text" class="form-control" id="usuariobase" name='usuariobase' placeholder='base de datos' required>
				  </div>
				   <div class="form-group">
				    <label for="pwd">Password:</label>
				    <input type="text" class="form-control" id="pwdbase" name='pwdbase' placeholder='base de datos' >
				  </div>
		 <div class="alert alert-success alert-dismissable" id='msjconfirmaconexion' style='display:none;'>
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Conexión establecida</strong>.
        </div>
         <div class="alert alert-warning alert-dismissable" id='msjerrorconexion' style='display:none;'>
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Conexión no establecida verifique datos</strong>.
        </div>

        
		      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Configurar después</button>
        <button type="button" class="btn btn-success" id='probarConexion'>Probar Conexión</button>
        <button type="submit" class="btn btn-primary" >Aplicar</button>
      </div>
      </form>
    </div>

  </div>
</div>
@endsection
