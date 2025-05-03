<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />
</head>

<body style="margin: 0;">

<div id="p4" style="overflow: hidden; position: relative; background-color: white; width: 2200px; height: 1237px;">
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

#t1_4{left:129px;bottom:907px;letter-spacing:-0.14px;}
#t2_4{left:1480px;bottom:914px;letter-spacing:0.05px;}
#t3_4{left:1712px;bottom:937px;letter-spacing:0.05px;}
#t4_4{left:1719px;bottom:892px;letter-spacing:0.05px;}
#t5_4{left:1933px;bottom:914px;letter-spacing:0.05px;}
#t6_4{left:129px;bottom:797px;letter-spacing:0.04px;}
#t7_4{left:129px;bottom:752px;letter-spacing:0.04px;}
#t8_4{left:183px;bottom:708px;letter-spacing:0.04px;}
#t9_4{left:183px;bottom:663px;letter-spacing:0.04px;}
#ta_4{left:183px;bottom:618px;letter-spacing:0.04px;}
#tb_4{left:183px;bottom:573px;letter-spacing:0.04px;}
#tc_4{left:183px;bottom:529px;letter-spacing:0.04px;}
#td_4{left:238px;bottom:484px;letter-spacing:0.04px;}
#te_4{left:238px;bottom:439px;letter-spacing:0.04px;}
#tf_4{left:1544px;bottom:618px;}
#tg_4{left:1735px;bottom:618px;letter-spacing:0.05px;}
#th_4{left:1952px;bottom:618px;letter-spacing:0.05px;}
#ti_4{left:129px;bottom:344px;letter-spacing:0.05px;}
#tj_4{left:1949px;bottom:344px;letter-spacing:0.05px;}
#tk_4{left:129px;bottom:249px;letter-spacing:0.04px;}
#tl_4{left:1738px;bottom:249px;letter-spacing:0.05px;}
#tm_4{left:1949px;bottom:249px;letter-spacing:0.05px;}

.s0_4{font-size:40px;font-family:Aileron-Bold_b9;color:#F4F0ED;}
.s1_4{font-size:32px;font-family:Aileron-Bold_b9;color:#FFF;}
.s2_4{font-size:32px;font-family:Aileron-Regular_ba;color:#FFF;}
</style>
<style id="fonts4" type="text/css" >

@font-face {
	font-family: Aileron-Bold_b9;
	src: url("fonts/Aileron-Bold_b9.woff") format("woff");
}

@font-face {
	font-family: Aileron-Regular_ba;
	src: url("fonts/Aileron-Regular_ba.woff") format("woff");
}

</style>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    .budget-table {
        width: 90%;
        border-collapse: collapse;
        color: white;
        font-size: 20px;
        border: 4px solid white;
        font-family: 'Poppins', sans-serif;
    }
    .budget-table th, .budget-table td {
        padding: 25px;
        text-align: left;
        border-bottom: 4px solid white;
        border-right: 4px solid white;
        font-size: 40px;
        font-family: 'Poppins', sans-serif;
    }
    .budget-table th {
        background-color: #f0f28f;
        font-weight: 600;
        font-size: 40px;
        padding: 30px;
        font-family: 'Poppins', sans-serif;
        color: black;
    }
    .budget-table tr:last-child td {
        border-bottom: none;
    }
    .total-cell {
        background-color: #f0f28f;
        font-weight: 600;
        padding: 30px;
        text-align: right;
        font-size: 40px;
        border-right: none;
        font-family: 'Poppins', sans-serif;
        color: black;
    }
    .budget-table td {
        font-size: 40px;
        font-family: 'Poppins', sans-serif;
    }
    .table-wrapper {
        position: absolute;
        top: 50%;
        left: 55%;
        transform: translate(-50%, -50%);
        width: 2200px;
        max-width: 30000px;
        margin: 0 auto;
        padding: 40px;
        border-radius: 10px;
        z-index: 1;
    }
    .budget-table th:first-child, .budget-table td:first-child {
        width: 60%;
    }
    .budget-table th:not(:first-child), .budget-table td:not(:first-child) {
        width: 13.33%;
    }
    .budget-table td:last-child {
        border-right: none;
    }
</style>

<div id="pg4Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg4" style="overflow: hidden; position: relative; background-color: #2e3221; width: 2200px; height: 1237px;">
    <object width="2200" height="1237" data="../4/4.svg" type="image/svg+xml" id="pdf4" style="width:2200px; height:1237px; z-index: 0;"></object>
    <div class="table-wrapper">
        @if(isset($cliente->budget_items) && is_array($cliente->budget_items) && count($cliente->budget_items) > 0)
            <table class="budget-table">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Unidad</th>
                        <th>Importe Unitario</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal = 0;
                        $iva = 0;
                        $total = 0;
                    @endphp
                    @foreach($cliente->budget_items as $item)
                        <tr>
                            <td>
                                <div style="margin-bottom: 20px;">{{ $item['service'] ?? 'Sin especificar' }}</div>
                                @if(isset($item['descripciones']) && is_array($item['descripciones']) && count($item['descripciones']) > 0)
                                    <div style="font-size: 30px; color: #e0e0e0;">
                                        @foreach($item['descripciones'] as $descripcion)
                                            <div style="margin-bottom: 10px;">• {{ $descripcion['descripcion'] ?? '' }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>{{ $item['unit'] ?? '1' }}</td>
                            <td>€{{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                            <td>€{{ number_format($item['total'] ?? 0, 2) }}</td>
                            @php
                                $subtotal += (float)($item['total'] ?? 0);
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total-cell">Subtotal:</td>
                        <td class="total-cell">€{{ number_format($subtotal, 2) }}</td>
                    </tr>
                    @if(isset($cliente->iva_incluido) && $cliente->iva_incluido)
                        <tr>
                            <td colspan="3" class="total-cell">IVA (21%):</td>
                            <td class="total-cell">€{{ number_format($subtotal * 0.21, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="total-cell">Total General:</td>
                            <td class="total-cell">€{{ number_format($subtotal * 1.21, 2) }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3" class="total-cell">Total General:</td>
                            <td class="total-cell">€{{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endif
                </tfoot>
            </table>
        @else
            <p style="color: white; text-align: center;">No se han especificado items en el presupuesto.</p>
        @endif
    </div>
</div>

</div>
</body>
</html>
