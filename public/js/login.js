$(document).ready(function(){

	 var path=$('meta[name="base_url"]').attr('content'); //para el base url

		$("#Foto-login").submit(function(){
            	var datos=$(this).serialize();
            	$.post(path+"/Login",datos,
            		function(data){

            		},'json');
            		
				return false;
		});

});