<?php 
namespace imprimart\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class OrdenesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}
	
	public function getOrdenes()
	{

	     /*	foreach($_POST as $nombre_campo => $valor)
            { 
                $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
                eval($asignacion); 
                
                //echo $asignacion."<br>";
            }*/
      		$Ordenes = DB::table('OrdenesManuales')->orderBy('OrdenID','DESC')->get();
      		


		    foreach ($Ordenes as $valor)
            {
            	//$total=$valor->MonTotal-$valor->Descuento;
                $datos['data'][]=array(
                    "OrdenID"=>$valor->OrdenID,
                    "NoOrden"=>trim($valor->NoOrden),
                    "FechaHoraEmision"=>$valor->FechaHoraEmision,
                    "PersonaID"=>$valor->PersonaID,
                    "Estatus"=>$valor->OrdST,
                    "Monto"=>"<b>$".($valor->MonTotal+$valor->Descuento-$valor->Adicional)."</b>",
                    "Adicional"=>"<b>$".$valor->Adicional."</b>",
                    "Descuento"=>"<b style='color:Red;'>$".$valor->Descuento."</b>",
                    "Total"=>"<b>$".$valor->MonTotal."</b>",
                    "Cliente"=>$valor->Nombres,
                    "Acciones"=>"<button type='button' id='ButtonEditar' class='editarOrden edit-modal btn btn-primary botonEditar boton_accion' data-toggle='tooltip' title='Editar Orden'><span class='glyphicon glyphicon-edit'></span></button><button  type='button' id='ButtonDetalle' class='detalle edit-modal btn btn-success botonDetalle boton_accion' data-toggle='tooltip' title='Ver Artículos'></span><span class='glyphicon glyphicon-list-alt'></span></button><button  type='button' id='autorizaOrden' class='autorizaOrden edit-modal btn btn-info  boton_accion' data-toggle='tooltip' title='Liberar Orden'><span class='glyphicon glyphicon-ok-sign'></span></button><button  type='button' id='cancelaOrden' class='cancelaOrden edit-modal btn btn-danger  boton_accion' data-toggle='tooltip' title='Cancelar Orden'><span class='glyphicon glyphicon-remove'></span></button>"
                 );
            }
            $result = json_encode($datos);

             return $result;
          // return view('gridordenes')->with('datos', $Ordenes); //pasarle los parametros a la vista

	}

	public function consultaNoOrden(){

		$NoOrden=DB::select("SELECT top 1 Valor  from OrdTiposPropiedades where CatOrdPropiedadID=1 ORDER BY OrdenID desc	");

		return response()->json(['NoOrden' =>$NoOrden[0]->Valor]);
								    
			
	}

	public function actualizaEstatus(){
		 //ACTUALIZAMOS EL ESTATUS DE LA ORDEN 

		$OrdenID=$_REQUEST['OrdenID'];

            //CONSULTAR SI LA ORDEN TIENE ARTÍCULOS
            $articulos=DB::select("SELECT
							dbo.Ordenes.OrdenID,
							dbo.OrdArticulos.OrdArtSTID,
							dbo.OrdArticulos.CatArticuloID,
							dbo.Ordenes.OrdSTID

							FROM
							dbo.Ordenes
							INNER JOIN dbo.OrdArticulos ON dbo.OrdArticulos.OrdenID = dbo.Ordenes.OrdenID
							WHERE dbo.Ordenes.OrdenID=$OrdenID AND dbo.Ordenes.OrdSTID=51");

							if(count($articulos)>0){
								DB::table('Ordenes')
						            ->where('OrdenID', $OrdenID)
						            ->update(['OrdSTID' =>101]);
								$valida="true";
							}
							else{
								$valida="false";
							}
					return response()->json([
								    'valida' =>$valida
								    
								]);
           


	}
	public function cancelarOrdenDots(){

		$OrdenID=$_REQUEST['OrdenID'];

		DB::table('Ordenes')->where('OrdenID', $OrdenID)->update(['OrdSTID' =>50]);
		return response()->json(['valida' =>$OrdenID]);
								    
								    
								
						            

	}

	public function articulos()
	{

	  
            $articulos = DB::table('CatArticulos')->get();
		    foreach ($articulos as $valor)
            {
                $datos['rows'][]=array(
                    "Nombre"=>$valor->Nombre,
                    "CatArticuloID"=>$valor->CatArticuloID
                    
                 );
            }
            $result = json_encode($datos);

             return $result;
          

	}

	public function eliminaArticuloPropiedades(){

		
		$OrdenID=$_REQUEST['OrdenID'];
		$articuloID=$_REQUEST['articuloID'];

		 //CONSULTAR SI LA ORDEN TIENE ARTÍCULOS
            $articulos=DB::select("SELECT
							dbo.Ordenes.OrdenID,
							dbo.OrdArticulos.OrdArtSTID,
							dbo.OrdArticulos.CatArticuloID,
							dbo.Ordenes.OrdSTID

							FROM
							dbo.Ordenes
							INNER JOIN dbo.OrdArticulos ON dbo.OrdArticulos.OrdenID = dbo.Ordenes.OrdenID
							WHERE dbo.Ordenes.OrdenID=$OrdenID AND dbo.Ordenes.OrdSTID=51");

							if(count($articulos)>0){

		DB::table('OrdArticulos')
						 ->where('OrdenID', '=',$OrdenID)
						 ->where('CatArticuloID', '=',$articuloID)
		                 ->delete();
		                 
		 DB::table('OrdArtTiposPropiedades')
						 ->where('OrdenID', '=',$OrdenID)
						 ->where('CatArticuloID', '=',$articuloID)
		                 ->delete();

		  //recalcular monto de la orden
		 $SubtotalOrden=DB::table('OrdArticulos')->where('OrdenID', '=',$OrdenID)->sum('Total');
         $descuento=DB::select("SELECT Descuento from OrdenesManuales where OrdenID=$OrdenID");

          $totalOrden=$SubtotalOrden-$descuento[0]->Descuento;

			DB::table('Ordenes')
				->where('OrdenID', $OrdenID)
				->update(['MonTotal' =>$totalOrden]);

		                 $valida="true";

		             }else{

		             	 $valida="false";
		             }

		 return response()->json([
			    'ord' =>$valida
			    
			]);








	}
		public function consultaArticulos(){
					

					  $OrdenID=$_REQUEST['OrdenID'];



						$articulos=DB::select("SELECT
						dbo.OrdArticulos.OrdenID,
						dbo.OrdArticulos.CatArticuloID,
						dbo.CatArticulos.PID,
						dbo.CatArticulos.Nombre,
						dbo.OrdArticulos.JobID,
						dbo.OrdArticulos.Cantidad,
						precioUnitario=ISNULL((SELECT Precio FROM CatPersArtPrecio WHERE CatArticuloID=dbo.OrdArticulos.CatArticuloID and PersonaID=1 and CatPrecioTipoID=1),'0'),
						dbo.OrdArticulos.Total
						FROM
						dbo.OrdArticulos INNER JOIN dbo.CatArticulos ON dbo.OrdArticulos.CatArticuloID = dbo.CatArticulos.CatArticuloID
						WHERE dbo.OrdArticulos.OrdenID=$OrdenID
						");



          				if(count($articulos)>0){
          				foreach ($articulos as $valor){
            		
				                $datos['data'][]=array(
				                   
				                     "ID"=>$valor->CatArticuloID,
				                     "Nombre"=>$valor->Nombre,
				                     "Precio"=>"$".$valor->precioUnitario,
				                     "Cantidad"=>$valor->Cantidad,
				                     "Total"=>"<b>$".$valor->Total."</b>",
				                     "Acciones"=>"<button type='button' id='ButtonEditarArt' class='editarArt edit-modal btn btn-primary  boton_accion'><span class='fa fa-edit'></span><span class='glyphicon glyphicon-edit'></span></button><button style='margin-left:3px;' type='button' id='eliminarArticulo' class='eliminarArticulo edit-modal btn btn-default  boton_accion'><span class='fa fa-edit'></span><span class='glyphicon glyphicon-trash'></span></button>"
				                    
				                 );
				        }

            		 }else{
            		  	$datos['data'][]=array(
				                   
				                     "ID"=>"",
				                     "Nombre"=>"No existen artículos para la orden $OrdenID",
				                     "Precio"=>"",
				                     "Cantidad"=>"",
				                     "Total"=>"",
				                     "Acciones"=>""
				                    
				                );
            		  }
            		   $result = json_encode($datos);
						   return $result;


          			

	}
	public function consultaArticulosOrden(){
					

					   $OrdenID=$_REQUEST['OrdenID'];
					   $articuloID=$_REQUEST['id'];

					   $articulos=DB::select("SELECT
						dbo.Ordenes.OrdenID,
						dbo.OrdArticulos.CatArticuloID,
						dbo.CatArticulos.Nombre,
						dbo.CatArticulos.NombreCorto,
						color=ISNULL((SELECT Valor FROM OrdArtTiposPropiedades where OrdenID=dbo.Ordenes.OrdenID AND CatArticuloID=dbo.CatArticulos.CatArticuloID and CatOrdArtPropiedadesID=3), ''),
						paginas=ISNULL((SELECT Valor FROM OrdArtTiposPropiedades where OrdenID=dbo.Ordenes.OrdenID AND CatArticuloID=dbo.CatArticulos.CatArticuloID and CatOrdArtPropiedadesID=2), ''),
						observaciones=ISNULL((SELECT Valor FROM OrdArtTiposPropiedades where OrdenID=dbo.Ordenes.OrdenID AND CatArticuloID=dbo.CatArticulos.CatArticuloID and CatOrdArtPropiedadesID=6), ''),
						dbo.OrdArticulos.JobID,
						dbo.OrdArticulos.Cantidad,
						dbo.CatArticulos.CatArtFamiliaID
						FROM
						dbo.Ordenes
						INNER JOIN dbo.OrdArticulos ON dbo.OrdArticulos.OrdenID = dbo.Ordenes.OrdenID
						INNER JOIN dbo.CatArticulos ON dbo.OrdArticulos.CatArticuloID = dbo.CatArticulos.CatArticuloID
						where dbo.Ordenes.OrdenID=$OrdenID and dbo.OrdArticulos.CatArticuloID=$articuloID

						");


          				foreach ($articulos as $valor){
            		
				                $datos=array(
				                   
				                     "ID"=>$valor->CatArticuloID,
				                     "Nombre"=>$valor->Nombre,
				                     "Cantidad"=>$valor->Cantidad,
				                     "paginas"=>$valor->paginas,
				                     "color"=>$valor->color,
				                     "JobID"=>$valor->JobID,
				                     "observaciones"=>$valor->observaciones
				                    
				                    
				                 );
				        }

				         $result = json_encode($datos);
						   return $result;



          			

	}

	


	

	public function insertaOrdenes(){

		foreach($_POST as $nombre_campo => $valor)
            { 
                $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
                eval($asignacion); 
                //echo $asignacion."<br>";

            }

           if($bandera=="false"){

            $fechaEmision = date("d-m-Y", strtotime($fechaEmision));
            $gastoEnvio=(isset($gastoEnvio) && $gastoEnvio !="") ? $gastoEnvio : '0.0';

            //insertamos persona
             DB::table('Personas')->insert(
			    ['CatPersTipoID' =>$inputTipoPersona, //aqui el id de la persona logueada
			      'Nombres' =>$inputNombresPersona , 
			      'PadPersona'=>1
			    ]
			    
			);
             $PersonaID= DB::table('Personas')->max('PersonaID');
           // echo $PersonaID;

             //insertar propiedades de la persona CatPersPropiedades
              DB::table('CatPersPropiedades')->insert(
			    ['PersonaID' =>$PersonaID, 
			      'CatPersTiposPropiedadID' =>4, 
			      'Valor'=>$inputNombresPersona
			    ]	);
			    
				DB::table('CatPersPropiedades')->insert(
			    ['PersonaID' =>$PersonaID, 
			      'CatPersTiposPropiedadID' =>3, 
			      'Valor'=>$inputApellidosPersona
			    ]	);
			    
				 DB::table('CatPersPropiedades')->insert(
			    ['PersonaID' =>$PersonaID,
			      'CatPersTiposPropiedadID' =>6, 
			      'Valor'=>$inputEmail
			    ]	);
			    
			     DB::table('CatPersPropiedades')->insert(
			    ['PersonaID' =>$PersonaID, 
			      'CatPersTiposPropiedadID' =>8, 
			      'Valor'=>$inputRazonSocial
			    ]	);

			       DB::table('CatPersPropiedades')->insert(
			    ['PersonaID' =>$PersonaID, 
			      'CatPersTiposPropiedadID' =>9, 
			      'Valor'=>$inputRfc
			    ]	);

			         DB::table('CatPersPropiedades')->insert(
			    ['PersonaID' =>$PersonaID, 
			      'CatPersTiposPropiedadID' =>10, 
			      'Valor'=>$inputTelefono
			    ]	);

			         DB::table('CatPersPropiedades')->insert(
			    ['PersonaID' =>$PersonaID, 
			      'CatPersTiposPropiedadID' =>11, 
			      'Valor'=>$inputLada
			    ]	);

			         DB::table('CatPersPropiedades')->insert(
			    ['PersonaID' =>$PersonaID, 
			      'CatPersTiposPropiedadID' =>12, 
			      'Valor'=>$inputFax
			    ]	);


			   DB::table('CatPersPropiedades')->insert(
			    ['PersonaID' =>$PersonaID,
			      'CatPersTiposPropiedadID' =>13, 
			      'Valor'=>$inputCelular
			    ]	);
			    
			    
         	$OrdenID= DB::table('Ordenes')->insertGetId(
			    ['PersonaID' =>$PersonaID, 
			    'OrdSTID' => 51, 
			    'FechaHoraEmision'=>"$fechaEmision",
			    'Facturado'=>0,
			    'Descripcion'=>$inputDescripcionOrden,
			    'MonTotal'=>($gastoEnvio+$costoAdicional)]
			    
			);

			//agregar el numero de orden  CatOrdPropiedades
         	DB::table('OrdTiposPropiedades')->insert(
			    ['OrdenID' =>$OrdenID,
			      'CatOrdPropiedadID' =>1, 
			      'Valor'=>$inputNumOrdenCanal
			    ]	);

         	//agregar el descuento global
         	DB::table('OrdTiposPropiedades')->insert(
			    ['OrdenID' =>$OrdenID,
			      'CatOrdPropiedadID' =>4, 
			      'Valor'=>$descuento
			    ]	);

         	//agregar el numero de cuenta bancaria para la orden
         	DB::table('OrdTiposPropiedades')->insert(
			    ['OrdenID' =>$OrdenID,
			      'CatOrdPropiedadID' =>20, 
			      'Valor'=>$inputCuenta
			    ]	);


         	//registrar dirección de entrega Direcciones
			
			$DireccionID= DB::table('Direcciones')->max('DireccionID');
			$DireccionID=$DireccionID+1;

			DB::insert("Set Identity_Insert Direcciones on
     INSERT INTO Direcciones (DireccionID,Activa,Descripcion) VALUES ($DireccionID,1,'')
     Set Identity_Insert Direcciones off");
			
			//insertamos en ordirecciones
			DB::table('OrdDirecciones')->insert(
			    ['OrdenID' =>$OrdenID, 
			      'DireccionID' =>$DireccionID, 
			      'Valor'=>3
			    ]);


			//insertamos las propiedades para la dirección de envio CatDireccTiposPropiedades
			DB::table('CatDireccTiposPropiedades')->insert(
			    ['DireccionID' =>$DireccionID, 
			      'CatDireccPropiedadID' =>1, 
			      'Valor'=>$inputCalleEntrega
			    ]	);

			
			DB::table('CatDireccTiposPropiedades')->insert(
			    ['DireccionID' =>$DireccionID, 
			      'CatDireccPropiedadID' =>2, 
			      'Valor'=>$inputNumExtEntrega
			    ]	);
			DB::table('CatDireccTiposPropiedades')->insert(
			    ['DireccionID' =>$DireccionID, 
			      'CatDireccPropiedadID' =>3, 
			      'Valor'=>$inputNumIntEntrega
			    ]	);
			DB::table('CatDireccTiposPropiedades')->insert(
			    ['DireccionID' =>$DireccionID, 
			      'CatDireccPropiedadID' =>4, 
			      'Valor'=>$inputCpEntrega
			    ]	);
			DB::table('CatDireccTiposPropiedades')->insert(
			    ['DireccionID' =>$DireccionID, 
			      'CatDireccPropiedadID' =>5, 
			      'Valor'=>$inputEstadoEntrega
			    ]	);
			DB::table('CatDireccTiposPropiedades')->insert(
			    ['DireccionID' =>$DireccionID, 
			      'CatDireccPropiedadID' =>6, 
			      'Valor'=>$inputMunicipioEntrega
			    ]	);
			DB::table('CatDireccTiposPropiedades')->insert(
			    ['DireccionID' =>$DireccionID, 
			      'CatDireccPropiedadID' =>7, 
			      'Valor'=>$inputColoniaEntrega
			    ]	);


			//agregar el método de envio  OrdenesCatMetodoEnvio
			

			DB::table('OrdenesCatMetodoEnvio')->insert(
			    ['OrdenID' =>$OrdenID, 
			      'CatMetodoEnvioID' =>$envio, //recoger en tienda
			      'Valor'=>$gastoEnvio,
			      'OrdMetoEnvioSTID'=>100
			    ]	);

			//agregamos el costo adicional como propiedad 21 
		    DB::table('OrdTiposPropiedades')->insert(
			    ['OrdenID' =>$OrdenID,
			      'CatOrdPropiedadID' =>21, 
			      'Valor'=>$costoAdicional
			    ]	);


			return response()->json([
			    'ord' => $OrdenID
			    
			]);

		}else{

		    $auxOrden=$_POST['auxOrden'];

		    //actualizar costo adicional
		    DB::table('OrdTiposPropiedades')
				->where('OrdenID', $auxOrden)
				->where('CatOrdPropiedadID',21)
				->update(['valor' =>$costoAdicional]);

			//actualiza descripción
			DB::table('Ordenes')
				->where('OrdenID', $auxOrden)
				->update(['Descripcion' =>$inputDescripcionOrden]);

		    //actualiza descuento global

			DB::table('OrdTiposPropiedades')
				->where('OrdenID', $auxOrden)
				->where('CatOrdPropiedadID',4)
				->update(['valor' =>$descuento]);

			//actualiza cuenta bancaria
		    $existeCuenta=DB::table('OrdTiposPropiedades')->where('OrdenID', '=',$auxOrden)->where('CatOrdPropiedadID', '=',20)->max('Valor');
		    if(isset($existeCuenta)){
		    	DB::table('OrdTiposPropiedades')
				->where('OrdenID', $auxOrden)
				->where('CatOrdPropiedadID',20)
				->update(['valor' =>$inputCuenta]);
		    }else{//insertamos
		    	DB::table('OrdTiposPropiedades')->insert(
			    ['OrdenID' =>$auxOrden,
			      'CatOrdPropiedadID' =>20, 
			      'Valor'=>$inputCuenta
			    ]	);
		    }
			

			//actualiza monto
			$SubtotalOrden=DB::table('OrdArticulos')->where('OrdenID', '=',$auxOrden)->sum('Total');

			$envio=DB::select("SELECT Valor from OrdenesCatMetodoEnvio where OrdenID=$auxOrden");
          	$totalEnvio=$envio[0]->Valor;
          	$adicional=DB::select("SELECT Adicional from OrdenesManuales where OrdenID=$auxOrden");
            $totalOrden=($SubtotalOrden+$totalEnvio+$adicional[0]->Adicional)-$descuento;

			DB::table('Ordenes')
				->where('OrdenID', $auxOrden)
				->update(['MonTotal' =>$totalOrden]);
			//actualiza envio
			/*DB::table('OrdenesCatMetodoEnvio')
				->where('OrdenID', $auxOrden)
				->update(['CatMetodoEnvioID' =>$envio]);*/
			//actualiza nombre
			$PersonaID= DB::table('Ordenes')->where('OrdenID', $auxOrden)->max('PersonaID');

			
			DB::table('CatPersPropiedades')
				->where('PersonaID', $PersonaID)
				->where('CatPersTiposPropiedadID',4)
				->update(['Valor' =>$inputNombresPersona]);

			DB::table('CatPersPropiedades')
				->where('PersonaID', $PersonaID)
				->where('CatPersTiposPropiedadID',3)
				->update(['Valor' =>$inputApellidosPersona]);

			DB::table('CatPersPropiedades')
				->where('PersonaID', $PersonaID)
				->where('CatPersTiposPropiedadID',6)
				->update(['Valor' =>$inputEmail]);

			DB::table('CatPersPropiedades')
				->where('PersonaID', $PersonaID)
				->where('CatPersTiposPropiedadID',8)
				->update(['Valor' =>$inputRazonSocial]);

			DB::table('CatPersPropiedades')
				->where('PersonaID', $PersonaID)
				->where('CatPersTiposPropiedadID',9)
				->update(['Valor' =>$inputRfc]);

			DB::table('CatPersPropiedades')
				->where('PersonaID', $PersonaID)
				->where('CatPersTiposPropiedadID',10)
				->update(['Valor' =>$inputTelefono]);

			DB::table('CatPersPropiedades')
				->where('PersonaID', $PersonaID)
				->where('CatPersTiposPropiedadID',11)
				->update(['Valor' =>$inputLada]);

			DB::table('CatPersPropiedades')
				->where('PersonaID', $PersonaID)
				->where('CatPersTiposPropiedadID',12)
				->update(['Valor' =>$inputFax]);

			DB::table('CatPersPropiedades')
				->where('PersonaID', $PersonaID)
				->where('CatPersTiposPropiedadID',13)
				->update(['Valor' =>$inputCelular]);

			DB::table('Personas')
				->where('PersonaID', $PersonaID)
				->update(['CatPersTipoID' =>$inputTipoPersona]);



			return response()->json([
			    'ord' => $auxOrden
			    
			]);


			
		}







			

			

	    }

	public function detalleOrden(){

		$OrdenID=$_POST['OrdenID'];

		$ordenes = DB::table('V_Detalle_Completo_Orden_Manual')->where('OrdenID', '=',$OrdenID)->get();
		//$apellido = DB::table('CatPersPropiedades')->where('OrdenID', '=',$OrdenID)->where('CatPersTiposPropiedadID', '=',4)->max('Valor');

		  
		    foreach ($ordenes as $valor)
            {

            	$desc=DB::select("SELECT Descripcion from Ordenes where OrdenID=$OrdenID");
            	$total=DB::select("SELECT MonTotal from Ordenes where OrdenID=$OrdenID");
            	$descuento=DB::select("SELECT Descuento from OrdenesManuales where OrdenID=$OrdenID");
            	$envio=DB::select("SELECT Valor from OrdenesCatMetodoEnvio where OrdenID=$OrdenID");
            	$adicional=DB::select("SELECT Adicional from OrdenesManuales where OrdenID=$OrdenID");


                $datos=array(
                    "NoOrden"=>$valor->NoOrden,
                    "Descripcion"=>$desc[0]->Descripcion,
                    "Descuento"=>$descuento[0]->Descuento,
                    "FechaHoraEmision"=>date("Y-m-d", strtotime($valor->FechaHoraEmision)),
                    "Monto"=>$total[0]->MonTotal,
                    "NoOrden"=>$valor->NoOrden,
                    "Cuenta"=>DB::table('OrdTiposPropiedades')->where('OrdenID', '=',$OrdenID)->where('CatOrdPropiedadID', '=',20)->max('Valor'),
                    "costoEnvio"=>$envio[0]->Valor,
                    "Adicional"=>$adicional[0]->Adicional,
                    "metodoEnvio"=>DB::table('OrdenesCatMetodoEnvio')->where('OrdenID', '=',$OrdenID)->max('CatMetodoEnvioID'),
                    "RZ"=>$valor->RazonSocial,
                    "RFC"=>$valor->RFC,
                    "ECalle"=>$valor->ECalle,
                    "ENumExterior"=>$valor->ENumExterior,
                    "ENumInterior"=>$valor->ENumInterior,
                    "ECP"=>$valor->ECP,
                    "EEstado"=>$valor->EEstado,
                    "EMunicipio"=>$valor->EMunicipio,
                    "EColonia"=>$valor->EColonia,
                    "Apellido"=>DB::table('CatPersPropiedades')->where('PersonaID', '=',$valor->PersonaID)->where('CatPersTiposPropiedadID', '=',3)->max('Valor'),
                    "Nombre"=>DB::table('CatPersPropiedades')->where('PersonaID', '=',$valor->PersonaID)->where('CatPersTiposPropiedadID', '=',4)->max('Valor'),
                    "Lada"=>DB::table('CatPersPropiedades')->where('PersonaID', '=',$valor->PersonaID)->where('CatPersTiposPropiedadID', '=',11)->max('Valor'),
                    "Telefono"=>DB::table('CatPersPropiedades')->where('PersonaID', '=',$valor->PersonaID)->where('CatPersTiposPropiedadID', '=',10)->max('Valor'),
                    "Celular"=>DB::table('CatPersPropiedades')->where('PersonaID', '=',$valor->PersonaID)->where('CatPersTiposPropiedadID', '=',13)->max('Valor'),
                    "Fax"=>DB::table('CatPersPropiedades')->where('PersonaID', '=',$valor->PersonaID)->where('CatPersTiposPropiedadID', '=',12)->max('Valor'),
                    "Correo"=>DB::table('CatPersPropiedades')->where('PersonaID', '=',$valor->PersonaID)->where('CatPersTiposPropiedadID', '=',6)->max('Valor')



                    
                 );
            }

            $result = json_encode($datos);

             return $result;



	}


	public function insertaArticulos(){

		foreach($_POST as $nombre_campo => $valor)
            { 
            	if($nombre_campo!='grid-articulos_length'){
                $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
                eval($asignacion); 
            
               }

            }
             //VALIDAR SI EXISTE HAY CAMBIOS EN EL ARTÍCULO
        /* $articulos=DB::select("SELECT
						dbo.Ordenes.OrdenID,
						dbo.OrdArticulos.CatArticuloID,
						dbo.CatArticulos.Nombre,
						dbo.CatArticulos.NombreCorto,
						color=ISNULL((SELECT Valor FROM OrdArtTiposPropiedades where OrdenID=dbo.Ordenes.OrdenID AND CatArticuloID=dbo.CatArticulos.CatArticuloID and CatOrdArtPropiedadesID=3), ''),
						paginas=ISNULL((SELECT Valor FROM OrdArtTiposPropiedades where OrdenID=dbo.Ordenes.OrdenID AND CatArticuloID=dbo.CatArticulos.CatArticuloID and CatOrdArtPropiedadesID=2), ''),
						observaciones=ISNULL((SELECT Valor FROM OrdArtTiposPropiedades where OrdenID=dbo.Ordenes.OrdenID AND CatArticuloID=dbo.CatArticulos.CatArticuloID and CatOrdArtPropiedadesID=6), ''),
						dbo.OrdArticulos.JobID,
						dbo.OrdArticulos.Cantidad,
						dbo.CatArticulos.CatArtFamiliaID
						FROM
						dbo.Ordenes
						INNER JOIN dbo.OrdArticulos ON dbo.OrdArticulos.OrdenID = dbo.Ordenes.OrdenID
						INNER JOIN dbo.CatArticulos ON dbo.OrdArticulos.CatArticuloID = dbo.CatArticulos.CatArticuloID
						where dbo.Ordenes.OrdenID=$OrdenID and dbo.OrdArticulos.CatArticuloID=$selectorArticulo
						AND dbo.OrdArticulos.JobID='$jobid' AND dbo.OrdArticulos.Cantidad=$copias


						");
						
				   print_r($articulos);

				   exit();*/
		if($editar=="false"){

        $secuencia= DB::table('OrdArticulos')->select('OrdArtSeqID')->where('OrdenID', '=',$OrdenID)->max('OrdArtSeqID');
        $precioBase= DB::table('CatPersArtPrecio')
        			 ->select('Precio')
                     ->where('CatArticuloID', '=',$selectorArticulo)
                     ->where('PersonaID', '=',1)
                     ->where('CatPrecioTipoID', '=',1)
                     ->max('Precio');
		   
		$total=$precioBase*$copias;

		   if(count($secuencia)==0){
	            $secuencia=1;
           }else{
           	    $secuencia=$secuencia+1;
           }
           //INSERTAR ARTICULOS A LA ORDEN
            DB::table('OrdArticulos')->insert(
			    ['OrdenID' =>$OrdenID, 
			      'CatArticuloID' =>$selectorArticulo, 
			      'OrdArtSeqID'=>$secuencia,
			      'OrdArtSTID'=>100,
			      'Cantidad'=>$copias,
			      'Total'=>$total,
			      'Fecha'=>date("d-m-Y"),
			      'JobID'=>"INT_$jobid",
			      'Ordenamiento1'=>(DB::table('OrdArticulos')->max('Ordenamiento1'))+1  //consultar el ultimo ordenamiento

			    ]);

            //actualizamos el monto de la orden, consultando primero si existe descuento
            $SubtotalOrden=DB::table('OrdArticulos')->where('OrdenID', '=',$OrdenID)->sum('Total');
            $descuento=DB::select("SELECT Descuento from OrdenesManuales where OrdenID=$OrdenID");
            $adicional=DB::select("SELECT Adicional from OrdenesManuales where OrdenID=$OrdenID");
            $envio=DB::select("SELECT Valor from OrdenesCatMetodoEnvio where OrdenID=$OrdenID");

            $totalOrden=($SubtotalOrden+$envio[0]->Valor+$adicional[0]->Adicional)-$descuento[0]->Descuento;


          	DB::table('Ordenes')
				->where('OrdenID', $OrdenID)
				->update(['MonTotal' =>$totalOrden]);


            //INSERTAMOS EL COLOR DE PASTA OrdArtTiposPropiedades

             DB::table('OrdArtTiposPropiedades')->insert(
			    ['OrdenID' =>$OrdenID, 
			      'CatArticuloID' =>$selectorArticulo, 
			      'OrdArtSeqID'=>$secuencia,
			      'CatOrdArtPropiedadesID'=>3,//COLOR
			      'Valor'=>$color
			      
			    ]);

               //INSERTAMOS EL NUMERO DE PAGINAS OrdArtTiposPropiedades

             DB::table('OrdArtTiposPropiedades')->insert(
			    ['OrdenID' =>$OrdenID, 
			      'CatArticuloID' =>$selectorArticulo, 
			      'OrdArtSeqID'=>$secuencia,
			      'CatOrdArtPropiedadesID'=>2,//paginas
			      'Valor'=>$paginas
			      
			    ]);

             //INSERTAMOS OBSERVACIONES

             DB::table('OrdArtTiposPropiedades')->insert(
			    ['OrdenID' =>$OrdenID, 
			      'CatArticuloID' =>$selectorArticulo, 
			      'OrdArtSeqID'=>$secuencia,
			      'CatOrdArtPropiedadesID'=>6,//paginas
			      'Valor'=>$observaciones
			      
			    ]);


             //INSERTAMOS DECUENTO Descuento
            /* DB::table('OrdArtTiposPropiedades')->insert(
			    ['OrdenID' =>$OrdenID, 
			      'CatArticuloID' =>$selectorArticulo, 
			      'OrdArtSeqID'=>$secuencia,
			      'CatOrdArtPropiedadesID'=>1,//paginas
			      'Valor'=>100
			      
			    ]);*/
			 return response()->json([
			    'ord' => $OrdenID,
			    'estatus'=>"true"
			    
			]);


           
           }else{

           	//comprobar estatus de la orden
           	 $estatusOrden=DB::select("SELECT OrdSTID from OrdenesManuales where OrdenID=$OrdenID");
			

           	//actualizar articulo
			if( $estatusOrden[0]->OrdSTID==51){

		    DB::table('OrdArticulos')
		    ->where('OrdenID', '=',$OrdenID)
		    ->where('CatArticuloID', '=',$selectorArticulo)
		    ->update(['Cantidad' => $copias]);

		     DB::table('OrdArtTiposPropiedades')
		    ->where('OrdenID', '=',$OrdenID)
		    ->where('CatArticuloID', '=',$selectorArticulo)
		    ->where('CatOrdArtPropiedadesID', '=',3) 
		    ->update(['Valor' =>$color]);


		    DB::table('OrdArtTiposPropiedades')
		    ->where('OrdenID', '=',$OrdenID)
		    ->where('CatArticuloID', '=',$selectorArticulo)
		    ->where('CatOrdArtPropiedadesID', '=',2) 
		    ->update(['Valor' =>$paginas]);


		    DB::table('OrdArtTiposPropiedades')
		    ->where('OrdenID', '=',$OrdenID)
		    ->where('CatArticuloID', '=',$selectorArticulo)
		    ->where('CatOrdArtPropiedadesID', '=',6) 
		    ->update(['Valor' =>$observaciones]);

		      return response()->json([
			    'ord' => $OrdenID,
			    'estatus'=>"true"
			    
			]);


		}else{
			return response()->json([
			    'ord' => $OrdenID,
			    'estatus'=>"false"
			    
			]);

		}


     }


           






	}    


	public function index()
	{
	 
		return view('gridordenes');

	}

	
}
