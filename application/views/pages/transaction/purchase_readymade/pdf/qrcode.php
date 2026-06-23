<?php
// Include the main TCPDF library (search for installation path).
$this->mypdf_class->tcpdf();

global $barcode_data ; 
global $company_name;
global $description;
global $category_name;
global $design_name;
global $color_name;
global $size_name;
global $cost_char;
global $mrp;
global $qty;
global $qrcode;
global $roll_no;

$barcode_data = $data['barcode_data'];
$company_pdf  = $data['company_data'];
// echo"<pre>";print_r($barcode_data);exit;
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    //Page header
    public function Header(){
    }

    // Page footer
    public function Footer() {	
    	$this->SetY(2);

    	global $barcode_data ;
    	global $company_pdf ;
  
		global $company_name;
		global $description;
    	global $category_name;
    	global $design_name;
    	global $color_name;
    	global $size_name;
    	global $cost_char;
    	global $mrp;
    	global $qty;
    	global $roll_no;
		$tbl = '<table border="0" cellpadding="3">
					<tr>
						<td  colspan="2" style="font-size: 12px;font-family:dejavusansb; text-align: center;">
							 <b >'.$company_name.'</b> 
						</td>		
					</tr>	
                    <tr>
						<td width="20%" style="font-size: 9px;" align="left">
							<b>DESIGN </b>
						</td>
						<td width="50%" style="font-size: 9px;" align="left">
							<b> : '.$design_name.'</b>
						</td>
					</tr>
					<tr>
						<td width="23%" style="font-size: 9px;" align="left">
							<b>CATEGORY</b>
						</td>
						<td width="50%" style="font-size: 9px;" align="left">
							<b> : '.$category_name.'</b>
						</td>
					</tr>
                    <tr>
						<td width="20%" style="font-size: 9px;" align="left">
							<b>COLOR </b>
						</td>
						<td width="50%" style="font-size: 9px;" align="left">
							<b> : '.$color_name.'</b>
						</td>
					</tr>
					<tr>
						<td width="20%" style="font-size: 9px;" align="left">
							<b>SIZE </b>
						</td>
						<td width="50%" style="font-size: 9px;" align="left">
							<b> : '.$size_name.'</b>
						</td>
					</tr>
					<tr>
						<td width="20%"  style="font-size: 9px;" align="left">
							<b>QTY  </b>
						</td>
						<td width="50%" style="font-size: 9px;" align="left">
							<b> : '.$qty.'</b>
						</td>
					</tr>
					<tr>
						<td width="20%" style="font-size: 9px;" align="left">
							<b>MRP </b>
						</td>
						<td width="50%" style="font-size: 14px;" align="left">
							<b> : '.$mrp.'</b>
						</td>
						<td width="30%" style="font-size: 8px;" align="center">
							<b > '.$roll_no.'</b>
						</td>
					</tr>
					<tr>
						<td width="70%" style="font-size: 9px;" align="left"></td>
                      	<td width="30%" style="font-size: 8px;" align="center">
							<b >'.$cost_char.'</b>
						</td>
					</tr>
				</table>';
		$this->writeHTML($tbl, true, false, false, false, '');
    }
}

// $page_size = array('38','34');
// $page_size = array('55.8','60.8');
$page_size = array('75','45');


// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT,$page_size, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('Purchase-Qrcode Pdf');
$pdf->SetSubject('Purchase-Qrcode Pdf');
// $pdf->SetFont('copperplateccheavy', '', 9);
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(2, 0, 2);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(30);

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
$company_name = strtoupper($company_pdf[0]['company_name']);

foreach ($barcode_data as $key => $value) {
	$pdf->AddPage($key+1);
	$qrcode 				= $value['qrcode'];
	$roll_no 				= $value['roll_no'] == 0 ? '' : $value['roll_no'];
	$mrp 					= round($value['mrp']);
	$design_name 			= $value['design_name']; 
	$category_name 			= $value['readymade_category_name'];
	$color_name 			= $value['color_name'];
	$size_name 				= $value['size_name'];
	$mrp 					= $value['mrp'];
	$qty					= $value['qty'];
	$cost_char				= $value['cost_char'];
	$description 			= $value['description'];

    $style['border'] 		= 0;
	$style['vpadding'] 		= 2;
	$style['hpadding'] 		= 2;
	$style['fgcolor'] 		= array(0,0,0);
	$style['bgcolor'] 		= false;
	$style['module_width'] 	= 1;
	$style['module_height'] = 1;

	$pdf->write2DBarcode($qrcode, 'QRCODE,H', 52, 10, 50, 20, $style, 'N');
	
}
// ---------------------------------------------------------


// note


// first declare all variable global becouse they can inherited by any class function

// then set page size 24,42 if landscape mode first para is height and second width

// if u increase page height then set value of setfootermargin 
// ---------------------------------------------------------
// $pdf->IncludeJS("print();");
//Close and output PDF document
$pdf->Output('Purchase-Qrcode.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+