<?php
    header("Content-type: application/json; charset=utf-8");
    include(dirname(__FILE__)."/../datos/db.php");
    include(dirname(__FILE__)."/../negocio/comprobantes.class.php");
    $modelo = new comprobantes($database);

    function responseJSON($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    } 

    switch ($_GET["f"]) {
        case 'board': echo $modelo->board($_POST);  break;
        case 'add': echo $modelo->add($_POST);  break;
        case 'update': echo $modelo->update($_POST);  break;
        case 'searchById': echo $modelo->searchById($_POST);  break;
        case 'deleteById': echo $modelo->deleteById($_POST);  break;
    }
?>