<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />
</head>

<body style="margin: 0;">

<div id="p2" style="overflow: hidden; position: relative; background-color: white; width: 2200px; height: 1237px;">
<style class="shared-css" type="text/css" >
.t {
	transform-origin: bottom left;
	z-index: 2;
	position: absolute;
	white-space: pre;
	overflow: visible;
	line-height: 1.5;
}
.text-container {
	white-space: pre;
}
@supports (-webkit-touch-callout: none) {
	.text-container {
		white-space: normal;
	}
}
</style>
<style type="text/css" >

#t1_2{left:228px;bottom:921px;letter-spacing:-2.96px;}
#t2_2{left:228px;bottom:1072px;letter-spacing:11.5px;}
#t3_2{left:1361px;bottom:943px;letter-spacing:-1.06px;}
#t4_2{left:1322px;bottom:888px;letter-spacing:-1.06px;}
#t5_2{left:428px;bottom:771px;letter-spacing:-1.27px;}
#t6_2{left:428px;bottom:518px;letter-spacing:-1.28px;}
#t7_2{left:428px;bottom:223px;letter-spacing:-1.27px;}
#t8_2{left:428px;bottom:730px;letter-spacing:-0.91px;}
#t9_2{left:428px;bottom:693px;letter-spacing:-0.92px;}
#ta_2{left:428px;bottom:476px;letter-spacing:-0.91px;word-spacing:2.08px;}
#tb_2{left:428px;bottom:440px;letter-spacing:-0.92px;}
#tc_2{left:428px;bottom:178px;letter-spacing:-0.92px;}

.s0_2{font-size:86px;font-family:Poppins-SemiBold_b5;color:#F4F0ED;}
.s1_2{font-size:31px;font-family:Poppins-Regular_b6;color:#F4F0ED;}
.s2_2{font-size:33px;font-family:Poppins-Regular_b6;color:#F4F0ED;}
.s3_2{font-size:34px;font-family:Poppins-SemiBold_b5;color:#3D2313;}
.s4_2{font-size:27px;font-family:Poppins-Regular_b6;color:#3D2313;}
</style>
<style id="fonts2" type="text/css" >

@font-face {
	font-family: Poppins-Regular_b6;
	src: url("../fonts/poppins-regular-webfont.woff2") format("woff");
}

@font-face {
	font-family: Poppins-SemiBold_b5;
	src: url("../fonts/poppins-regular-webfont.woff2") format("woff");
}

</style>
<div id="pg2Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg2" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 0;">
    <object width="100%" height="auto" data="{{ $formato->id === 3 ? '../2/2.boda.svg' : '../2/2.svg' }}" type="image/svg+xml" id="pdf2" style="max-width: 100%; height: auto; object-fit: contain;"></object>
</div>
<div class="text-container"><span id="t1_2" class="t s0_2">Requisitos del evento </span>
<span id="t2_2" class="t s1_2">FECHA: @php
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $fecha = date_create($cliente->fecha_estimada);
        $mes = $meses[date_format($fecha, 'n') - 1];
        echo date_format($fecha, 'd') . ' de ' . $mes . ' del ' . date_format($fecha, 'Y');
    @endphp</span>
<span id="t3_2" class="t s2_2">Cualquier petición que tengas, no dudes en </span>
<span id="t4_2" class="t s2_2">comentárnoslo e intentaremos hacerla realidad. </span>
<span id="t5_2" class="t s3_2">Localización </span>
<span id="t6_2" class="t s3_2">Preferencias musicales </span>
<span id="t7_2" class="t s3_2">Asistentes </span>
<span id="t8_2" class="t s4_2">La ubicacion del evento sera @php echo $cliente->ubicacion_local ?? 'Por confirmar'; @endphp</span>
<span id="t9_2" class="t s4_2"></span>
<span id="ta_2" class="t s4_2">La selección musical abarcará @php
    $referencias = $cliente->referencias_musicales ;
    if (empty($referencias)) {
        echo 'Por confirmar';
    } else {
        foreach ($referencias as $referencia) {
            echo $referencia['referencia'].",\n";
        }
    }
@endphp</span>
<span id="tb_2" class="t s4_2"></span>
<span id="tc_2" class="t s4_2">Aproximadamente asistirán @php echo $cliente->cantidad_personas ?? 'Por confirmar'; @endphp invitados.</span></div>

</div>
</body>
</html>
