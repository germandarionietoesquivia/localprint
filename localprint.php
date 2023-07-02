<html>
  <body> <?php
    try {
      $website_url = 'http://strongboxcol.000webhostapp.com'; # url del sitio web
      $folder = 'print'; # carpeta donde se encuentran los archivos que desea imprimir
      if (!isset($_GET['filetoprint']) || strlen($_GET['filetoprint']) == 0 || strpos($_GET['filetoprint'], ' ') > 0) {
        die('<h1 style="color:#8933FF";>Error en nombre de archivo a imprimir: filetoprint</h1>');
      } else {
        $url = $website_url.'/'.$folder.'/'.$_GET['filetoprint'];
        $file_headers = @get_headers($url);
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
          die('<h1 style="color:#FF33FF";>Error obteniendo datos para impresion...</h1>');
        }
        echo '<h1 style="color:#FF335A";>Imprimiendo, espere...</h1><br>';
        $file = fopen ($url, "rb");
        if ($file) {
          $newfilename = 'fileprint.pdf';
          if (file_exists($newfilename)) {
            $deletefile = unlink($newfilename);
            if (!$deletefile || file_exists($newfilename)) {
              die('<h1 style="color:#FF33FF";>Error creando archivo de impresion...</h1>');
            }
          }
          $newfile = fopen ($newfilename, "wb");
          if ($newfile) {
            while(!feof($file)) {
              fwrite($newfile, fread($file, 1024 * 8 ), 1024 * 8 );
            }
          }
        }
        if ($file) {
          fclose($file);
        }
        if ($newfile) {
          fclose($newfile);
        }
        $number_copies = (isset($_GET['number_copies']) && $_GET['number_copies'] >= 1 && $_GET['number_copies'] <= 5) ? $_GET['number_copies'] : 1;
        for ($c = 1; $c <= $number_copies; $c++) {
          shell_exec('"\SumatraPDF\SumatraPDF.exe" -print-to-default -silent "fileprint.pdf"');
        }
        echo '<h1 style="color:#3347FF";>Impresion Exitosa...</h1>';
      } 
    } catch (Exception $e) {  
      die ('<h1 style="color:#FF33FF";>Error: '.$e->getMessage().'</h1>');
    } ?>
    <script>window.close();</script>
  </body>
</html>
