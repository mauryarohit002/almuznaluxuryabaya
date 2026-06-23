<?php
// Include the main TCPDF library (search for installation path).
$this->mypdf_class->tcpdf();

global $print_array ; 
global $barcode;
global $mrp;
global $design_name;
global $fabric_name;
global $mtr;
global $supplier_code;
global $params;
global $company_name;

$print_array = $data['barcode_data'];
$company_pdf = $data['company_data'];
// echo"<pre>";print_r($print_array);exit;
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF 
{
    //Page header
    public function Header() 
    {
		
    }

    // Page footer
    public function Footer() 
    {	
    	$this->SetY(2);

    	global $print_array ;
    	global $company_pdf ;
  
    	global $mrp;
    	global $fabric_name;
    	global $design_name;
    	global $mtr;
    	global $barcode;
		global $params;
		global $supplier_code;
		global $company_name;

		// echo $vend_code;
		
    
		$footer_tbl = "";
		
			$footer_tbl .= <<<EOD
				<table border="0" cellpadding="2" >
					<tr>
						<td  colspan="2" style="font-size: 12px;font-family:dejavusansb">
							 <b >$company_name</b> 
						</td>		
					</tr>	
					<tr>
						<td width="50%" style="font-size: 9px;" align="left">
							<b>FABRIC </b>
						</td>
						<td width="50%" style="font-size: 9px;" align="left">
							<b> : $fabric_name</b>
						</td>
					</tr>
					<tr>
						<td width="50%" style="font-size: 9px;" align="left">
							<b>SUPP CODE </b>
						</td>
						<td width="50%" style="font-size: 9px;" align="left">
							<b> : $supplier_code</b>
						</td>
					</tr>
					
					<tr>
						<td width="50%"  style="font-size: 9px;" align="left">
							<b>QTY  </b>
						</td>
						<td width="50%" style="font-size: 9px;" align="left">
							<b> : $mtr</b>
						</td>
					</tr>
					<tr>
						<td width="50%"  style="font-size: 9px;" align="left">
							<b>M.R.P.  </b>
						</td>
						<td width="50%" style="font-size: 9px;" align="left">
							<b > : $mrp</b>
						</td>
					</tr>
					<tr>
						<td width="20px;"  style="font-size: 9px;">
							<b></b>
						</td>
						<td width="125px;" style="font-size: 14px;">
							<tcpdf method="write1DBarcode" params="$params" />
						</td>
					</tr>
	
				</table>
				
EOD;
		
		$this->writeHTML($footer_tbl, true, false, false, false, '');
    }
}

// $page_size = array('38','34');
// $page_size = array('55.8','60.8');
$page_size = array('75','38');


// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT,$page_size, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('Barcode Pdf');
$pdf->SetSubject('Barcode Pdf');
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



$array_cnt = count($print_array);


$company_name = strtoupper($company_pdf[0]['company_name']);

for($i=0; $i<$array_cnt; $i++)
{
	$pdf->AddPage($i);
	$barcode = $print_array[$i]['roll_no'];

	// $sold_qty = $print_array[$i]['sold_qty'];
	
	$mrp 			= round($print_array[$i]['mrp']);
	$fabric_name 	= strtoupper(substr($print_array[$i]['fabric_name'],0,12));
	$rate 			= strtoupper($print_array[$i]['rate']);
	$mtr			= $print_array[$i]['mtr'];
	$supplier_code 	= substr($print_array[$i]['supplier_name'],0,1).''.strtoupper(str_replace('_', ' ', $print_array[$i]['supplier_code']));
	$design_name		= $print_array[$i]['design_name'];


	$params ="";
	$params = $pdf->serializeTCPDFtagParameters(array($barcode, 'I25', '', '',45, 14, 0.8, 
													array('position'=>'C', 
														'border'=>false, 
														'padding'=>1, 
														'fgcolor'=>array(0,0,0), 
														'bgcolor'=>array(255,255,255), 
														'text'=>true, 
														'font'=>'copperplateccheavy', 
														'fontsize'=>8, 
														'stretchtext'=>2), 'N'));
	
}
// ---------------------------------------------------------


// note


// first declare all variable global becouse they can inherited by any class function

// then set page size 24,42 if landscape mode first para is height and second width

// if u increase page height then set value of setfootermargin 
// ---------------------------------------------------------
// $pdf->IncludeJS("print();");
//Close and output PDF document
$pdf->Output('Qrcode.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+