
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />
</head>

<body style="margin: 0;">

<div id="p5" style="overflow: hidden; position: relative; background-color: white; width: 2200px; height: 1237px;">
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

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
</style>

@if($formato->id === 3)
<style type="text/css" >
#t1_5{left:123px;bottom:884px;letter-spacing:-2.98px;}
#t2_5{left:123px;bottom:782px;letter-spacing:-2.98px;}
#t3_5{left:123px;bottom:645px;letter-spacing:-1.43px;word-spacing:9.23px;}
#t4_5{left:123px;bottom:593px;letter-spacing:-1.43px;word-spacing:16.3px;}
#t5_5{left:123px;bottom:542px;letter-spacing:-1.44px;word-spacing:25.7px;}
#t6_5{left:123px;bottom:490px;letter-spacing:-1.4px;}
#t7_5{left:123px;bottom:387px;letter-spacing:-1.41px;word-spacing:3.4px;}
#t8_5{left:123px;bottom:336px;letter-spacing:-1.4px;word-spacing:8.6px;}
#t9_5{left:123px;bottom:284px;letter-spacing:-1.41px;word-spacing:4.22px;}
#ta_5{left:123px;bottom:233px;letter-spacing:-1.4px;}
#tb_5{left:123px;bottom:1034px;letter-spacing:-1.54px;}

.s0_5{font-size:86px;font-family:'Poppins', sans-serif;font-weight:700;color:#F4F0ED;}
.s1_5{font-size:38px;font-family:'Poppins', sans-serif;font-weight:400;color:#F4F0ED;}
.s2_5{font-size:46px;font-family:'Poppins', sans-serif;font-weight:700;color:#F0E07F;}
</style>
@else
<style type="text/css" >
#t1_5{left:97px;bottom:836px;letter-spacing:-2.54px;}
#t2_5{left:97px;bottom:743px;letter-spacing:-2.53px;}
#t3_5{left:97px;bottom:635px;letter-spacing:-1.43px;word-spacing:7.25px;}
#t4_5{left:97px;bottom:583px;letter-spacing:-1.44px;word-spacing:9.04px;}
#t5_5{left:97px;bottom:532px;letter-spacing:-1.43px;word-spacing:7.81px;}
#t6_5{left:97px;bottom:480px;letter-spacing:-1.43px;word-spacing:12.97px;}
#t7_5{left:97px;bottom:429px;letter-spacing:-1.45px;word-spacing:21.34px;}
#t8_5{left:97px;bottom:377px;letter-spacing:-1.4px;}
#t9_5{left:97px;bottom:274px;letter-spacing:-1.41px;word-spacing:4.27px;}
#ta_5{left:97px;bottom:222px;letter-spacing:-1.4px;}
#tb_5{left:90px;bottom:968px;letter-spacing:-1.54px;}

.s0_5{font-size:78px;font-family:'Poppins', sans-serif;font-weight:700;color:#F4F0ED;}
.s1_5{font-size:38px;font-family:'Poppins', sans-serif;font-weight:400;color:#F4F0ED;}
.s2_5{font-size:46px;font-family:'Poppins', sans-serif;font-weight:700;color:#F0E07F;}
</style>
@endif

<div id="pg5Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg5" style="-webkit-user-select: none;"><object width="2200" height="1237" data="{{ $formato->id === 3 ? '../5/5.boda.svg' : '../5/5.svg' }}" type="image/svg+xml" id="pdf5" style="width:2200px; height:1237px; z-index: 0;"></object></div>
<div class="text-container">
    @if($formato->id === 3)
        <span id="t1_5" class="t s0_5">La banda sonora de</span>
        <span id="t2_5" class="t s0_5">vuestro amor eterno</span>
        <span id="t3_5" class="t s1_5">Queremos que recuerdes este gran día por</span>
        <span id="t4_5" class="t s1_5">siempre. Por eso, queremos regalarte una</span>
        <span id="t5_5" class="t s1_5">placa personalizada con la playlist que</span>
        <span id="t6_5" class="t s1_5">pinchará nuestro DJ en tu boda.</span>
        <span id="t7_5" class="t s1_5">Disfruta de esta placa decorativa y accede a</span>
        <span id="t8_5" class="t s1_5">la playlist a través del código de Spotify. La</span>
        <span id="t9_5" class="t s1_5">placa viene con un soporte acrílico para que</span>
        <span id="ta_5" class="t s1_5">puedas utilizarlo como elemento decorativo.</span>
        <span id="tb_5" class="t s2_5">¡Te lo incluimos gratis!</span>
    @else
        <span id="t1_5" class="t s0_5">El toque mágico para </span>
        <span id="t2_5" class="t s0_5">iluminar tu gran día </span>
        <span id="t3_5" class="t s1_5">Haz que el cumpleaños sea inolvidable con </span>
        <span id="t4_5" class="t s1_5">unas bengalas que llenará el momento de </span>
        <span id="t5_5" class="t s1_5">luz y magia. Cada chispa será parte de un </span>
        <span id="t6_5" class="t s1_5">recuerdo especial, creando una atmósfera </span>
        <span id="t7_5" class="t s1_5">única para celebrar a esa persona tan </span>
        <span id="t8_5" class="t s1_5">especial. </span>
        <span id="t9_5" class="t s1_5">Llevaremos 10 bengalas para que las utilices </span>
        <span id="ta_5" class="t s1_5">como quieras durante la fiesta. </span>
        <span id="tb_5" class="t s2_5">¡Te lo incluimos gratis! </span>
    @endif
</div>

</div>
</body>
</html>
