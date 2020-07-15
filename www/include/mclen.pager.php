<?
  include("$path/adodb5/adodb-pager.inc.php");

  class MCLEN_Pager extends ADODB_Pager {
  	function RenderGrid()
  	{
  		ob_start();
      $rs = $this->db->Execute($this->rs->sql);
      while ($row = $rs->FetchRow()) {
          if ( $row[B_ReplyCount] > 0 ) $reply = " [".$row[B_ReplyCount]."]";
          else $reply = "";

          echo "<tr align='center'>";
          echo "<td>".$row[B_No]."</td>";
          echo "<td align='left'>".$row[B_Subject].$reply."</td>";
          echo "<td>".$row[M_Name]."</td>";
          echo "<td>".$row[B_RegDate]."</td>";
          echo "<td>".$row[B_Count]."</td>";
          echo "</tr>";
      }

  		$s = ob_get_contents();
  		ob_end_clean();
  		return $s;
  	}

  	function RenderLayout($header,$grid,$footer,$attributes='')
  	{
?>

<?
  	}
  }
?>