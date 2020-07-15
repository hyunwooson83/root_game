<?php
  CLASS MCLEN_LIB
  {
    var $used = null;
    var $db = null;

    function MCLEN_LIB( &$db = "" ) {
      if( $db ) $this->db = $db;
    }

    // 디버그 함수
      function debugVars($type)
      {
        global $HTTP_SESSION_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS;

        switch(strtolower($type))
        {
          case "session":
            $tmp=$HTTP_SESSION_VARS;
            break;
          case "post":
            $tmp=$HTTP_POST_VARS;
            break;
          case "get":
            $tmp=$HTTP_GET_VARS;
            break;
        };

        while(list($id,$value)=each($tmp))
        {
          echo "$id : $value <br>";
          if(is_array($value))
          {
            while(list($id2,$value2)=each($value))
            {
              echo "&nbsp;- ${id}[".$id2."] : $value2 <br>";
            };
          };
        };
      }

    // 입력된 파일의 사이즈를 체크하여 반환하는 함수
      function Get_File_Size($files)
      {
         $size = filesize($files);
         $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
         return @round($size/pow(1024, ($i = floor(log($size, 1024)))), 1) . $filesizename[$i];
      }

    // 문자열 자르는 함수!
      function Str_Cut($str, $len, $suffix)
      {
        // 저장할 문자열의 길이가 저장 한계치보다 작을경우 그대로 출력.
          /*if (strlen($text) <= $length) return $text;

        // 저장할 문자열의 길이가 저장 한계치보다 많을 경우.

        // cpos 라는 변수에 한계 길이보다 1 작은 값을 넣어준다.
          $cpos = $length - 1;
          $count_2B = 0;
        // 저장할 문자열에서 마지막 문자를 읽어 온다.
          $lastchar = $text[$cpos];

        // 마지막 문자의 아스키 값이 127보다 크고 cpos 길이가 0보다 클때 까지 루프를 돈다.
        // 즉, 저장할 마지막 문자가 한글일 경우 완전한 한글자가 될때 까지 루프 돈다.
          while (ord($lastchar)>127 && $cpos>=0)
          {
            $count_2B++;
            $cpos--;
            $lastchar = $text[$cpos];
          }

        // 위의 저장된 정보를 바탕으로 입력된 문자열을 자른다.
          return substr($text, 0, (($count_2B % 2) ? $length-1 : $length)).$suffix;*/
      	
      	//UTF-8 용으로 수정
	    $s = substr($str, 0, $len);
	    $cnt = 0;
	    for ($i=0; $i<strlen($s); $i++)
	        if (ord($s[$i]) > 127)
	            $cnt++;
        $s = substr($s, 0, $len - ($cnt % 3));

        if (strlen($s) >= strlen($str))
	        $suffix = "";
	    return $s . $suffix;
      	
      }
      
     // Html 코드 변환.
      function Conv_Html($string, $type = "Del")
      {
        if($type == "Del")
        {
          $string = str_replace( "&", "&amp;", $string);
          $string = str_replace( ">", "&gt;", $string);
          $string = str_replace( "<", "&lt;", $string);
          $string = str_replace( "'", "&#039;", $string);
          $string = str_replace( "\"", "&quot;", $string);
        }
         else
        {
          $string = str_replace( "&gt;" , ">" , $string);
          $string = str_replace( "&lt;" , "<" , $string);
          $string = str_replace( "&amp;" , "&" , $string);
          $string = str_replace( "&#039;" , "'" , $string);
          $string = str_replace( "&quot;" , "\"" , $string);
        };
        return $string;
      }


      function Get_MIME_Type($ext)
      {
        $ext = strtolower($ext);

        $G_MIMES = parse_ini_file ("./inc/mime.ini");

        if($G_MIMES[$ext])
          return $G_MIMES[$ext];
        else
          return "text/plain";
      }

     // Html 코드 변환.
      function Conv_Header_Blank($string)
      {
        $string = str_replace( "\n", "<br>", $string);
        $string = str_replace( "\t", "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp", $string);
        $string = str_replace( " ", "&nbsp;", $string);

        return $string;
      }

    // Alert 후 페이지 이동!
    function AlertMSGSwal($String , $Url = "", $Delay = "0", $location = "0")
    {
      echo '<link rel="stylesheet" href="/css/sweetalert.css">';
      echo '<script src="/js/sweetalert.js"></script>';
      echo "<script>\n\r";
      echo "  swal('','{$String}','warning');\n\r";
      echo "</script>\n\r";
      if ( $Url ) {
        if($location == "0") echo "<meta http-equiv='refresh' content='$Delay; url=$Url'>";
        else echo "<script>".$location.".document.location.href='".$Url."';</script>";
      };
      exit;
    }


    // Alert 후 페이지 이동!
      function AlertMSG($String , $Url = "", $Delay = "0", $location = "0")
      {
        echo "<script>\n\r";
        //echo "  swal('','{$String}','warning');\n\r";
        echo "  alert('{$String}');\n\r";
        echo "</script>\n\r";
        if ( $Url ) {
          if($location == "0") echo "<meta http-equiv='refresh' content='$Delay; url=$Url'>";
          else echo "<script>".$location.".document.location.href='".$Url."';</script>";
        };
        exit;
      }

    // Alert 후 페이지 이동!
      function AlertBack($String )
      {
        echo "<script>\n\r";
        echo "  alert('$String');\n\r";
        echo "  history.go(-1);\n\r";
        echo "</script>\n\r";
        exit;
      }

      function AlertMSGClose($String, $location = "0" )
      {
        echo "<script>\n\r";
        echo "  alert('$String');\n\r";
        if ( $location == "0" ) echo "  window.close();";
        else echo $location.".window.close();";
        echo "</script>\n\r";
        exit;
      }

    // 페이지 이동 하는 함수!
      function MovePage($Url, $Delay = "0", $location = "0")
      {
        if($location == "0")
        {
          echo "<meta http-equiv='refresh' content='$Delay; url=$Url'>";
        }
        else
        {
          echo "<script>".$location.".document.location.href='".$Url."';</script>";
        };

        exit;
      }


    // 페이지 리로드
      function ReloadPage( $location = "0" )
      {
        if($location != "0") echo "<script>".$location.".document.location.reload();</script>";
        else echo "<script>document.location.reload();</script>";
        exit;
      }

    // 이전 페이지 파일 가져오는 함수!
      function LastPageName()
      {
        $Last_Url = explode("/",$_SERVER["HTTP_REFERER"]);
        $Last_Url_Num = count($Last_Url);
        $Last_Url = explode("?",$Last_Url[$Last_Url_Num - 1]);
        $Last_Url = $Last_Url[0];
        return $Last_Url;
      }

    // 현재 페이지 파일명 가져오는 함수!
      function ThisPageName()
      {
        $This_Url = explode("/",$_SERVER['SCRIPT_NAME']);
        $This_Url_Num = count($This_Url);
        $This_Url = $This_Url[$This_Url_Num - 1];

        return $This_Url;
      }

    // 현재 페이지 폴더 가져오는 함수!
      function ThisFolderName()
      {
        $This_Url = explode("/",$_SERVER['SCRIPT_NAME']);
        $This_Url_Num = count($This_Url);
        $This_Url = $This_Url[$This_Url_Num - 2];

        return $This_Url;
      }

    function getExt($value)
    {
      return strtolower(array_pop(explode('.',$value)));
    }

    function filter_urldecode() {
      global $_POST, $_GET;
      foreach( $_POST as $key => $val ) {
        $_POST[$key] = urldecode($val);
      };

      foreach( $_GET as $key => $val ) {
        $_GET[$key] = urldecode($val);
      };

    }

    function make_vars( $delvar = "" ) {
      global $HTTP_POST_VARS, $HTTP_GET_VARS;
      if(!isset($HTTP_POST_VARS))
      	$HTTP_POST_VARS = $_POST;
      if(!isset($HTTP_GET_VARS))
      	$HTTP_GET_VARS = $_GET;
      $prefix = "";
      $delvar = explode("|", $delvar);
      foreach( $HTTP_POST_VARS as $key => $val ) {
        if( $val && !in_array($key, $delvar) ) {
          $prefix .= "&$key=".urlencode($val);
        };
      };

      foreach( $HTTP_GET_VARS as $key => $val ) {
        if( $val && !in_array($key, $delvar ) ){
          $prefix .= "&$key=".urlencode($val);
        };
      };

      return $prefix;
    }

    function columnSort($unsorted, $column) {
        $sorted = $unsorted;
        for ($i=0; $i < sizeof($sorted)-1; $i++) {
          for ($j=0; $j<sizeof($sorted)-1-$i; $j++)
            if ($sorted[$j][$column] > $sorted[$j+1][$column]) {
              $tmp = $sorted[$j];
              $sorted[$j] = $sorted[$j+1];
              $sorted[$j+1] = $tmp;
          }
        }
        return $sorted;
    }



      // 페이지 네비게이션 함수, 함수호출후 pagenum.inc 파일 인클루드시켜야 한다.
      // arg : 한페이지에 보여줄 게시물 갯수, 페이지넘버 갯수, $offset, 전체게시물을 구할 쿼리문, 질의변수, 값, 질의변수, 값...
      function checkPageNum($input_limit, $input_num, $input_offset, $input_query, $input_var1=0, $input_val1=0, $input_var2=0, $input_val2=0, $input_var3=0, $input_val3=0, $input_var4=0, $input_val4=0, $input_var5=0, $input_val5=0, $input_var6=0, $input_val6=0, $input_var7=0, $input_val7=0, $input_var8=0, $input_val8=0, $input_var9=0, $input_val9=0, $input_var10=0, $input_val10=0)
      {
            global $liststart, $limit, $offset, $num, $numrows, $totPage;
			
			 
            if($input_offset=="" || $input_offset==1)
            {
                    $input_offset=(int)1;
                    $liststart=0;
            }
            else
            {
                    $liststart=($input_offset-1)*$input_limit;
            }

            $limit = $input_limit;
            $offset = $input_offset;
            $num = $input_num;

            //$pagevar1 = $input_var1;

            for($i=1 ; $i<=10 ; $i++)
            {
                    global ${"pagevar".$i}, ${"pageval".$i};
                    ${"pagevar".$i} = ${"input_var".$i};
                    ${"pageval".$i} = ${"input_val".$i};
            }
			
			//echo $input_query;
            $rs = $this->db->execute($input_query);
            $numrows = $rs->RecordCount();       // 총게시물 수
            $totPage = ceil($numrows / $limit); // 전체 페이지 수
      }

    function file_content_type($filename) {
     $idx = strtolower(end( explode( '.', $filename )) );
     $mimet = array(
       'ai' =>'application/postscript',
       'aif' =>'audio/x-aiff',
       'aifc' =>'audio/x-aiff',
       'xyz' =>'chemical/x-xyz',
       'zip' =>'application/zip',
       'xls' =>'application/vnd.ms-excel',
       'ppt' =>'application/mspowerpoint',
       'doc' =>'application/msword',
       'htm' =>'text/html',
       'html' =>'text/html',
       'eml' =>'message/rfc822',
       'txt' =>'text/plain',
       'pdf' =>'application/pdf',
       'jpg' =>'image/jpeg',
       'gif' =>'image/gif',
       'png' =>'image/png',
       'dwg' =>'application/acad',
       'dxf' =>'application/dxf'
     );

     if (isset( $mimet[$idx] )) {
       return $mimet[$idx];
     } else {
       return 'application/octet-stream';
     }
    }
    
    function file_manager_upload( $files , $folder, $table_name = 'file_manager' ) {
      //  테이블 구조
      //  CREATE TABLE IF NOT EXISTS `file_manager` (
      //    `F_Key` mediumint(9) unsigned NOT NULL auto_increment,
      //    `F_RName` varchar(100) default NULL,
      //    `F_VName` varchar(100) default NULL,
      //    `F_Folder` varchar(100) default NULL,
      //    `F_Size` varchar(10) default NULL,
      //    `F_RegDate` datetime default NULL,
      //    `Deleted` enum('Y','N') default 'N',
      //    PRIMARY KEY  (`F_Key`)
      //  );

      if ( $files[tmp_name] ) {
        $vfilename = substr(md5(time().$files[name]),0,100);
        
        $record = null;
        $record[F_RName]    = $files[name];
        $record[F_VName]    = $vfilename;
        $file_folder = $folder;
        if ( !is_dir($file_folder) ) mkdir($file_folder, 0755);
        $record[F_Folder]   = $file_folder;
        $record[F_Size]     = $files[size];
        $record[F_RegDate]  = date("Y-m-d H:i:s");
        $record[F_Deleted]  = "N";
        move_uploaded_file($files[tmp_name], $file_folder."/".$vfilename) or die ( '파일 등록 에러' );
        $this->db->AutoExecute("file_manager",$record,'INSERT');
        return $this->db->Insert_ID();
      };
    }

    function file_manager_info( $f_key, $table_name = 'file_manager' ) {

      $result = $this->db->Execute("SELECT * FROM $table_name WHERE Deleted = 'N' AND F_Key = ?", array( $f_key) );
      return $result->FetchRow();
    }
  }; // MCLEN_LIB_Class End
?>
