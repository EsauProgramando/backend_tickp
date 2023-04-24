<?php

namespace App\Http\Controllers\inventary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\register_inventaries as RegisterInventary;

use Dompdf\Dompdf;
use Dompdf\Options;



class Register_inventaries extends Controller
{
    public function index()
    {
        $register_inventaries = RegisterInventary::all();
        return response()->json([
            'success' => true,
            'msg' => 'Listado de registros de inventario',
            'data' =>  $register_inventaries
        ]);
    }


    public function ticketPDFExcel($codigo, $dia, $mes, $year)
    {
        $barcode = new \Com\Tecnick\Barcode\Barcode();
        $infoList = array(
            'Codigo' => $codigo,
            'Fecha' => $dia . "-" . $mes . "-" . $year,
            'Descripcion' => "nombre", //TODO: ENVIAR NOMBRE
        );
        $jsonInfo = json_encode($infoList);

        $Qrhtml =  $this->generadorQr($jsonInfo, $barcode);
        $Brhtml = $this->generadorCodigoBarra($jsonInfo, $barcode);

        // PDF
        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        $dompdf = new Dompdf($options);
        $dompdf->setPaper(array(-1, -1, 108, 72), 'portrait');
        ob_start();

        $html = view('generar_ticket_excel', [
            'codigo' => $codigo,
            'dia' => $dia,
            'mes' => $mes,
            'year' => $year,
            'Qr' =>  $Qrhtml,
            'Br' =>   $Brhtml
        ]);
        $dompdf->loadHtml($html);
        $dompdf->render();

        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=ticket" . $codigo . ".pdf");
        return $dompdf->stream("ticket" . $codigo . ".pdf", array("Attachment" => false));
    }

    protected function generadorCodigoBarra($jsonInfo, $barcode)
    {
        $bobj2 = $barcode->getBarcodeObj(
            "PDF417",             // Tipo de Barcode o Qr
            $jsonInfo,  // Datos
            -3,             // Width
            -4,             // Height
            "black",         // Color del codigo
            array(0, 0, 0, 0)    // Padding
        );
        $Br = $bobj2->getPngData();
        $Brhtml = '<img src="data:image/png;base64,' . base64_encode($Br) . '" alt="barcode"   />';
        return  $Brhtml;
    }
    protected function generadorQr($jsonInfo, $barcode)
    {
        $bobj1 = $barcode->getBarcodeObj(
            'QRCODE,H',
            $jsonInfo,
            -1,
            -1,
            'black',
            array(0, 0, 0, 0)
        );
        $Qr = $bobj1->getPngData();
        $Qrhtml = '<img class="imagen-qr" src="data:image/png;base64,' . base64_encode($Qr) . '" alt="barcode"   />';
        return   $Qrhtml;
    }
}
