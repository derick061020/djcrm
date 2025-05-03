<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />
<style class="shared-css" type="text/css" >
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
</style>
</head>

<body style="margin: 0;">

<div id="p2" style="overflow: hidden; position: relative; background-color: white; width: 2200px; height: 1237px;">
<style class="shared-css" type="text/css" >
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

.text-container {
	white-space: pre;
}

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

#t1_2{left:158px;bottom:833px;letter-spacing:0.15px;word-spacing:9.66px;}
#t2_2{left:541px;bottom:833px;letter-spacing:0.17px;word-spacing:9.66px;}
#t3_2{left:158px;bottom:758px;letter-spacing:0.14px;}
#t4_2{left:158px;bottom:609px;letter-spacing:0.14px;}
#t5_2{left:532px;bottom:609px;letter-spacing:0.13px;}
#t6_2{left:158px;bottom:460px;letter-spacing:0.13px;}
#t7_2{left:387px;bottom:460px;letter-spacing:0.15px;}
#t8_2{left:158px;bottom:311px;letter-spacing:0.16px;word-spacing:12.53px;}
#t9_2{left:768px;bottom:311px;letter-spacing:0.16px;word-spacing:12.53px;}
#ta_2{left:158px;bottom:237px;letter-spacing:0.15px;}
#tb_2{left:124px;bottom:1024px;letter-spacing:0.07px;}

.s0_2 {
	font-size: 44px;
	font-family: Poppins;
	color: rgba(255,255,255,0.8);
	font-weight: 600;
}

.s1_2 {
	font-size: 44px;
	font-family: Poppins;
	color: rgba(255,255,255,0.8);
	font-weight: 400;
}

.s2_2 {
	font-size: 64px;
	font-family: Poppins;
	color: #F0F28F;
	font-weight: 600;
}
</style>
<style id="fonts2" type="text/css" >

@font-face {
	font-family: Poppins-Bold_ey;
	src: url("../fonts/Poppins-Bold_ey.woff") format("woff");
}

@font-face {
	font-family: Poppins-Bold_ey_1;
	src: url("../fonts/Poppins-Bold_ey_1.woff") format("woff");
}

@font-face {
	font-family: Poppins-Regular_fw;
	src: url("../fonts/Poppins-Regular_fw.woff") format("woff");
}

</style>
<div id="pg2Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg2" style="-webkit-user-select: none;"><object width="2200" height="1237" data="../2/2.svg" type="image/svg+xml" id="pdf2" style="width:2200px; height:1237px; z-index: 0;"></object></div>
<div class="text-container">
    <span id="t1_2" class="t s0_2">Tipo de evento: </span>
    <span id="t2_2" class="t s1_2">{{ $cliente->tipo_evento === 'fiesta_corporativa' ? 'Evento corporativo con DJ' : substr($cliente->tipo_evento, 0, 25) }} </span>
    <span id="t4_2" class="t s0_2">Fecha y horario: </span>
    <span id="t5_2" class="t s1_2">   @php
            $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            $fecha = date_create($cliente->fecha_estimada);
            $mes = $meses[date_format($fecha, 'n') - 1];
            echo date_format($fecha, 'd') . ' de ' . $mes;
        @endphp</span>
    <span id="t6_2" class="t s0_2">Set de DJ: </span>
    <span id="t7_2" class="t s1_2">  @php
            $hora_inicio = date_create($cliente->hora_inicio);
            $hora_fin = date_create($cliente->hora_fin);
            $diferencia = date_diff($hora_inicio, $hora_fin);
            $horas = $diferencia->h;
            echo "Actuación de " . $horas . " horas.";
        @endphp
    </span>
    <span id="t8_2" class="t s0_2">Equipamiento de sonido: </span>
    <span id="t9_2" class="t s1_2">LAFEST proporcionará </span>
    <span id="ta_2" class="t s1_2">el equipo de sonido y música del DJ. </span>
    <span id="tb_2" class="t s2_2">Requisitos del cliente </span>
</div>

</div>
</body>
</html>
