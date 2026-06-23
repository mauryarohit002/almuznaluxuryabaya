<?php 
    $this->mypdf_class->tcpdf();
	global $master_pdf;
	global $trans_pdf;
	global $yy;

	$master_pdf = $master_data;
	$trans_pdf  = $trans_data;

	class MYPDF extends TCPDF {
		public function Header(){
			global $master_pdf;
            $date_time 		    = date('d-m-Y h:i:s a');
            $title 	            = $master_pdf[0]['titles'][1].' '.$master_pdf[0]['titles'][2];
			$print_type 	    = 'PURCHASE';

			$entry_no 			= $master_pdf[0]['entry_no'];
            $entry_date			= date('d-m-Y', strtotime($master_pdf[0]['entry_date']));
			$bill_no 			= $master_pdf[0]['bill_no'];
			$order_no 			= $master_pdf[0]['order_no'];
			$notes 				= $master_pdf[0]['notes'];
			$supplier_name		= $master_pdf[0]['supplier_name'];
			$supplier_address	= $master_pdf[0]['supplier_address'];
			$transport_name		= $master_pdf[0]['transport_name'];

			$this->SetFont('Times', '', 8);
			$tbl_header='<table border="0" cellpadding="3">
                            <tr>
                                <td width="80%" style="font-size:14px;" align="center"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$print_type.'</b></td>
                                <td width="20%" style="font-size:12px;" ><b>'.$date_time.'</b></td>
                            </tr>
                        </table>				
                        <table border="0" cellpadding="3">
                            <tr>
                                <td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
                                    <b>ENTRY NO</b>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$entry_no.'
                                </td>
                                <td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000;">
                                    <b>ENTRY DATE</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$entry_date.'
                                </td>
                            </tr>
							<tr>
                                <td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
                                    <b>BILL NO</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$bill_no.'
                                </td>
                                <td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000;">
                                    <b>P.O NO</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$order_no.'
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
                                    <b>SUPPLIER</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$supplier_name.'
                                </td>
                                <td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000;">
                                    <b>TRANSPORT</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$transport_name.'
                                </td>
                            </tr>
							<tr>
                                <td width="100%" height="50px" colspan="2" style="border-top:1px solid #000; border-bottom:1px solid #000;">
                                    <b>NOTES</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$notes.'
                                </td>
                            </tr>
                        </table>
                        <table cellpadding="5" border="0">
                            <tr>
                                <th width="6%" 	style="border-bottom:1px dashed #000;">#</th>
                                <th width="24%" style="border-bottom:1px dashed #000;">FABRIC</th>
                                <th width="24%" style="border-bottom:1px dashed #000;">DESIGN NO</th>
                                <th width="10%" style="border-bottom:1px dashed #000;">ROLL</th>
                                <th width="11%" style="border-bottom:1px dashed #000;">MTR</th>
                                <th width="11%" style="border-bottom:1px dashed #000;">RATE</th>
                                <th width="13%" style="border-bottom:1px dashed #000;">AMOUNT</th>
                            </tr>
                        </table>';
			$this->writeHTMLCell(200, 280, 6, 6, $tbl_header, 0, 0, 0, true, 'P', true);
			// $this->SetTopMargin(3);	
			$yy = $this->GetY();
			$yy = $yy + 0;
			// $this->line(16,$yy,16,280);
			// $this->line(96,$yy,96,280);
			// $this->line(176,$yy,176,280);
			// $this->line(166,$yy,166,280);
			$this->SetTopMargin($yy + 45);
		}

		public function Footer(){
    		$tbl_footer = "";
		  	$this->writeHTMLCell(200, 150, 6, 280, $tbl_footer, 0, 0, 0, true, 'P', true);
        	// Set font
        	$this->SetFont('helvetica', 'I', 8);
        	// Page number
        	$this->Cell(0, 20, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    	}
	}

	// create new PDF document
	$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Imran Khan');
	$pdf->SetTitle('PURCHASE');
	$pdf->SetSubject('PURCHASE');

	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(6, 38, 4);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(74);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, 25);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) 
	{
	    require_once(dirname(__FILE__).'/lang/eng.php');
	    $pdf->setLanguageArray($l);
	}


	$pdf->SetFont('Times', '', 8);
	$pdf->AddPage('P');
	$total_rolls= $master_pdf[0]['total_rolls'];
    $total_mtr 	= $master_pdf[0]['total_mtr'];
    $total_amt 	= $master_pdf[0]['total_amt'];
	$tbl 	    = ' <table border="0" cellpadding="5" style="font-size:13px;">';
                        foreach ($trans_pdf as $key => $value){
                            $sr_no		= $key+1;
                            $fabric_name= $value['fabric_name'];
                            $design_no	= $value['design_no'];
                            $qty		= $value['qty'];
                            $mtr	    = $value['total_mtr'];
                            $rate		= $value['rate'];
                            $amt		= $value['amt'];

                            $tbl.=' <tr >
                                        <td width="6%"  style="border-bottom:1px dashed #000;">'.$sr_no.'</td>
                                        <td width="24%" style="border-bottom:1px dashed #000;">'.$fabric_name.'</td>
                                        <td width="24%" style="border-bottom:1px dashed #000;">'.$design_no.'</td>
                                        <td width="10%" style="border-bottom:1px dashed #000;">'.$qty.'</td>
                                        <td width="11%" style="border-bottom:1px dashed #000;">'.$mtr.'</td>
                                        <td width="11%" style="border-bottom:1px dashed #000;">'.$rate.'</td>
                                        <td width="13%" style="border-bottom:1px dashed #000;">'.$amt.'</td>
                                    </tr>';
                        }

	        $tbl.=' </table>			
                    <table cellpadding="5" border="0" style="font-size:12px; font-weight:bold;">
                        <tr>
                            <td width="54%" style="border-top: 1px dashed #000;" align="right" colspan="3">TOTAL</td>
                            <td width="10%" style="border-top: 1px dashed #000;" >'.$total_rolls.'</td>
                            <td width="11%" style="border-top: 1px dashed #000;" >'.$total_mtr.'</td>
                            <td width="11%" style="border-top: 1px dashed #000;" ></td>
                            <td width="13%" style="border-top: 1px dashed #000;" >'.$total_amt.'</td>
                        </tr>
                    </table>';
	$pdf->writeHTML($tbl, true, false, false, false, '');
	// $pdf->writeHTMLCell(188, 150, 6, 134, $tbl, 0, 0, 0, true, 'P', true);
	$pdf->IncludeJS("print();");
	// ---------------------------------------------------------

	//Close and output PDF document
	ob_end_clean();
	$pdf->Output('PURCHASE', 'I');
	//============================================================+
	// END OF FILE
	//============================================================+
