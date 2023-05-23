<?php

namespace App\Http\Controllers\inventary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\register_inventaries as RegisterInventary;

use Dompdf\Dompdf;
use Dompdf\Options;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\Response;




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

    public function bienid($codigo) {
        $bien = RegisterInventary::where('codigo_patrimonial', $codigo)->first();
        //validar si el producto existe
        if (!$bien) {
               return  response()->json(["message" => "bien no encontrado"], 404);
         }
        return response()->json($bien);
    }
    public function agregarBines(
        Request $request
    ) {
        $bien = new
            RegisterInventary;

        $codigo
            = RegisterInventary::where('codigo_patrimonial', $request->CODIGO_PATRIMONIAL)->first();
        if (!$codigo) {
            $bien->codigo_patrimonial = $request->CODIGO_PATRIMONIAL;
            $bien->denominacion_bien = $request->DENOMINACION_BIEN;
            $bien->nro_doc_adquisicion = $request->NRO_DOCUMENTO_ADQUIS;
            $bien->cta_con_seguro = $request->OPC_ASEGURADO;
            $bien->fecha_adquisicion = $request->FECHA_DOCUMENTO_ADQUIS;
            $bien->valor_adquisicion = $request->VALOR_ADQUIS;
            $bien->tipo_cuenta = $request->TIPO_CUENTA;
            $bien->nro_cuenta_contable = $request->NRO_CTA_CONTABLE;
            $bien->estado_bien = $request->NOM_EST_BIEN;
            $bien->condicion = $request->CONDICION;
            $bien->save();
            return response()->json([
                'success' => true,
                'msg' => 'Registro de inventario agregado correctamente',
                'data' => $bien
            ]);
        }
        return response()->json([
            'success' => false,
            'msg' => 'El codigo patrimonial ya existe',
            'data' => $codigo
        ]);
        // mostrar errores

    }

    public function ticketPDFExcel($codigo, $fecha,$descripcion)
    {
        
        $barcode = new \Com\Tecnick\Barcode\Barcode();
        $infoList = array(
            'Codigo' => $codigo,
            'Fecha' => $fecha,
            'Descripcion' => $descripcion,
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
            'fecha' => $fecha,
            'descripcion' => $descripcion,
            'Qr' =>  $Qrhtml,
            'Br' =>   $Brhtml
        ]);
        $dompdf->loadHtml($html);
        $dompdf->render();

        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=ticket" . $codigo . ".pdf");
        return $dompdf->stream("ticket" . $codigo . ".pdf", array("Attachment" => false));
    }

    protected function generadorCodigoBarra($codigo, $barcode)
    {
        $bobj2 = $barcode->getBarcodeObj(
            "C128",             // Tipo de Barcode o Qr
            $codigo,  // Datos
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

    /*====Impresión Select===*/
    public function imprimirSelect(Request $request){
        $barcode = new \Com\Tecnick\Barcode\Barcode();
        $objeto= request()->json()->all();

        
        $codigo0 = RegisterInventary::where('codigo_patrimonial', $objeto['item0'])->first();
        $codigo1 = RegisterInventary::where('codigo_patrimonial', $objeto['item1'])->first();
        $codigo2 = RegisterInventary::where('codigo_patrimonial', $objeto['item2'])->first();
        $codigo3 = RegisterInventary::where('codigo_patrimonial', $objeto['item3'])->first();
        $codigo4 = RegisterInventary::where('codigo_patrimonial', $objeto['item4'])->first();

        $options = new Options();
        
        $options->set('isRemoteEnabled',TRUE);
        $dompdf0 = new Dompdf($options);
        $dompdf1 = new Dompdf($options);
        $dompdf2 = new Dompdf($options);
        $dompdf3 = new Dompdf($options);
        $dompdf4 = new Dompdf($options);
        
        

        if($codigo0!=null){
           
            //$dompdf0->setPaper('10.5cm', '2.54cm', 'landscape');
            $dompdf0->setPaper(array(-1, -1, 108, 72), 'portrait');
            ob_start();
            $infoList = array(
                'Codigo' => $codigo0->codigo_patrimonial,
                'Fecha' => $codigo0->fecha_adquisicion,
                'Descripcion' => $codigo0->denominacion_bien
            );
            $jsonInfo = json_encode($infoList);
    
            $Qrhtml =  $this->generadorQr($jsonInfo, $barcode);
            $Brhtml = $this->generadorCodigoBarra($codigo0->codigo_patrimonial, $barcode);
            
            $html = view('generar_ticket_excel',['codigo'=>$codigo0->codigo_patrimonial,
                                         'fecha'=>$codigo0->fecha_adquisicon,
                                         'descripcion'=>$codigo0->denominacion_bien,
                                         'fecha'=>$codigo0->fecha_adquisicion,
                                         'Qr' =>  $Qrhtml,
                                         'Br' =>   $Brhtml]);
            $dompdf0->loadHtml($html);
            $dompdf0->render();
            $pdf_content = $dompdf0->output();
    
    
            // Guardar el archivo PDF en la carpeta deseada
            $save_path0 = __DIR__ . '../../pdftemporal/'.$codigo0->codigo_patrimonial.'file.pdf'; // Ruta de la carpeta y el archivo destino
            file_put_contents($save_path0, $pdf_content);
            $listaArchivosPDF[]='../../pdftemporal/'.$codigo0->codigo_patrimonial.'file.pdf';
            $listaArchivosPDF2[]= $save_path0;
    
        }
        if($codigo1!=null){
            //$dompdf1->setPaper('10.5cm', '2.54cm', 'landscape');
            $dompdf1->setPaper(array(-1, -1, 108, 72), 'portrait');
            ob_start();
            $infoList = array(
                'Codigo' => $codigo1->codigo_patrimonial,
                'Fecha' => $codigo1->fecha_adquisicion,
                'Descripcion' => $codigo1->denominacion_bien
            );
            $jsonInfo = json_encode($infoList);
    
            $Qrhtml =  $this->generadorQr($jsonInfo, $barcode);
            $Brhtml = $this->generadorCodigoBarra($codigo1->codigo_patrimonial, $barcode);
            $html = view('generar_ticket_excel',['codigo'=>$codigo1->codigo_patrimonial,
                                         'fecha'=>$codigo1->fecha_adquisicon,
                                         'descripcion'=>$codigo1->denominacion_bien,
                                         'fecha'=>$codigo1->fecha_adquisicion,
                                         'Qr' =>  $Qrhtml,
                                         'Br' =>   $Brhtml]);
            $dompdf1->loadHtml($html);
            $dompdf1->render();
            $pdf_content = $dompdf1->output();
    
    
            // Guardar el archivo PDF en la carpeta deseada
            $save_path1 = __DIR__ . '../../pdftemporal/'.$codigo1->codigo_patrimonial.'file.pdf'; // Ruta de la carpeta y el archivo destino
            file_put_contents($save_path1, $pdf_content);
            $listaArchivosPDF[]='../../pdftemporal/'.$codigo1->codigo_patrimonial.'file.pdf';
            $listaArchivosPDF2[]= $save_path1;
    
        }
        if($codigo2!=null){
            //$dompdf2->setPaper('10.5cm', '2.54cm', 'landscape');
            $dompdf2->setPaper(array(-1, -1, 108, 72), 'portrait');
            ob_start();
            $infoList = array(
                'Codigo' => $codigo2->codigo_patrimonial,
                'Fecha' => $codigo2->fecha_adquisicion,
                'Descripcion' => $codigo2->denominacion_bien
                
            );
            $jsonInfo = json_encode($infoList);
    
            $Qrhtml =  $this->generadorQr($jsonInfo, $barcode);
            $Brhtml = $this->generadorCodigoBarra($codigo2->codigo_patrimonial, $barcode);
        
            $html = view('generar_ticket_excel',['codigo'=>$codigo2->codigo_patrimonial,
                                         'fecha'=>$codigo2->fecha_adquisicon,
                                         'descripcion'=>$codigo2->denominacion_bien,
                                         'fecha'=>$codigo2->fecha_adquisicion,
                                         'Qr' =>  $Qrhtml,
                                         'Br' =>   $Brhtml]);
            $dompdf2->loadHtml($html);
            $dompdf2->render();
            $pdf_content = $dompdf2->output();
    
    
            // Guardar el archivo PDF en la carpeta deseada
            $save_path2 = __DIR__ . '../../pdftemporal/'.$codigo2->codigo_patrimonial.'file.pdf'; // Ruta de la carpeta y el archivo destino
            file_put_contents($save_path2, $pdf_content);
            $listaArchivosPDF[]='../../pdftemporal/'.$codigo2->codigo_patrimonial.'file.pdf';
            $listaArchivosPDF2[]= $save_path2;
    
        }
        
        if($codigo3!=null){
            //$dompdf3->setPaper('10.5cm', '2.54cm', 'landscape');
            $dompdf3->setPaper(array(-1, -1, 108, 72), 'portrait');
            ob_start();
            $infoList = array(
                'Codigo' => $codigo3->codigo_patrimonial,
                'Fecha' => $codigo3->fecha_adquisicion,
                'Descripcion' => $codigo3->denominacion_bien
                
            );
            $jsonInfo = json_encode($infoList);
    
            $Qrhtml =  $this->generadorQr($jsonInfo, $barcode);
            $Brhtml = $this->generadorCodigoBarra($codigo3->codigo_patrimonial, $barcode);
        
            $html = view('generar_ticket_excel',['codigo'=>$codigo3->codigo_patrimonial,
                                         'fecha'=>$codigo3->fecha_adquisicon,
                                         'descripcion'=>$codigo3->denominacion_bien,
                                         'fecha'=>$codigo3->fecha_adquisicion,
                                         'Qr' =>  $Qrhtml,
                                         'Br' =>   $Brhtml]);
            $dompdf3->loadHtml($html);
            $dompdf3->render();
            $pdf_content = $dompdf3->output();
    
    
            // Guardar el archivo PDF en la carpeta deseada
            $save_path3= __DIR__ . '../../pdftemporal/'.$codigo3->codigo_patrimonial.'file.pdf'; // Ruta de la carpeta y el archivo destino
            file_put_contents($save_path3, $pdf_content);
            $listaArchivosPDF[]='../../pdftemporal/'.$codigo3->codigo_patrimonial.'file.pdf';
            $listaArchivosPDF2[]= $save_path3;
    
        }
        
        if($codigo4!=null){
            //$dompdf4->setPaper('10.5cm', '2.54cm', 'landscape');
            $dompdf4->setPaper(array(-1, -1, 108, 72), 'portrait');
            ob_start();
            $infoList = array(
                'Codigo' => $codigo4->codigo_patrimonial,
                'Fecha' => $codigo4->fecha_adquisicion,
                'Descripcion' => $codigo4->denominacion_bien
                
            );
            $jsonInfo = json_encode($infoList);
    
            $Qrhtml =  $this->generadorQr($jsonInfo, $barcode);
            $Brhtml = $this->generadorCodigoBarra($codigo4->codigo_patrimonial, $barcode);
        
            $html = view('generar_ticket_excel',['codigo'=>$codigo4->codigo_patrimonial,
                                         'fecha'=>$codigo4->fecha_adquisicon,
                                         'descripcion'=>$codigo4->denominacion_bien,
                                         'fecha'=>$codigo4->fecha_adquisicion,
                                         'Qr' =>  $Qrhtml,
                                         'Br' =>   $Brhtml
                                        ]);
            $dompdf4->loadHtml($html);
            $dompdf4->render();
            $pdf_content = $dompdf4->output();
    
    
            // Guardar el archivo PDF en la carpeta deseada
            $save_path4 = __DIR__ . '../../pdftemporal/'.$codigo4->codigo_patrimonial.'file.pdf'; // Ruta de la carpeta y el archivo destino
            file_put_contents($save_path4, $pdf_content);
            $listaArchivosPDF[]='../../pdftemporal/'.$codigo4->codigo_patrimonial.'file.pdf';
            $listaArchivosPDF2[]= $save_path4;
    
        }
        
        /*===combinar===*/
        $combinador = new Merger;
        //$pdf = new FPDF();
        //echo (implode(", ", $listaArchivosPDF));
        foreach ($listaArchivosPDF2 as $archivoPDF) {
            $combinador->addFile($archivoPDF);
            
            
        }
        $salida = $combinador->merge();
        foreach ($listaArchivosPDF2 as $archivoPDF) {
            
            unlink($archivoPDF);
            
        }
        $nombreArchivo = "combinado.pdf";
        header("Content-type:application/pdf");
        header("Content-disposition: inline; filename=$nombreArchivo");
        // header("content-Transfer-Encoding:binary");
        // header("Accept-Ranges:bytes");
        # Imprimir salida luego de encabezados
        echo $salida;

        }

    /*===========CONDICIONES y ESTADO BIEN===========*/
    public function vista_estado()
    {
        $data = RegisterInventary::from('cond_bien')->select('cant_regular', 'cant_malo', 'cant_bueno','cant_nuevo')->get();
        return response()->json($data);
    }

    public function vista_condicion()
    {
        $data = RegisterInventary::from('condiciones')->select('cond_activo', 'cond_baja')->get();
        return response()->json($data);
    }

    /*============ACTUALIZAR BIEN====================*/
    public function update_inventario(Request $request, $codigo){
        
            
            $objeto= request()->json()->all();
            $bien = RegisterInventary::where('codigo_patrimonial', $codigo)->first();
            if (!($bien)) {
                return response()->json(['success' => false, 'msg' => 'Inventario no encontrado']);
            }
            
            $bien->estado_bien=$objeto['estado_bien'];
            $bien->condicion=$objeto['condicion'];  
            $bien->fecha_inventario=$objeto['fecha_inventario']; 
            $bien->save();         
            return response()->json(['message' => 'Registro actualizado correctamente',$bien]);
    
            
    }
}