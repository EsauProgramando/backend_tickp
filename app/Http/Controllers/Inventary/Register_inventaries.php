<?php

namespace App\Http\Controllers\inventary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\register_inventaries as RegisterInventary;

use Dompdf\Dompdf;
use Dompdf\Options;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



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
  public function PaginadoInvitario(Request $request)
  {

    $query = $request->input('query');
    $perPage = $request->input('perPage', 10);
    $datos = RegisterInventary::where('codigo_patrimonial', 'LIKE', "%$query%")
      ->orWhere('codigo_patrimonial', 'LIKE', "%$query%")
      ->orWhere('denominacion_bien', 'LIKE', "%$query%")
      ->orWhere('desc_area', 'LIKE', "%$query%")
      ->paginate($perPage);
    return response()->json([
      'success' => true,
      'msg' => 'Listado de registros de inventario',
      'data' => $datos
    ]);
  }


  public function bienid($codigo)
  {
    $bien = RegisterInventary::where('codigo_patrimonial', $codigo)->first();
    //validar si el producto existe
    if (!$bien) {
      return  response()->json(["message" => "bien no encontrado"], 404);
    }
    return response()->json($bien);
  }



  public function agregarBines(Request $request)
  {
    $data = $request->all(); // Obtener los datos de la solicitud POST

    // Crear un arreglo para almacenar los códigos patrimoniales repetidos
    $codigosRepetidos = [];
    $bien = new RegisterInventary;
    // Iterar sobre cada objeto en el arreglo de datos
    foreach ($data as $item) {
      $codigoPatrimonial = $item['CODIGO_PATRIMONIAL'];

      // Verificar si el código patrimonial ya existe en la base de datos
      $codigoExistente = RegisterInventary::where('codigo_patrimonial', $codigoPatrimonial)->first();

      if ($codigoExistente) {
        // Si el código patrimonial ya existe, agregarlo al arreglo de códigos repetidos
        $codigosRepetidos[] = $codigoPatrimonial;
      } else {
        // Si el código patrimonial no existe, crear un nuevo registro en la base de datos
        $bien = [
          'codigo_patrimonial' => $item['CODIGO_PATRIMONIAL'],
          'denominacion_bien' => $item['DENOMINACION_BIEN'],
          'nro_doc_adquisicion' => $item['NRO_DOCUMENTO_ADQUIS'],
          'cta_con_seguro' => $item['OPC_ASEGURADO'],
          'fecha_adquisicion' => $item['FECHA_DOCUMENTO_ADQUIS'],
          'valor_adquisicion' => $item['VALOR_ADQUIS'],

          'nro_cuenta_contable' => $item['NRO_CTA_CONTABLE'],
          'estado_bien' => $item['NOM_EST_BIEN'],
          'condicion' => $item['CONDICION'],
          'valor_adquis' => $item['VALOR_ADQUIS'],
          'valor_neto' => $item['VALOR_NETO'],

          'desc_area' => isset($item['DESC_AREA']) ? $item['DESC_AREA'] : null,
          'marca' => isset($item['MARCA']) ? $item['MARCA'] : null,
          'modelo' => isset($item['MODELO']) ? $item['MODELO'] : null,
          'dimension' => isset($item['DIMENSION']) ? $item['DIMENSION'] : null,
          'serie' => isset($item['SERIE']) ? $item['SERIE'] : null,
          'color' => isset($item['COLOR']) ? $item['COLOR'] : null,

        ];
        RegisterInventary::create($bien);
      }
    }

    if (count($codigosRepetidos) > 0) {
      return response()->json([
        'success' => false,
        'msg' => 'Los siguientes códigos patrimoniales ya existen en la base de datos',
        'data' => $codigosRepetidos
      ]);
    }

    return response()->json([
      'success' => true,
      'msg' => 'Registros de inventario agregados correctamente'
    ]);
  }

  public function ticketPDFExcel($codigo, $fecha, $descripcion)
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
  public function imprimirSelect(Request $request)
  {
    $barcode = new \Com\Tecnick\Barcode\Barcode();
    $objeto = request()->json()->all();


    $codigo0 = RegisterInventary::where('codigo_patrimonial', $objeto['item0'])->first();
    $codigo1 = RegisterInventary::where('codigo_patrimonial', $objeto['item1'])->first();
    $codigo2 = RegisterInventary::where('codigo_patrimonial', $objeto['item2'])->first();
    $codigo3 = RegisterInventary::where('codigo_patrimonial', $objeto['item3'])->first();
    $codigo4 = RegisterInventary::where('codigo_patrimonial', $objeto['item4'])->first();

    $options = new Options();

    $options->set('isRemoteEnabled', TRUE);
    $dompdf0 = new Dompdf($options);
    $dompdf1 = new Dompdf($options);
    $dompdf2 = new Dompdf($options);
    $dompdf3 = new Dompdf($options);
    $dompdf4 = new Dompdf($options);



    if ($codigo0 != null) {

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

      $html = view('generar_ticket_excel', [
        'codigo' => $codigo0->codigo_patrimonial,
        'fecha' => $codigo0->fecha_adquisicon,
        'descripcion' => $codigo0->denominacion_bien,
        'fecha' => $codigo0->fecha_adquisicion,
        'Qr' =>  $Qrhtml,
        'Br' =>   $Brhtml
      ]);
      $dompdf0->loadHtml($html);
      $dompdf0->render();
      $pdf_content = $dompdf0->output();


      // Guardar el archivo PDF en la carpeta deseada
      $save_path0 = __DIR__ . '../../pdftemporal/' . $codigo0->codigo_patrimonial . 'file.pdf'; // Ruta de la carpeta y el archivo destino
      file_put_contents($save_path0, $pdf_content);
      $listaArchivosPDF[] = '../../pdftemporal/' . $codigo0->codigo_patrimonial . 'file.pdf';
      $listaArchivosPDF2[] = $save_path0;
    }
    if ($codigo1 != null) {
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
      $html = view('generar_ticket_excel', [
        'codigo' => $codigo1->codigo_patrimonial,
        'fecha' => $codigo1->fecha_adquisicon,
        'descripcion' => $codigo1->denominacion_bien,
        'fecha' => $codigo1->fecha_adquisicion,
        'Qr' =>  $Qrhtml,
        'Br' =>   $Brhtml
      ]);
      $dompdf1->loadHtml($html);
      $dompdf1->render();
      $pdf_content = $dompdf1->output();


      // Guardar el archivo PDF en la carpeta deseada
      $save_path1 = __DIR__ . '../../pdftemporal/' . $codigo1->codigo_patrimonial . 'file.pdf'; // Ruta de la carpeta y el archivo destino
      file_put_contents($save_path1, $pdf_content);
      $listaArchivosPDF[] = '../../pdftemporal/' . $codigo1->codigo_patrimonial . 'file.pdf';
      $listaArchivosPDF2[] = $save_path1;
    }
    if ($codigo2 != null) {
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

      $html = view('generar_ticket_excel', [
        'codigo' => $codigo2->codigo_patrimonial,
        'fecha' => $codigo2->fecha_adquisicon,
        'descripcion' => $codigo2->denominacion_bien,
        'fecha' => $codigo2->fecha_adquisicion,
        'Qr' =>  $Qrhtml,
        'Br' =>   $Brhtml
      ]);
      $dompdf2->loadHtml($html);
      $dompdf2->render();
      $pdf_content = $dompdf2->output();


      // Guardar el archivo PDF en la carpeta deseada
      $save_path2 = __DIR__ . '../../pdftemporal/' . $codigo2->codigo_patrimonial . 'file.pdf'; // Ruta de la carpeta y el archivo destino
      file_put_contents($save_path2, $pdf_content);
      $listaArchivosPDF[] = '../../pdftemporal/' . $codigo2->codigo_patrimonial . 'file.pdf';
      $listaArchivosPDF2[] = $save_path2;
    }

    if ($codigo3 != null) {
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

      $html = view('generar_ticket_excel', [
        'codigo' => $codigo3->codigo_patrimonial,
        'fecha' => $codigo3->fecha_adquisicon,
        'descripcion' => $codigo3->denominacion_bien,
        'fecha' => $codigo3->fecha_adquisicion,
        'Qr' =>  $Qrhtml,
        'Br' =>   $Brhtml
      ]);
      $dompdf3->loadHtml($html);
      $dompdf3->render();
      $pdf_content = $dompdf3->output();


      // Guardar el archivo PDF en la carpeta deseada
      $save_path3 = __DIR__ . '../../pdftemporal/' . $codigo3->codigo_patrimonial . 'file.pdf'; // Ruta de la carpeta y el archivo destino
      file_put_contents($save_path3, $pdf_content);
      $listaArchivosPDF[] = '../../pdftemporal/' . $codigo3->codigo_patrimonial . 'file.pdf';
      $listaArchivosPDF2[] = $save_path3;
    }

    if ($codigo4 != null) {
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

      $html = view('generar_ticket_excel', [
        'codigo' => $codigo4->codigo_patrimonial,
        'fecha' => $codigo4->fecha_adquisicon,
        'descripcion' => $codigo4->denominacion_bien,
        'fecha' => $codigo4->fecha_adquisicion,
        'Qr' =>  $Qrhtml,
        'Br' =>   $Brhtml
      ]);
      $dompdf4->loadHtml($html);
      $dompdf4->render();
      $pdf_content = $dompdf4->output();


      // Guardar el archivo PDF en la carpeta deseada
      $save_path4 = __DIR__ . '../../pdftemporal/' . $codigo4->codigo_patrimonial . 'file.pdf'; // Ruta de la carpeta y el archivo destino
      file_put_contents($save_path4, $pdf_content);
      $listaArchivosPDF[] = '../../pdftemporal/' . $codigo4->codigo_patrimonial . 'file.pdf';
      $listaArchivosPDF2[] = $save_path4;
    }

    /*===combinar===*/
    $combinador = new Merger;
    //$pdf = new FPDF();
    //echo (implode(", ", $listaArchivosPDF));
    //echo $listaArchivosPDF2;
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
    $data = RegisterInventary::from('cond_bien')->select('cant_regular', 'cant_malo', 'cant_bueno', 'cant_nuevo')->get();
    return response()->json($data);
  }

  public function vista_condicion()
  {
    $data = RegisterInventary::from('condiciones')->select('cond_activo', 'cond_baja')->get();
    return response()->json($data);
  }

  /*============ACTUALIZAR BIEN====================*/
  public function update_inventario(Request $request, $codigo)
  {


    $objeto = request()->json()->all();
    $bien = RegisterInventary::where('codigo_patrimonial', $codigo)->first();
    if (!($bien)) {
      return response()->json(['success' => false, 'msg' => 'Inventario no encontrado']);
    }

    $bien->estado_bien = $objeto['estado_bien'];
    $bien->condicion = $objeto['condicion'];
    $bien->fecha_inventario = $objeto['fecha_inventario'];
    $bien->desc_area = $objeto['desc_area'];
    $bien->save();
    return response()->json(['message' => 'Registro actualizado correctamente', $bien]);
  }
  /*=====CARGAR AREA========*/
  public function vista_area()
  {
    $data = DB::table('cantidad_bienes_area')->get();
    return response()->json($data);
  }

  /*=======CARGAR DATA===========*/
  public function generarExcel()
  {
    // Obtener los datos de la base de datos
    $datos = DB::table('register_inventaries')->select(
      'ruc_entidad',
      'codigo_patrimonial',
      'denominacion_bien',
      'actos_de_adquisicion_que_genera_alta',
      'nro_doc_adquisicion',
      'fecha_adquisicion',
      'valor_adquisicion',
      'tipo_uso_cuenta',
      'tipo_cuenta',
      'nro_cuenta_contable',
      'cta_con_seguro',
      'estado_bien',
      'condicion',
      'valor_adquis',
      'valor_neto',
      'desc_area',
      'marca',
      'modelo',
      'dimension',
      'serie',
      'color'
    )->get();

    // Obtener la ruta absoluta del directorio actual
    $directorioActual = __DIR__;

    // Concatenar el nombre del archivo Excel al final de la ruta del directorio
    $rutaExcel = $directorioActual . '/Formato_Carga_Masiva_Altas_v1.xlsm';

    // Cargar el archivo Excel existente
    $spreadsheet = IOFactory::load($rutaExcel);

    // Obtener la hoja de trabajo activa
    $hoja = $spreadsheet->getActiveSheet();
    $fila = 6;
    foreach ($datos as $dato) {
      $hoja->getCell('B' . $fila)->setValue($dato->ruc_entidad);
      $hoja->getCell('C' . $fila)->setValue($dato->codigo_patrimonial);
      $hoja->getCell('D' . $fila)->setValue($dato->denominacion_bien);
      $hoja->getCell('E' . $fila)->setValue($dato->actos_de_adquisicion_que_genera_alta);
      $hoja->getCell('F' . $fila)->setValue($dato->nro_doc_adquisicion);
      $hoja->getCell('G' . $fila)->setValue($dato->fecha_adquisicion);
      $hoja->getCell('H' . $fila)->setValue($dato->valor_adquisicion);
      $hoja->getCell('I' . $fila)->setValue($dato->tipo_uso_cuenta);
      $hoja->getCell('J' . $fila)->setValue($dato->tipo_cuenta);
      $hoja->getCell('K' . $fila)->setValue($dato->nro_cuenta_contable);
      $hoja->getCell('L' . $fila)->setValue($dato->cta_con_seguro);
      $hoja->getCell('M' . $fila)->setValue($dato->estado_bien);
      $hoja->getCell('N' . $fila)->setValue($dato->condicion);
      $hoja->getCell('O' . $fila)->setValue($dato->marca);
      $hoja->getCell('P' . $fila)->setValue($dato->modelo);
      $hoja->getCell('Q' . $fila)->setValue("-");
      $hoja->getCell('R' . $fila)->setValue($dato->color);
      $hoja->getCell('S' . $fila)->setValue($dato->serie);
      $hoja->getCell('T' . $fila)->setValue($dato->dimension);

      // Incrementar la variable de la fila para pasar a la siguiente fila
      $fila++;
    }

    // Guardar el archivo Excel modificado en formato XLSX
    $rutaExcelModificado = __DIR__ . '/excel_modificado';
    $writer = new Xlsx($spreadsheet);
    $writer->save($rutaExcelModificado);
    //rename($rutaExcelModificado, $rutaExcelModificado . '.xlsm');
    // Cambiar la extensión del archivo de XLSX a XLSM
    //    $rutaExcelModificadoXlsm = $directorioActual .'/excel_modificado.xlsm';
    rename($rutaExcelModificado, $rutaExcelModificado . '.xlsm');
    $rutaExcelModificado = __DIR__ . '/excel_modificado.xlsm';
    // Descargar el archivo Excel modificado


    // $headers = ['Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12'];

    // return response()->download($rutaExcelModificado, 'excel_modificado.xlsm', $headers);
    return response()->file($rutaExcelModificado, [
      'Content-Type' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
      'Content-Disposition' => 'attachment; filename=excel_modificado.xlsm',
    ])->deleteFileAfterSend(true);
  }
}
