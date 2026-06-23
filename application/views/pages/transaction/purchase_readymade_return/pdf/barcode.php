<?php
// Include the main TCPDF library (search for installation path).
$this->mypdf_class->tcpdf();

global $print ; 
global $barcode;
global $params;
global $item_code;
global $color_code;
global $color_name;
global $supplier_code;

$print = $data;
class MYPDF extends TCPDF 
{
    //Page header
    public function Header() 
    {
		
    }

    // Page footer
    public function Footer() 
    {	
    	$this->SetY(1);

    	global $print ;
    	global $barcode;
		global $params;
		global $item_code;
		global $color_code;
		global $color_name;
		global $supplier_code;
		
		$footer_tbl = "";
		
			$footer_tbl .= <<<EOD
				<table border="0">
					<tr>
						<td width="100%" style="text-align:center;font-size:10px;"><b>REGAL FASHION</b></td>
					</tr>										
					<tr>
						<td width="70%" style="text-align:left;font-size:9px;"><b>&nbsp;&nbsp;&nbsp;$item_code</b></td>
						<td width="30%" style="text-align:center;font-size:9px;"><b>$supplier_code</b></td>
					</tr>					
					<tr>
						<td width="50%" style="font-size:9px;"><b>&nbsp;&nbsp;&nbsp;$color_name</b></td>
						<td width="50%" style="text-align:center;font-size:9px;"><b>$color_code</b></td>
					</tr>					
					<tr >
						<td width="100%" height="30px" style="font-size:5px;"><tcpdf method="write1DBarcode" params="$params" /><br/><br/><br/></td>
					</tr>
					<tr >
						<td width="100%" style="text-align:center;font-size:11px;"><b>$barcode</b></td>
					</tr>
				</table>
				
EOD;
		
		$this->writeHTML($footer_tbl, true, false, false, false, '');
    }
}

// $page_size = array('38','34');
$page_size = array('25','50');

// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT,$page_size, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('Barcode Pdf');
$pdf->SetSubject('Barcode Pdf');
// $pdf->SetFont('copperplateccheavy', '', 10);
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(1, 0, 1);
$pdf->SetHeaderMargin(4);
$pdf->SetFooterMargin(12);

// $pdf->SetMargins(PDF_MARGIN_LEFT- 0, PDF_MARGIN_TOP-29, PDF_MARGIN_RIGHT-16);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 0);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}



$array_cnt = count($print);

// echo "<pre>"; print_r($print); exit();
foreach ($print as $key => $value) {
	$pdf->AddPage();
	$barcode 				= $value['bm_roll_no'];
	$color_code 			= substr($value['color_code'], 0, 7);
	$color_name 			= strtoupper(substr($value['color_name'], 0, 10));
	$supplier_code 			= strtoupper($value['supplier_code']);
	
	$options['bgcolor'] 	= array(255,255,255);
	$options['border'] 		= false;
	$options['fgcolor'] 	= array(0,0,0);
	$options['font'] 		= 'copperplateccheavy';
	$options['fontsize'] 	= 6;
	$options['padding'] 	= 1;
	$options['position'] 	= 'C';
	$options['stretchtext'] = 2;
	$options['text'] 		= false;

	$params = $pdf->serializeTCPDFtagParameters(array($barcode, 'I25', '', '', 45, 8, 0.8, $options, 'S'));
}

// ---------------------------------------------------------


// note


// first declare all variable global becouse they can inherited by any class function

// then set page size 24,42 if landscape mode first para is height and second width

// if u increase page height then set value of setfootermargin 
// ---------------------------------------------------------
// $pdf->IncludeJS("print();");
//Close and output PDF document
$pdf->Output('Barcode.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+