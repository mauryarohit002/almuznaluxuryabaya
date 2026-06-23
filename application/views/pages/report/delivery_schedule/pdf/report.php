<?php $this->mypdf_class->tcpdf();
	global $yy;

	class MYPDF extends TCPDF {
		public function Header(){
			$this->SetFont('copperplateccheavy', 'B', 8);
			$header = "";
			$_customer_name 	= (isset($_GET['_customer_name'])) ? strtoupper($_GET['_customer_name']) : '';
			$_date_from 		= (isset($_GET['_delivery_date_from'])) ? date('d-m-Y', strtotime($_GET['_delivery_date_from'])) : "";
			$_date_to 			= (isset($_GET['_delivery_date_to']) && $_GET['_delivery_date_to'] != '') ? ' TO '.date('d-m-Y', strtotime($_GET['_delivery_date_to'])) : "";
			$header .= '<table border="1" cellpadding="3">
                            <tr>
                                <td>
                                    <table border="0" cellpadding="5">
                                        <tr>
                                            <td width="20%" >'.strtoupper($_SESSION['user_branch']).'</td>
                                            <td width="60%" align="center" style="font-size:12px;"><b>DELIVERY SCHEDULE</b></td>
                                            <td width="20%" align="right">'.date('d-m-Y H:i:s a').'</td>
                                        </tr>
                                    </table>		
                                </td>
                            </tr>		
                            <tr>
                                <td width="50%">CUSTOMER : '.$_customer_name.'</td>
                                <td width="50%">DELIVERY DATE : '.$_date_from.' '.$_date_to.'</td>
                            </tr>
                        </table>
						<table border="1" cellpadding="3" style="font-size:10px;">
							<tr>
								<th width="4%">#</th>
								<th width="7%">ORDER</th>
								<th width="28%">CUSTOMER</th>
								<th width="12%">MOBILE NO</th>
								<th width="11%">ORDER DATE</th>
								<th width="10%">TRIAL DATE</th>
								<th width="10%">DEL. DATE</th>
								<th width="18%">NOTES</th>
							</tr>
						</table>';
			
			$this->writeHTMLCell(200, 195, 5, 5, $header, 0, 0, 0, true, 'P', true);
			// $this->SetTopMargin(3);	
			$yy = $this->GetY();
			$yy = $yy + 19.2;
			$this->SetTopMargin($yy + 0);
		}

		public function Footer(){
    		$footer = "";
    		$this->writeHTMLCell(200, 195, 5, 150, $footer, 0, 0, 0, true, 'P', true);
        	// Set font
        	$this->SetFont('copperplateccheavy', 'I', 8);
        	// Page number
        	$this->Cell(0, 280, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    	}
	}

	// create new PDF document
	$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Imran Khan');
	$pdf->SetTitle('DELIVERY SCHEDULE');
	$pdf->SetSubject('DELIVERY SCHEDULE');

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
	$pdf->SetAutoPageBreak(TRUE, 15);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) 
	{
	    require_once(dirname(__FILE__).'/lang/eng.php');
	    $pdf->setLanguageArray($l);
	}

	$pdf->SetFont('copperplateccheavy', 'B', 9);
	$body = "";
	$pdf->AddPage('P');
	$body .= '<table border="1" cellpadding="5">';
    if(!empty($data)):
        foreach ($data as $key => $value):
            $body .= ' <tr>
                        <td width="4%">'.($key+1).'</td>
                        <td width="7%">'.$value['entry_no'].'</td>
                        <td width="28%">
                            <table>
								<tr><td>'.$value['customer_name'].'</td></tr>
							</table>
							<table cellpadding="5">
								<tr><td>'.$value['apparel_data'].'</td></tr>
							</table>
                        </td>
                        <td width="12%">'.$value['customer_mobile'].'</td>
                        <td width="11%">'.$value['entry_date'].'</td>
                        <td width="10%">'.$value['trial_date'].'</td>
                        <td width="10%">'.$value['delivery_date'].'</td>
                        <td width="18%">'.$value['notes'].'</td>
                    </tr>';
        endforeach;
    endif;
	$body .= '</table>';
	$pdf->writeHTML($body, true, false, false, false, '');
	// $pdf->writeHTMLCell(188, 150, 6, 134, $body, 0, 0, 0, true, 'P', true);
	$pdf->IncludeJS("print();");
	// ---------------------------------------------------------

	//Close and output PDF document
	ob_end_clean();
	$pdf->Output('DELIVERY SCHEDULE.pdf', 'I');
	//============================================================+
	// END OF FILE
	//============================================================+
