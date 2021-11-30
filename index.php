<?php
require 'flight/Flight.php';

Flight::register('db', 'Database', array('rest'));
$json_podaci = file_get_contents("php://input");
Flight::set('json_podaci', $json_podaci);

Flight::route('/hello', function(){
    echo 'hello world!';
});

Flight::route('GET /novosti', function ()
{
    header("Content-Type: application/json; character=utf-8");
    $db = Flight::db();
    $db->select();
    while($red=$db->getResult()->fetch_object()){
        $niz[]=$red;
    }
    $json_niz = json_encode($niz, JSON_UNESCAPED_UNICODE);
    echo $json_niz;
    return false;
});

Flight::route('GET /novosti/@id', function ()
{
    # code...
});

Flight::route('GET /kategorije', function ()
{
    header("Content-Type: application/json; character=utf-8");
    $db = Flight::db();
    $db->select('kategorija', 'id, kategorija');
    while($red=$db->getResult()->fetch_object()){
        $niz[]=$red;
    }
    $json_niz = json_encode($niz, JSON_UNESCAPED_UNICODE);
    echo $json_niz;
    return false;
});

Flight::route('GET /kategorije/@id', function ()
{
    # code...
});

Flight::route('POST /novosti', function ()
{
    header("Content-Type: application/json; character=utf-8");
    $db = Flight::db();
    $podaci = json_decode(Flight::get('json_podaci'));

    if($podaci==null){
        $odgovor["poruka"] = "Niste prosledili podatke";
        $json_odgovor = json_encode($odgovor);
        echo $json_odgovor;
        return $json_odgovor;
    }else{
        if(!property_exists($podaci, 'naslov') || !property_exists($podaci, 'tekst') || !property_exists($podaci, 'kategorija_id')){
            $odgovor["poruka"] = "Niste prosledili korektne podatke";
            $json_odgovor = json_encode($odgovor, JSON_UNESCAPED_UNICODE);
            echo $json_odgovor;
        }else{
            $podaci_q = array();
            foreach($podaci as $k=>$v){
                $v = "'".$v."'";
                $podaci_q[$k] = $v;
            }
            //ZASTO OVA LINIJA SLOMI PROGRAM!?!! 
            //$niz_za_bazu = array($podaci_q["naslov"], $podaci_q["tekst"], $podaci_q["kategorija"], 'NOW()');
            if ($db->insert("novosti", "naslov, tekst, kategorija_id, datumvreme", array($podaci_q["naslov"], $podaci_q["tekst"], $podaci_q["kategorija_id"], 'NOW()'))){
                $odgovor["poruka"] = "Novost uspesno ubacena";
                $json_odgovor = json_encode($odgovor, JSON_UNESCAPED_UNICODE);
                echo $json_odgovor;
            }else{
                $odgovor["poruka"] = "Doslo do greske prilikom ubacivanja novosti";
                $json_odgovor = json_encode($odgovor, JSON_UNESCAPED_UNICODE);
                echo $json_odgovor;
            }
        }
    }
});

Flight::route('POST /kategorije', function ()
{
    # code...
});

Flight::route('PUT /novosti/@id', function ()
{
    # code...
});

Flight::route('PUT /kategorije@/id', function ()
{
    # code...
});

Flight::route('DELETE /novosti/@id', function ()
{
    # code...
});

Flight::route('DELETE /kategorije/@id', function ()
{
    # code...
});

Flight::start();
