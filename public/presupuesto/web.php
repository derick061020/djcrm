<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para el presupuesto
Route::get('/presupuesto/{idCliente}/index.html', function($idCliente) {
    return view('presupuesto.index', compact('idCliente'));
})->name('presupuesto.index');

Route::get('/presupuesto/{idCliente}/1.html', function($idCliente) {
    return view('presupuesto.1', compact('idCliente'));
})->name('presupuesto.page1');

Route::get('/presupuesto/{idCliente}/2.html', function($idCliente) {
    return view('presupuesto.2', compact('idCliente'));
})->name('presupuesto.page2');

Route::get('/presupuesto/{idCliente}/3.html', function($idCliente) {
    return view('presupuesto.3', compact('idCliente'));
})->name('presupuesto.page3');

Route::get('/presupuesto/{idCliente}/4.html', function($idCliente) {
    return view('presupuesto.4', compact('idCliente'));
})->name('presupuesto.page4');

Route::get('/presupuesto/{idCliente}/5.html', function($idCliente) {
    return view('presupuesto.5', compact('idCliente'));
})->name('presupuesto.page5');

Route::get('/presupuesto/{idCliente}/6.html', function($idCliente) {
    return view('presupuesto.6', compact('idCliente'));
})->name('presupuesto.page6');

Route::get('/presupuesto/{idCliente}/7.html', function($idCliente) {
    return view('presupuesto.7', compact('idCliente'));
})->name('presupuesto.page7');

Route::get('/presupuesto/{idCliente}/8.html', function($idCliente) {
    return view('presupuesto.8', compact('idCliente'));
})->name('presupuesto.page8');

Route::get('/presupuesto/{idCliente}/9.html', function($idCliente) {
    return view('presupuesto.9', compact('idCliente'));
})->name('presupuesto.page9');
