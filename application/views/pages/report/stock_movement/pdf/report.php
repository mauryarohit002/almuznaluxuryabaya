<?php $this->mypdf_class->tcpdf();
	global $yy;

	class MYPDF extends TCPDF {
		public function Header(){
			$this->SetFont('copperplateccheavy', 'B', 8);
			$header = "";
			$_customer_name 	= (isset($_GET['_customer_name'])) ? strtoupper($_GET['_customer_name']) : '';
			$_contact_person 	= (isset($_GET['_contact_person'])) ? strtoupper($_GET['_contact_person']) : '';
			$header .= '<table border="1" cellpadding="3">
                            <tr>
                                <td>
                                    <table border="0" cellpadding="5">
                                        <tr>
                                            <td width="20%" >'.strtoupper($_SESSION['user_branch']).'</td>
                                            <td width="60%" align="center" style="font-size:12px;"><b>MISSING MOBILE NO</b></td>
                                            <td width="20%" align="right">'.date('d-m-Y H:i:s a').'</td>
                                        </tr>
                                    </table>		
                                </td>
                            </tr>		
                            <tr>
                                <td width="50%">CUSTOMER : '.$_customer_name.'</td>
                                <td width="50%">CONTACT PERSON : '.$_contact_person.'</td>
                            </tr>
                        </table>
						<table border="1" cellpadding="3" style="font-size:8px;">
							<tr>
								<th width="3%">#</th>
								<th width="12%">CUSTOMER</th>
								<th width="12%">CONTACT PERSON</th>
								<th width="8%">MOBILE NO</th>
								<th width="8%">PHONE NO</th>
								<th width="10%">EMAIL</th>
								<th width="15%">ADDRESS</th>
								<th width="8%">CITY</th>
								<th width="8%">STATE</th>
								<th width="8%">COUNTRY</th>
								<th width="8%">PINCODE</th>
							</tr>
						</table>';
			
			$this->writeHTMLCell(287, 195, 5, 5, $header, 0, 0, 0, true, 'L', true);
			// $this->SetTopMargin(3);	
			$yy = $this->GetY();
			$yy = $yy + 18.5;
			$this->SetTopMargin($yy + 0);
		}

		public function Footer(){
    		$footer = "";
    		$this->writeHTMLCell(200, 195, 5, 150, $footer, 0, 0, 0, true, 'L', true);
        	// Set font
        	$this->SetFont('copperplateccheavy', 'I', 8);
        	// Page number
        	$this->Cell(175, 110, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    	}
	}

	// create new PDF document
	$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Imran Khan');
	$pdf->SetTitle('MISSING MOBILE NO');
	$pdf->SetSubject('MISSING MOBILE NO');

	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(5, 0, 5);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(74);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, 16);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) 
	{
	    require_once(dirname(__FILE__).'/lang/eng.php');
	    $pdf->setLanguageArray($l);
	}

	$pdf->SetFont('copperplateccheavy', 'B', 7);
	$body = "";
	$pdf->AddPage('L');
	$body .= '<table border="1" cellpadding="5">';
    if(!empty($data)):
        foreach ($data as $key => $value):
            $body .= ' <tr>
							<td width="3%">'.($key + 1).'</td>
							<td width="12%">'.$value['customer_name'].'</td>
							<td width="12%">'.$value['contact_person'].'</td>
							<td width="8%">'.$value['customer_mobile'].'</td>
							<td width="8%">'.$value['customer_phone'].'</td>
							<td width="10%" >'.$value['customer_email'].'</td>
							<td width="15%">'.$value['customer_address'].'</td>
							<td width="8%">'.$value['city_name'].'</td>
							<td width="8%">'.$value['state_name'].'</td>
							<td width="8%">'.$value['country_name'].'</td>
							<td width="8%">'.$value['customer_pincode'].'</td>
                    </tr>';
        endforeach;
    endif;
	$body .= '</table>';
	$pdf->writeHTML($body, true, false, false, false, '');
	// $pdf->writeHTMLCell(188, 150, 6, 134, $body, 0, 0, 0, true, 'L', true);
	$pdf->IncludeJS("print();");
	// ---------------------------------------------------------

	//Close and output PDF document
	ob_end_clean();
	$pdf->Output('MISSING MOBILE NO.pdf', 'I');
	//============================================================+
	// END OF FILE
	//============================================================+
