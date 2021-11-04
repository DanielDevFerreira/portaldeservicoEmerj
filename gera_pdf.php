<?php

require __DIR__.'/vendor/autoload.php';

use Dompdf\Dompdf;

//ISNTANCIA DE DOMPDF
$dompdf = new Dompdf();

$dompdf->loadHtml('<b>Olá Mundo</b>');

$dompdf->render();
header('Content-type: application/pdf');
echo $dompdf->output();
