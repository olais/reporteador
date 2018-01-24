<?php 
namespace imprimart\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ReporteadorController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}
	
	public function index()
	{

		return view('resultquerys');

	}

	public function generaconexion(){

		foreach($_POST as $nombre_campo => $valor)
            { 
                $asignacion = "\$" . $nombre_campo . "='" . trim($valor) . "';"; 
                eval($asignacion); 
            }

//eliminar configuración actual

unlink('../.env');
//generamos la nueva conexión

$datos="
		APP_ENV=local
		APP_DEBUG=true
		APP_KEY=36yoihKoc9csFA39LDMyoexlEl7a5GzL

		DB_HOST=$servidor
		DB_DATABASE=$base
		DB_USERNAME=$usuariobase
		DB_PASSWORD=$pwdbase

		CACHE_DRIVER=file
		SESSION_DRIVER=file
		QUEUE_DRIVER=sync

		MAIL_DRIVER=smtp
		MAIL_HOST=mailtrap.io
		MAIL_PORT=2525
		MAIL_USERNAME=null
		MAIL_PASSWORD=null
			";

         $conexion= file_put_contents('../.env', $datos.PHP_EOL , FILE_APPEND);
	}

	public function probarconexion(){

		foreach($_POST as $nombre_campo => $valor)
            { 
                $asignacion = "\$" . $nombre_campo . "='" . trim($valor) . "';"; 
                eval($asignacion); 
            }
            switch ($motor) {
           	case 'mysql':
           		# code...
           
			@$link = mysql_connect("$servidor","$usuariobase","$pwdbase");
			@$conn = mysql_select_db($base, $link);

	 		break;
           	case 'sqlsrv':
           		# code...
         	@$connectionInfo = array( "Database"=>"$base", "UID"=>"$usuariobase", "PWD"=>"$pwdbase");
			@$conn = sqlsrv_connect( $servidor, $connectionInfo);

           		break;
           	default:
           		# code...
           		break;
           }
           //validamos la conexión
			if( $conn ) {
				     $mensaje= "Conexión establecida";
				     $valida="true";
				}else{
				     $valida="false";
				     $mensaje= "Conexión no establecida verifique datos";
				}

           $response=array("msj"=>$mensaje,"valida"=>$valida);
			    return json_encode($response) ;

	}


	public function querys()
	{
	 	
		      $columnas="";
		      try{
			  $query=DB::select($_REQUEST['consulta']);
			
				foreach ($query[0] as $key => $value) {
					
					$columnas.="<th>".$key."</th>";
				}
				$error=array("error"=>"false","msj"=>"ok");
				$tabla['columnas']=array($columnas);
		     	return json_encode($tabla) ;
		      }catch (\Exception $e) {
		     	$error=array("error"=>"true","msj"=>$e->getMessage());
			    return json_encode($error) ;
			}
	}

	public function llenaGridQuery(){

			 $filas=array();
			 $clientes=DB::select($_REQUEST['consulta']);
			
			    $datos=array();
		        $columnas=array();
		        foreach ($clientes as $columna=>$valor) {
		              foreach ($valor as $key => $value) {
                  array_push($columnas,$key);
                }
         }
		        $duplicates = array_unique($columnas);
		        $query = '';

       for ($i=0;$i<count($duplicates);$i++){
            $query .= "\$" . "valor->" . trim($duplicates[$i]) . ','; 
             }

		        $cadena =substr ($query, 0, -1);
		        $contenidoArray= "\$" ."datos[]=array($cadena);";

           foreach ($clientes as $valor) {
			     eval($contenidoArray);
             }
             
		 $filas['data']=$datos;
		 return json_encode($filas) ;


	}

	public function generarvista(){

		//insertamos la consulta
		$nombreVista = str_replace(' ', '_', $_REQUEST['reporte']);
		DB::table('reportes')->insert(
 		['query' =>$_REQUEST['consulta'],"nombre_reporte"=>$_REQUEST['reporte'],"nombre_vista"=>"r_".$nombreVista]);

		@$generarvista=DB::statement( "CREATE VIEW ". "r_".trim($nombreVista). " AS  ".$_REQUEST['consulta']."");
		
		$valida=array("valida"=>"true");
		     	return json_encode($valida) ;
			     
	}

	//consultamos las vistas generadas en relación a los nombres de los reportes

	public function consultavistas(){

		//$esquema='mysql';
		$esquema='sqlsrv';


    	switch ($esquema) {
			case 'mysql':
				  $consulta="SELECT TABLE_NAME AS vistas FROM INFORMATION_SCHEMA.tables WHERE TABLE_SCHEMA='starbucks' and TABLE_TYPE='VIEW'";
				break;
			case 'sqlsrv':
				  $consulta="SELECT TABLE_NAME AS vistas FROM Information_Schema.Tables where TABLE_TYPE = 'VIEW'";
				break;
			
			default:
				# code...
				break;

		}
		
		 $extraerVistas=DB::select($consulta);

		

		 $vistasGuardadas=DB::select("SELECT  query,nombre_vista,nombre_reporte from reportes");
         $armarMenu='';

         //print_r($vistasGuardadas);

         $armarMenu .="<li class='active'><a href='#''>Reportes</a></li>";
         //foreach ($extraerVistas as $key => $value) {
         	foreach ($vistasGuardadas as $vista) {
         	       /*if($vista->nombre_vista==$value->vistas){
         	       	 $armarMenu .="<li id='SELECT * FROM ".$vista->nombre_vista."' class='".$vista->nombre_reporte."'><a href='#'><span class='glyphicon glyphicon-folder-open'></span> ".$vista->nombre_reporte."</a></li>";
          			 }*/
          			  $armarMenu .="<li id='".$vista->query."' class='".$vista->nombre_reporte."'><a href='#'><span class='glyphicon glyphicon-folder-open'></span> ".$vista->nombre_reporte."</a></li>";
         	       }
               //}
               
         
          $submenu['vistas']=array($armarMenu);
		     	return json_encode($submenu) ;
  
	    }


	
}
