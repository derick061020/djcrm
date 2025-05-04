<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
      /*
  * VERIFICACION DEL WEBHOOK
  */
  public function webhook(){
    //TOQUEN QUE QUERRAMOS PONER 
    $token = 'HolaNovato';
    file_put_contents("text.txt", json_encode($_GET));
    //RETO QUE RECIBIREMOS DE FACEBOOK
    $hub_challenge = isset($_GET['hub_challenge']) ? $_GET['hub_challenge'] : '';
    //TOQUEN DE VERIFICACION QUE RECIBIREMOS DE FACEBOOK
    $hub_verify_token = isset($_GET['hub_verify_token']) ? $_GET['hub_verify_token'] : '';
    //SI EL TOKEN QUE GENERAMOS ES EL MISMO QUE NOS ENVIA FACEBOOK RETORNAMOS EL RETO PARA VALIDAR QUE SOMOS NOSOTROS
    if ($token === $hub_verify_token) {
        echo $hub_challenge;
        exit;
    }
  }
  /*
  * RECEPCION DE MENSAJES
  */
  public function recibe(){
    //LEEMOS LOS DATOS ENVIADOS POR WHATSAPP
    $respuesta = file_get_contents("php://input");
    //echo file_put_contents("text.txt", "Hola");
    //SI NO HAY DATOS NOS SALIMOS
    if($respuesta==null){
      exit;
    }
    //CONVERTIMOS EL JSON EN ARRAY DE PHP
    $respuesta = json_decode($respuesta, true);
    //EXTRAEMOS EL TELEFONO DEL ARRAY
    $mensaje="Telefono:".$respuesta['entry'][0]['changes'][0]['value']['messages'][0]['from']."\n";
    //EXTRAEMOS EL MENSAJE DEL ARRAY
    $mensaje.="Mensaje:".$respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
    //GUARDAMOS EL MENSAJE Y LA RESPUESTA EN EL ARCHIVO text.txt
    file_put_contents("text.txt", $mensaje);
  }
}
