<? 
  include "../include/header.php"; 

  if ( !is_numeric($_GET[f_key]) ) $lib->AlertBack( "정상적인 접속이 아닙니다." );  
  
  $file_row = $lib->file_manager_info($_GET[f_key]);
  
  $c_type = $lib->file_content_type( $lib->getExt($file_row[F_VName]));
  
  header("Content-type: $c_type\r\n");

  $filepath = $file_row[F_Folder]."/".$file_row[F_VName];
    
  if (is_file( $filepath )) {
      $fp = fopen( $filepath , "rb");

      while(!feof($fp)) {
          echo fread($fp, 100*1024);
          flush();
      }
      fclose ($fp);
      flush();
  } else {
      alert("해당 파일이나 경로가 존재하지 않습니다.");
  }
?>