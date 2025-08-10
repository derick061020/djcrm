<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />
</head>

<body style="margin: 0;">

<div id="p3" style="overflow: hidden; position: relative; background-color: white; width: 2200px; height: 1237px;">
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

#t1_3{left:1361px;bottom:943px;letter-spacing:-1.06px;}
#t2_3{left:1322px;bottom:888px;letter-spacing:-1.06px;}
#t3_3{left:194px;bottom:921px;letter-spacing:-2.96px;}
#t4_3{left:194px;bottom:1072px;letter-spacing:11.51px;}
#t5_3{left:395px;bottom:761px;letter-spacing:-1.27px;}
#t6_3{left:395px;bottom:506px;letter-spacing:-1.28px;}
#t7_3{left:395px;bottom:221px;letter-spacing:-1.27px;}
#t8_3{left:395px;bottom:719px;letter-spacing:-0.91px;}
#t9_3{left:395px;bottom:683px;letter-spacing:-0.92px;}
#ta_3{left:395px;bottom:463px;letter-spacing:-0.91px;word-spacing:2.08px;}
#tb_3{left:395px;bottom:426px;letter-spacing:-0.91px;}
#tc_3{left:395px;bottom:176px;letter-spacing:-0.92px;}

.s0_3{font-size:33px;font-family:Poppins-Regular_k7;color:#F4F0ED;}
.s1_3{font-size:86px;font-family:Poppins-SemiBold_k8;color:#F4F0ED;}
.s2_3{font-size:31px;font-family:Poppins-Regular_k7;color:#F4F0ED;}
.s3_3{font-size:34px;font-family:Poppins-SemiBold_k9;color:#3D2313;}
.s4_3{font-size:27px;font-family:Poppins-Regular_ka;color:#3D2313;}
</style>
<style id="fonts3" type="text/css" >

@font-face {
	font-family: Poppins-Regular_k7;
	src: url("../fonts/Poppins-Regular_k7.woff") format("woff");
}

@font-face {
	font-family: Poppins-Regular_ka;
	src: url("../fonts/Poppins-Regular_ka.woff") format("woff");
}

@font-face {
	font-family: Poppins-SemiBold_k8;
	src: url("../fonts/Poppins-SemiBold_k8.woff") format("woff");
}

@font-face {
	font-family: Poppins-SemiBold_k9;
	src: url("../fonts/Poppins-SemiBold_k9.woff") format("woff");
}

</style>
<div id="pg3Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg3" style="-webkit-user-select: none;"><object width="2200" height="1237" data="../3/3.svg" type="image/svg+xml" id="pdf3" style="width:2200px; height:1237px; z-index: 0;"></object></div>
<div class="text-container"><span id="t1_3" class="t s0_3">Cualquier petición que tengas, no dudes en </span>
<span id="t2_3" class="t s0_3">comentárnoslo e intentaremos hacerla realidad. </span>
<span id="t3_3" class="t s1_3">Requisitos del evento </span>
<span id="t4_3" class="t s2_3">FECHA: @php
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $fecha = date_create($cliente->fecha_estimada);
        $mes = $meses[date_format($fecha, 'n') - 1];
        echo date_format($fecha, 'd') . ' de ' . $mes . ' del ' . date_format($fecha, 'Y');
    @endphp </span>
<span id="t5_3" class="t s3_3">Localización </span>
<span id="t6_3" class="t s3_3">Preferencias musicales </span>
<span id="t7_3" class="t s3_3">Asistentes </span>
<span id="t8_3" class="t s4_3">La localización del evento será en @php echo $cliente->ubicacion_local ?? 'Por confirmar'; @endphp </span>
<span id="ta_3" class="t s4_3">La selección musical abarcará todo tipo de hits </span>
<span id="tb_3" class="t s4_3">de música variada a preferencias del cliente. </span>
<span id="tc_3" class="t s4_3">Asistirán aproximadamente @php echo $cliente->cantidad_personas ?? 'Por confirmar'; @endphp invitados. </span></div>

</div>
</body>
</html>
