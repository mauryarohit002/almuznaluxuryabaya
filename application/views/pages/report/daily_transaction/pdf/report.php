<?php $this->mypdf_class->tcpdf();
	global $yy;

	class MYPDF extends TCPDF {
		public function Header(){
			$this->SetFont('Helvetica', 'B', 8);  
			$header = "";
			$header .= '<table border="1" cellpadding="3">
                            <tr>
                                <td>
                                    <table border="0" cellpadding="5">
                                        <tr>
                                            <td width="20%" >'.strtoupper($_SESSION['user_branch']).'</td>
                                            <td width="60%" align="center" style="font-size:12px;"><b>DAILY TRANSACTION</b></td>
                                            <td width="20%" align="right">'.date('d-m-Y H:i:s a').'</td>
                                        </tr>
                                    </table>		
                                </td>
                            </tr>		
                        </table>
						<table border="1" cellpadding="3" style="font-size:8px;">
							<tr>
								<th width="4%">#</th>
								<th width="20%">BILL NO</th>
								<th width="36%">CASH / MEMO NO</th>
								<th width="20%">AMOUNT</th>
								<th width="20%">MODE</th>
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
        	$this->SetFont('helvetica', 'I', 8);
        	// Page number
        	$this->Cell(175, 110, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    	}
	}

	// create new PDF document
	$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Imran Khan');
	$pdf->SetTitle('DAILY TRANSACTION PDF');
	$pdf->SetSubject('DAILY TRANSACTION PDF');

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

	$pdf->SetFont('Helvetica', 'B', 7);
	$body = "";
	$pdf->AddPage('L');
	$body .= '<table border="1" cellpadding="5">';
    if(!empty($data)):
        foreach ($data as $key => $value): 
        	// echo "<pre>"; print_r($key);die;
        	if($key!='search'):
        		$dt = date('d-m-Y', $key);
	            $body .= ' <tr  style="background-color: #cacfd1;">
	                            <td width="4%"></td>
	                            <td width="20%">'.$dt.'</td>
	                            <td width="36%"></td>
	                            <td width="20%"></td>
	                            <td width="20%"></td>
	                        </tr>';
	            if(!empty($value['data'])):
	            	foreach ($value['data'] as $k => $v):        
                    $body .= ' <tr>
	                                <td width="4%" >'.$v['sr_no'].'</td>
	                                <td width="20%">'.$v['entry_no'].'</td>
	                                <td width="36%">'.$v['action'].'&nbsp;-&nbsp;'.$v['customer_name'].'</td>
	                                <td width="20%">'.$v['amt'].'</td>
	                                <td width="20%">'.$v['payment_mode_name'].'</td>
	                            </tr>';
                    endforeach;    
                endif; 
                	if(!empty($value['total'])): 
			        $body .= '<tr>
                                <td class="border-0" colspan="5">
                                    <table width="100%" style="border: 1px solid #000; font-size:10px">';
                                    foreach ($value['total'] as $k => $v):
                                     $body .= '<tr>
                                                <td width="4%"  style=""></td>
                                                <td width="20%" style=""></td>
                                                <td width="36%" style="">'.$k.'</td>
                                                <td width="20%" style="">'.$v.'</td>
                                                <td width="20%" style=""></td>
                                            </tr>';
                                        endforeach;    
                            $body .= '</table>
                                </td>
                            </tr>'; 
	                endif;    
		                
            endif;
               endforeach;         
    endif;
	$body .= '</table>';
	$pdf->writeHTML($body, true, false, false, false, '');
	// $pdf->writeHTMLCell(188, 150, 6, 134, $body, 0, 0, 0, true, 'L', true);
	$pdf->IncludeJS("print();");
	// ---------------------------------------------------------

	//Close and output PDF document
	ob_end_clean();
	$pdf->Output('DAILY TRANSACTION PDF.pdf', 'I');
	//============================================================+
	// END OF FILE
	//============================================================+
