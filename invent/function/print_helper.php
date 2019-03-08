<?php

function inputRow($text)
{
  return '<input type="text" class="print-row" value="'.$text.'" />';
}


function barcodeImage($text = '', $size = 8)
{
  if($text != '')
  {
    return  '<img src="'.WEB_ROOT.'library/class/barcode/barcode.php?text='.$text.'" style="max-height:'.$size.'mm; max-width:150px;" />';
  }

}

 ?>
