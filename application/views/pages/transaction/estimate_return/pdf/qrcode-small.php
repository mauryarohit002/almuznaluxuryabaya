<?php
// Include the main TCPDF library (search for installation path).
$this->mypdf_class->tcpdf();
$print = $data;
class MYPDF extends TCPDF {
    //Page header
    public function Header(){
    }

    // Page footer
    public function Footer(){
    }
}

// $page_size = array('38','34');
$page_size = array('48','35');

// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT,$page_size, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('Purchase-QRCode Pdf');
$pdf->SetSubject('Purchase-QRCode Pdf');
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
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

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
// echo "<pre>"; print_r($print); exit();
foreach ($print as $key => $value) {
	$pdf->AddPage();
	$merchant_label 		= 'Merchant Name';
	$articel_label 			= 'Article Code';
	$color_label 			= 'Color No';
	$qty_label 				= 'Mtrs.';
	$width_label 			= 'Width';

	$item_code 				= $value['bm_roll_no'];
	$merchant_name 			= $value['merchant_name'];
	$item_name 				= strtoupper($value['item_name']);
	$color_no				= $value['color_no'];
	$qty 					= $value['bm_pt_mtr'];
	$width 					= '';
	
	$style['border'] 		= 0;
	$style['vpadding'] 		= 5;
	$style['hpadding'] 		= 5;
	$style['fgcolor'] 		= array(0,0,0);
	$style['bgcolor'] 		= false;
	$style['module_width'] 	= 1;
	$style['module_height'] = 1;

	$pdf->SetFont('copperplateccheavy', 'B', 4);
	$pdf->MultiCell(12, 10, $merchant_label, 0, 'L', 0, 1, 1, 10, true, 0, true, true, 10, 'T');
	$pdf->MultiCell(12,  6, $articel_label,  0, 'L', 0, 1, 1, 20, true, 0, true, true, 10, 'T');
	$pdf->MultiCell(12,  8, $color_label,    0, 'L', 0, 1, 1, 26, true, 0, true, true, 10, 'T');
	$pdf->MultiCell(12,  4, $qty_label, 	 0, 'L', 0, 1, 1, 34, true, 0, true, true, 10, 'T');
	$pdf->MultiCell(12,  8, $width_label,    0, 'L', 0, 1, 1, 38, true, 0, true, true, 10, 'T');
	
	$pdf->SetFont('copperplateccheavy', 'B', 3);
	$pdf->MultiCell(21, 2, $item_code, 0, 'L', 0, 1, 13, 10, true, 0, true, true, 10, 'T');
	
	$pdf->SetFont('copperplateccheavy', 'B', 4);
	$pdf->MultiCell(13, 8, $merchant_name, 0, 'L', 0, 1, 13, 12, true, 0, true, true, 10, 'T');
	$pdf->MultiCell(21, 6, $item_name,     0, 'L', 0, 1, 13, 20, true, 0, true, true, 10, 'T');
	$pdf->MultiCell(13, 8, $color_no,      0, 'L', 0, 1, 13, 26, true, 0, true, true, 10, 'T');
	$pdf->MultiCell(21, 4, $qty,           0, 'L', 0, 1, 13, 34, true, 0, true, true, 10, 'T');
	$pdf->MultiCell(13, 8, $width,         0, 'L', 0, 1, 13, 38, true, 0, true, true, 10, 'T');
	
	$pdf->write2DBarcode($item_code, 'QRCODE,H', 26, 12, 20, 20, $style, 'N');
	$pdf->write2DBarcode($item_code, 'QRCODE,H', 26, 26, 20, 20, $style, 'N');
	$pdf->write2DBarcode($item_code, 'QRCODE,H', 26, 38, 20, 20, $style, 'N');
}

// ---------------------------------------------------------


// note


// first declare all variable global becouse they can inherited by any class function

// then set page size 24,42 if landscape mode first para is height and second width

// if u increase page height then set value of setfootermargin 
// ---------------------------------------------------------
// $pdf->IncludeJS("print();");
//Close and output PDF document
$pdf->Output('QRCode.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+