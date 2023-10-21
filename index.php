<?php

require 'flight/Flight.php';

Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=calzzapato','root',''));


//Leer y mostrar datos
Flight::route('GET /archivos', function () {
    
    $sentencia = Flight::db()->prepare("SELECT * FROM `archivos`");  
    $sentencia -> execute();
    $datos = $sentencia ->fetchAll();

    Flight::json($datos);
});

Flight::route('GET /bitacora', function () {
    
    //bitacora
    $sentencia = Flight::db()->prepare("
    select b.idBitacora, b.fecha, sc.nombre as Sucursal
	from Bitacora b
	INNER JOIN Sucursal sc on (sc.idSucursal = b.idSucursal)
	WHERE b.fecha = '20230411'
	GROUP BY b.idBitacora, b.fecha, sc.nombre;
    ");     
    
    //bitacoradetalle
    $sentenciaDetalle = Flight::db()->prepare("select bd.idBitacora, bd.Hora, bd.Entraron
    from bitacoradetalle bd
    INNER JOIN bitacora b on (b.idBitacora = bd.idBitacora)
    WHERE b.fecha = '20230411'");
    
    $sentencia -> execute();
    $sentenciaDetalle -> execute();
    // $datos = $sentencia ->fetchAll();

    $datos = array();
    $datosDetalle= array();     

    while ($rowD = $sentenciaDetalle -> fetch(PDO::FETCH_ASSOC)){
        extract($rowD);                
				$valoresDetalles = array(
	
						"idBitacora" => $idBitacora,
						"Hora" => $Hora,
						"Entraron" => $Entraron,                        
				);                               		
                array_push($datosDetalle, $valoresDetalles);	
            }
            
    while ($row = $sentencia -> fetch(PDO::FETCH_ASSOC)){
        extract($row);     

        $arregloHoras = array();
        
        foreach($datosDetalle as $dato){
            if($dato ['idBitacora'] == $idBitacora){
                array_push($arregloHoras, $dato);
            }
        }
				$valores = array(
	
						"idBitacora" => $idBitacora,
						"fecha" => $fecha,
						"Sucursal" => $Sucursal,  
                        "Detalle" => $arregloHoras    
                                                
				);  
                array_push($datos, $valores);                                                                         
            }          
            Flight::json($datos);               
        }
    );
Flight::route('GET /bitacora/fechaIncio=@FechaInicio&fechaFin=@fechaFinal', function ($FechaInicio,$FechaFinal) {
    
    //bitacora
    $sentencia = Flight::db()->prepare("
    select b.idBitacora, b.fecha, sc.nombre as Sucursal
	from Bitacora b
	INNER JOIN Sucursal sc on (sc.idSucursal = b.idSucursal)
	WHERE b.fecha BETWEEN '$FechaInicio' AND '$FechaFinal'
	GROUP BY b.idBitacora, b.fecha, sc.nombre;
    ");     
    
    //bitacoradetalle
    $sentenciaDetalle = Flight::db()->prepare("select bd.idBitacora, bd.Hora, bd.Entraron
    from bitacoradetalle bd
    INNER JOIN bitacora b on (b.idBitacora = bd.idBitacora)
    WHERE b.fecha BETWEEN '$FechaInicio' AND '$FechaFinal'");
    
    $sentencia -> execute();
    $sentenciaDetalle -> execute();
    // $datos = $sentencia ->fetchAll();

    $datos = array();
    $datosDetalle= array();     

    //Se ingresan a los valores de Detalle y se guardan los datos en $datosDetalles
    while ($rowD = $sentenciaDetalle -> fetch(PDO::FETCH_ASSOC)){
        extract($rowD);                
				$valoresDetalles = array(
	
						"idBitacora" => $idBitacora,
						"Hora" => $Hora,
						"Entraron" => $Entraron,                        
				);                               		
                array_push($datosDetalle, $valoresDetalles);	
            }
            
    //
    while ($row = $sentencia -> fetch(PDO::FETCH_ASSOC)){
        extract($row);     

        $arregloHoras = array();
        
        foreach($datosDetalle as $dato){
            if($dato ['idBitacora'] == $idBitacora){
                array_push($arregloHoras, $dato);
            }
        }
				$valores = array(
	
						"idBitacora" => $idBitacora,
						"fecha" => $fecha,
						"Sucursal" => $Sucursal,  
                        "Detalle" => $arregloHoras    
                                                
				);  
                array_push($datos, $valores);                                                                         
            }          
            Flight::json($datos);               
        }
    );

Flight::route('GET /bitacoradetalle', function () {
    
    $sentencia = Flight::db()->prepare("select bd.idBitacora, bd.Hora, bd.Entraron
    from bitacoradetalle bd
    INNER JOIN bitacora b on (b.idBitacora = bd.idBitacora)
    WHERE b.fecha = '20230411'; 
    ");  
    $sentencia -> execute();
    $datos = $sentencia ->fetchAll();

    Flight::json($datos);
});

Flight::route('GET /camara', function () {
    
    $sentencia = Flight::db()->prepare("SELECT * FROM `camara`");  
    $sentencia -> execute();
    $datos = $sentencia ->fetchAll();

    Flight::json($datos);
});

Flight::route('GET /empresa', function () {
    
    $sentencia = Flight::db()->prepare("SELECT * FROM `empresa`");  
    $sentencia -> execute();
    $datos = $sentencia ->fetchAll();

    Flight::json($datos);
});

Flight::route('GET /porcentaje', function () {
    
    $sentencia = Flight::db()->prepare("SELECT * FROM `porcentaje`");  
    $sentencia -> execute();
    $datos = $sentencia ->fetchAll();

    Flight::json($datos);
});

Flight::route('GET /sucursal', function () {
    
    $sentencia = Flight::db()->prepare("SELECT * FROM `sucursal`");  
    $sentencia -> execute();
    $datos = $sentencia ->fetchAll();

    Flight::json($datos);
});
Flight::start();