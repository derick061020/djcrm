<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />

</head>

<body style="margin: 0;">

<div id="p1" style="overflow: hidden; position: relative; background-color: white; width: 2200px; height: 1237px;">
@php
use Illuminate\Support\Str;
@endphp
<style class="shared-css" type="text/css" >
.t {
	transform-origin: bottom left;
	z-index: 2;
	position: absolute;
	white-space: pre;
	overflow: visible;
	line-height: 1.5;
}
.text {
	transform-origin: bottom left;
	z-index: 2;
	white-space: normal;
	overflow: visible;
	line-height: 1.5;
}
.centered-texts{
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
	white-space: normal;
}
.text-container {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
	white-space: pre;
}
@supports (-webkit-touch-callout: none) {
	.text-container {
		white-space: normal;
	}
}
</style>

<style type="text/css" >

#t1_1{left:1930px;bottom:21px;letter-spacing:-0.13px;}
#t2_1{left:381px;bottom:602px;letter-spacing:-0.17px;}
#t3_1{left:40px;bottom:22px;letter-spacing:10.41px;}
#t4_1{left:533px;bottom:532px;letter-spacing:9.22px;}
#t5_1{left:873px;bottom:485px;letter-spacing:9.24px;}

.s0_1{font-size:40px;font-family:RedHatDisplay-Regular_b3;color:#FFF;}
.s1_1{font-size:121px;font-family:Poppins-Regular_b4;color:#FFF;}
.s2_1{font-size:34px;font-family:RedHatDisplay-Regular_b3;color:#FFF;}
.s3_1{font-size:43px;font-family:RedHatDisplay-Regular_b3;color:#FFF;}
</style>
<style id="fonts1" type="text/css" >

@font-face {
	font-family: Poppins-Regular_b4;
	src: url("../fonts/poppins-regular-webfont.woff2") format("woff");
}

@font-face {
	font-family: RedHatDisplay-Regular_b3;
	src: url("../fonts/RedHatDisplay-Regular_b3.woff") format("woff");
}

</style>
<div id="pg1Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg1" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 0;{{ $formato->id === 3 ? ' margin-top: 400px;' : '' }}">
    <object width="100%" height="auto" data="/storage/{{ $formato->data['image_page_1'] }}" type="image/svg+xml" id="pdf1" style="max-width: 100%; height: auto; object-fit: contain;"></object>
</div>
<div class="text-container" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <span id="t1_1" class="t s0_1">www.lafest.es</span>
    <span id="t3_1" class="t s2_1">LAFEST</span>
	<div class="centered-texts">
		<span id="t2_1" class="text s1_1">{{$formato->data['title_page_1']}} {{ $cliente->nombre }}</span>
		<span id="t4_1" style="text-align: center;max-width: 1200px;letter-spacing: 10px;" class="text s3_1">{{ $formato->data['slogan_page_1'] }}</span>
	</div>
</div>

</div>
</body>

</html>
