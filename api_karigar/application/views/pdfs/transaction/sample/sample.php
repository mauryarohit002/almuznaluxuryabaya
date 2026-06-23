<?php $this->mypdf_class->tcpdf();
	global $master_pdf;
	global $trans_pdf;
	global $yy;

	$master_pdf = $master_data;
	$trans_pdf  = $trans_data;

	class MYPDF extends TCPDF {
		public function Header(){
			global $master_pdf;
			$tbl_header     = "";
			$date_time 		= date('d-m-Y h:i:s a');
			$print_type     = 'SAMPLE ISSUE';

			$entry_no 				= $master_pdf[0]['entry_no'];
			$entry_date				= $master_pdf[0]['entry_date'];
			$pod				    = $master_pdf[0]['pod'];
			$ref_no				    = $master_pdf[0]['ref_no'];
			$customer_name			= $master_pdf[0]['customer_name'];
			$customer_address		= $master_pdf[0]['customer_address'];
			$courier_name			= $master_pdf[0]['courier_name'];
			$other_courier			= $master_pdf[0]['other_courier'];
			$notes			        = $master_pdf[0]['notes'];
			$this->SetFont('Helvetica', 'B', 8);
			$tbl_header .= <<<EOD
				<table border="0" cellpadding="3">
					<tr>
						<td width="20%"></td>
						<td width="60%" style="font-size:12px;" align="center"><b>$print_type</b></td>
						<td width="20%" ><b>$date_time</b></td>
					</tr>
				</table>				
EOD;
			$tbl_header .= <<<EOD
				<table border="0" cellpadding="3" >
					<tr>
						<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
							<b>ENTRY NO</b>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;$entry_no
						</td>
						<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000;">
							<b>ENTRY DATE</b>&nbsp;&nbsp;:&nbsp;$entry_date
						</td>
					</tr>
                    <tr>
						<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
							<b>CUSTOMER</b>&nbsp;&nbsp;:&nbsp;$customer_name
						</td>
						<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000;">
							<b>COURIER</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;$courier_name
						</td>
					</tr>
                    <tr>
						<td width="100%" height="35px" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
							<b>NARRATION</b>&nbsp;&nbsp;:&nbsp;$notes
						</td>
					</tr>
				</table>
				<table border="1" cellpadding="5" style="border-bottom: 1px dashed #000;">
					<tr>
						<th width="5%">#</th>
						<th width="20%">ITEM</th>
						<th width="8%">RATE</th>
						<th width="6%">UNIT</th>
						<th width="10%">WIDTH</th>
						<th width="10%">AVG. PACK</th>
						<th width="6%">STRIP</th>
						<th width="15%">SIZE</th>
						<th width="20%">NARRATION</th>
					</tr>
				</table>			
EOD;
			$this->writeHTMLCell(200, 280, 6, 6, $tbl_header, 1, 0, 0, true, 'P', true);
			// $this->SetTopMargin(3);	
			$yy = $this->GetY();
			$yy = $yy + 33;
			$this->SetTopMargin($yy + 0);
		}

		public function Footer(){
    		$tbl_footer 	= "";
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
	$pdf->SetTitle('SAMPLE ISSUE Pdf');
	$pdf->SetSubject('SAMPLE ISSUE Pdf');

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
	$pdf->SetAutoPageBreak(TRUE, 15);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) 
	{
	    require_once(dirname(__FILE__).'/lang/eng.php');
	    $pdf->setLanguageArray($l);
	}


	$pdf->SetFont('Helvetica', 'B', 8);
	$tbl = "";
	$pdf->AddPage('P');

	$tbl .= <<<EOD
			<table border="0" cellpadding="5">
EOD;
			foreach ($trans_pdf as $key => $value){
				$sr_no		    = $key+1;
				$item_name 	    = $value['item_name'];
				$rate		    = $value['rate'];
				$unit_name	    = $value['unit_name'];
				$width		    = $value['width'];
				$avg_pack	    = $value['avg_pack'];
				$strip		    = $value['strip'];
				$size_name	    = $value['size_name'];
				$notes	        = $value['notes'];
				$category_name	= $value['category_name'];
                
                if(!empty($category_name)):

                    $tbl .= <<<EOD
                        <tr >
                            <td width="5%"  >$sr_no</td>
                            <td width="20%" >$item_name</td>
                            <td width="8%"  >$rate</td>
                            <td width="6%"  >$unit_name</td>
                            <td width="10%" >$width</td>
                            <td width="10%" >$avg_pack</td>
                            <td width="6%"  >$strip</td>
                            <td width="15%" >$size_name</td>
                            <td width="20%" >$notes</td>
                        </tr>
EOD;
                    $tbl .= <<<EOD
                        <tr >
                            <td width="5%"style="border-bottom:1px dashed #000;"></td>
                            <td width="95%"style="border-bottom:1px dashed #000;" colspan="8">$category_name</td>
                        </tr>
EOD;
                else:
                    $tbl .= <<<EOD
                        <tr >
                            <td width="5%"  style="border-bottom:1px dashed #000;">$sr_no</td>
                            <td width="20%" style="border-bottom:1px dashed #000;">$item_name</td>
                            <td width="8%"  style="border-bottom:1px dashed #000;">$rate</td>
                            <td width="6%"  style="border-bottom:1px dashed #000;">$unit_name</td>
                            <td width="10%" style="border-bottom:1px dashed #000;">$width</td>
                            <td width="10%" style="border-bottom:1px dashed #000;">$avg_pack</td>
                            <td width="6%"  style="border-bottom:1px dashed #000;">$strip</td>
                            <td width="15%" style="border-bottom:1px dashed #000;">$size_name</td>
                            <td width="20%" style="border-bottom:1px dashed #000;">$notes</td>
                        </tr>
EOD;
                endif;
			}

	$tbl .= <<<EOD
			</table>			
EOD;
	$pdf->writeHTML($tbl, true, false, false, false, '');
	// $pdf->writeHTMLCell(188, 150, 6, 134, $tbl, 0, 0, 0, true, 'P', true);
	$pdf->IncludeJS("print();");
	// ---------------------------------------------------------

	//Close and output PDF document
	ob_end_clean();
	$pdf->Output('SAMPLE ISSUE.pdf', 'I');
	//============================================================+
	// END OF FILE
	//============================================================+
