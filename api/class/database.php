<?php
Class MyDatabase
{
  function MyDatabase()
  {
    $server="27.254.36.4";
    $user="revoclou_afm";
    $pass="?AL2cP4l#o?X";
    $database="revoclou_afm";
    $this->con=mysqli_connect($server,$user,$pass,$database);
    $this->conset=mysqli_set_charset($this->con,'UTF8');
  }//function MyDatabase

  function fncInsertRecord($strinsert)
  {
      $strSQL = "$strinsert";
      $objQuery =mysqli_query($this->con,$strSQL);
      return $objQuery;
  }

  /**** function select record ****/
  function fncSelectRecord($strCondition)
  {
      $strSQL = "$strCondition";
      $objQuery =mysqli_query($this->con,$strSQL);
      if(!$objQuery){
        $num_rows =0;
      }else{
        $num_rows = mysqli_num_rows($objQuery);
      }

      $rowResult = array();
      if ($num_rows>0) {
        while($row =mysqli_fetch_assoc($objQuery)){
        $rowResult[] = $row;
      }

  }
    return   $rowResult;
      //return $strSQL;
  }

  /**** function update record (argument) ****/
  function fncUpdateRecord($strCondition)
  {
      $strSQL ="$strCondition";
      return mysqli_query($this->con,$strSQL);
  }

  /**** function delete record ****/
  function fncDeleteRecord($strCondition)
  {
      $strSQL = "$strCondition";
      return mysqli_query($this->con,$strSQL);
  }

  /*** end class auto disconnect ***/
  function __destruct() {
      return mysqli_close($this->con);
    }



}//class
 ?>
