<?php
error_reporting(0);
// Include the main TCPDF library (search for installation path).
$this->mypdf_class->tcpdf();

global $barcode_data ; 
global $company_name;
global $entry_no;
global $entry_date;
global $customer_name; 
global $customer_mobile;
global $apparel_name;
global $sku_name;

global $qrcode;
global $roll_no;
global $day; 
global $month;
global $year;     
 
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
        global $sku_name;
  
        global $qrcode;
        global $roll_no;
        global $day;
        global $month;
		global $year;

		
		$img =base_url('public/assets/dist/images/logo1.png');
       	$tbl = '<br>
		<table  cellpadding="3"  style="border-top-color:0.5px solid #000;border-style:dashed;" ></table><br>
		<table border="0" cellpadding="1" >
			<tr>
				<td align="center" style="
					font-size:18px;
					font-weight:bold;
					letter-spacing:1px;
					text-transform:uppercase;
				">
					AL MUZNA
				</td>
			</tr>

			<tr>
				<td align="center" style="
					font-size:10px;
					letter-spacing:2px;
					text-transform:uppercase;
				">
					LUXURY ABAYA
				</td>
			</tr>
			<br/><br/><br/><br/><br/><br/><br/>
			<tr>
				<td width="100%" style="font-size: 15px;" align="center">
					<b >'.$qrcode.'</b> 
				</td>
			</tr>
		</table>

		<table border="0" cellpadding="2" style="line-height: 14px;">
			<tr>
				<td width="100%" style="font-size:12px;font-weight:normal;" align="center">
					CRAFTED FOR <br/>
					<b style="text-transform:uppercase;font-size:13px;">'.(strlen($customer_name) > 25 ? substr($customer_name,0,25).'...' : $customer_name).'</b>
				</td>		
			</tr>
		</table>
		<table cellpadding="0" style="line-height: 12px;border-bottom:0.5px solid black;">
			<!-- <tr>
				<td width="100%" style="font-size: 12px;" align="center">
					<b >'.$apparel_name.' - '.$sku_name.'</b> 
				</td>
			</tr> -->
			<tr>
				<td width="100%" style="font-size: 12px;text-transform:uppercase;" align="center">
					<b >'.$entry_no.'</b> / <b style="text-transform:uppercase;">'.$day.' '.$month .' '.$year .'</b>
				</td>
			</tr>
			<hr>
		</table>
				
		';
		$this->writeHTML($tbl, true, false, false, false, '');
    }
}


$page_size = array('45','70');

// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT,$page_size, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('Estimate-Qrcode Pdf');
$pdf->SetSubject('Estimate-Qrcode Pdf');
// $pdf->SetFont('helvetica', '', 9);
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(3, 0, 3);
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
	$customer_name 			= trim($value['customer_name']);
	$customer_mobile 		= $value['customer_mobile'];
	$apparel_name 			= $value['apparel_name'];
	$sku_name 				= $value['sku_name'];

	$day = date('d', strtotime($value['entry_date']));
	$month = date('M', strtotime($value['entry_date']));
	$year = date('Y', strtotime($value['entry_date'])); 

    $style['border'] 		= 0;
	$style['vpadding'] 		= 0;
	$style['hpadding'] 		= 0;
	$style['fgcolor'] 		= array(0,0,0);
	$style['bgcolor'] 		= false;
	$style['module_width'] 	= 1;
	$style['module_height'] = 1;

	$pdf->write2DBarcode($qrcode, 'QRCODE,H', 2, 15, 47, 20, $style, 'N');
	
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