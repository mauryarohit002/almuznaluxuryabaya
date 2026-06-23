<?php
// Include the main TCPDF library (search for installation path).
$this->mypdf_class->tcpdf();

global $barcode_data ; 
global $company_name;
global $entry_no;
global $entry_date;
global $customer_name;
global $customer_mobile;
global $apparel_name;
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
	    global $entry_no;
        global $entry_date;
        global $customer_name;
        global $customer_mobile;
        global $apparel_name;
        global $qrcode;
        global $roll_no;
		
        $tbl = '<table border="0" cellpadding="3">
					<tr>
						<td  colspan="2" style="font-size: 12px;font-family:dejavusansb; text-align: center;">
							 <b >'.$company_name.'</b> 
						</td>		
					</tr>	
					<tr>
						<td width="30%" style="font-size: 9px;" align="left">
							<b>ENTRY NO </b>
						</td>
						<td width="40%" style="font-size: 9px;" align="left">
							<b> : '.$entry_no.'</b>
						</td>
					</tr>
                    <tr>
						<td width="30%" style="font-size: 9px;" align="left">
							<b>ENTRY DATE </b>
						</td>
						<td width="40%" style="font-size: 9px;" align="left">
							<b> : '.$entry_date.'</b>
						</td>
					</tr>
                    <tr>
						<td width="100%" style="font-size: 9px;" align="left">
							<b>'.$customer_name.'</b>
						</td>
					</tr>
					<tr>
						<td width="100%" style="font-size: 9px;" align="left">
							<b>'.$customer_mobile.'</b>
						</td>
					</tr>
                    <tr>
						<td width="100%" style="font-size: 9px;" align="left">
							<b>'.$apparel_name.'</b>
						</td>
					</tr>
                    <tr>
                        <td width="100%" style="font-size: 9px;" align="right">
							<b>'.$roll_no.'</b>
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
$pdf->SetTitle('Estimate-Qrcode Pdf');
$pdf->SetSubject('Estimate-Qrcode Pdf');
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
	$entry_no 			    = $value['entry_no'];
	$entry_date 			= $value['entry_date'];
	$customer_name 			= $value['customer_name'];
	$customer_mobile 		= $value['customer_mobile'];
	$apparel_name 			= $value['apparel_name'];

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
$pdf->Output('Estimate-Qrcode.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+