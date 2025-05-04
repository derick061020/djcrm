<?php

use Illuminate\Support\Facades\Route;
use App\Models\Clientes;
use App\Models\Formatos;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::get('/webhook', [App\Http\Controllers\WebhookController::class, 'webhook']);
Route::post('/webhook', [App\Http\Controllers\WebhookController::class, 'recibe']);

// Rutas para el presupuesto
Route::get('/presupuesto/{cliente}/index.html', function(Clientes $cliente) {
    if ($cliente->formato_evento == 4) {
        return redirect()->route('presupuestoCorporativo.index', ['cliente' => $cliente]);
    }
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuesto.index', compact('cliente', 'formato'));
})->name('presupuesto.index')
->where('cliente', '[0-9]+');

Route::get('/presupuesto/{cliente}/1.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuesto.1', compact('cliente', 'formato'));
})->name('presupuesto.page1')
->where('cliente', '[0-9]+');

Route::get('/presupuesto/{cliente}/2.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuesto.2', compact('cliente', 'formato'));
})->name('presupuesto.page2')
->where('cliente', '[0-9]+');

Route::get('/presupuesto/{cliente}/3.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuesto.3', compact('cliente', 'formato'));
})->name('presupuesto.page3')
->where('cliente', '[0-9]+');

Route::get('/presupuesto/{cliente}/4.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuesto.4', compact('cliente', 'formato'));
})->name('presupuesto.page4')
->where('cliente', '[0-9]+');

Route::get('/presupuesto/{cliente}/5.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuesto.5', compact('cliente', 'formato'));
})->name('presupuesto.page5')
->where('cliente', '[0-9]+');

Route::get('/presupuesto/{cliente}/6.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuesto.6', compact('cliente', 'formato'));
})->name('presupuesto.page6')
->where('cliente', '[0-9]+');

Route::get('/presupuesto/{cliente}/7.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuesto.7', compact('cliente', 'formato'));
})->name('presupuesto.page7')
->where('cliente', '[0-9]+');

Route::get('/presupuesto/{cliente}/8.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuesto.8', compact('cliente', 'formato'));
})->name('presupuesto.page8')
->where('cliente', '[0-9]+');

Route::get('/presupuesto/{cliente}/9.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuesto.9', compact('cliente', 'formato'));
})->name('presupuesto.page9')
->where('cliente', '[0-9]+');

// Rutas para el presupuesto corporativo
Route::get('/presupuestoCorporativo/{cliente}/index.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuestoCorporativo.index', compact('cliente', 'formato'));
})->name('presupuestoCorporativo.index')
->where('cliente', '[0-9]+');

Route::get('/presupuestoCorporativo/{cliente}/1.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuestoCorporativo.1', compact('cliente', 'formato'));
})->name('presupuestoCorporativo.page1')
->where('cliente', '[0-9]+');

Route::get('/presupuestoCorporativo/{cliente}/2.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuestoCorporativo.2', compact('cliente', 'formato'));
})->name('presupuestoCorporativo.page2')
->where('cliente', '[0-9]+');

Route::get('/presupuestoCorporativo/{cliente}/3.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuestoCorporativo.3', compact('cliente', 'formato'));
})->name('presupuestoCorporativo.page3')
->where('cliente', '[0-9]+');

Route::get('/presupuestoCorporativo/{cliente}/4.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuestoCorporativo.4', compact('cliente', 'formato'));
})->name('presupuestoCorporativo.page4')
->where('cliente', '[0-9]+');

Route::get('/presupuestoCorporativo/{cliente}/5.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuestoCorporativo.5', compact('cliente', 'formato'));
})->name('presupuestoCorporativo.page5')
->where('cliente', '[0-9]+');

Route::get('/presupuestoCorporativo/{cliente}/6.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuestoCorporativo.6', compact('cliente', 'formato'));
})->name('presupuestoCorporativo.page6')
->where('cliente', '[0-9]+');

Route::get('/presupuestoCorporativo/{cliente}/7.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuestoCorporativo.7', compact('cliente', 'formato'));
})->name('presupuestoCorporativo.page7')
->where('cliente', '[0-9]+');

Route::get('/presupuestoCorporativo/{cliente}/8.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuestoCorporativo.8', compact('cliente', 'formato'));
})->name('presupuestoCorporativo.page8')
->where('cliente', '[0-9]+');

Route::get('/presupuestoCorporativo/{cliente}/9.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuestoCorporativo.9', compact('cliente', 'formato'));
})->name('presupuestoCorporativo.page9')
->where('cliente', '[0-9]+');

Route::get('/presupuestoCorporativo/{cliente}/10.html', function(Clientes $cliente) {
    $formato = Formatos::find($cliente->formato_evento);
    return view('presupuestoCorporativo.10', compact('cliente', 'formato'));
})->name('presupuestoCorporativo.page10')
->where('cliente', '[0-9]+');

Route::get('/budget/{cliente}/contract', [App\Http\Controllers\BudgetController::class, 'contract'])->name('budget.contract')
->where('cliente', '[0-9]+');

Route::get('/budget/{cliente}/alternative', [App\Http\Controllers\BudgetController::class, 'alternative'])->name('budget.alternative')
->where('cliente', '[0-9]+');
