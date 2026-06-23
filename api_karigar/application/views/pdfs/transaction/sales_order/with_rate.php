<?php $this->mypdf_class->tcpdf();
	global $master_pdf;
	global $trans_pdf;
	global $company_pdf;
	global $yy;

	$master_pdf = $master_data;
	$trans_pdf  = $trans_data;
	$company_pdf= $company_data;

	class MYPDF extends TCPDF 
	{
		public function Header()
		{
			global $master_pdf;
			global $company_pdf;
			$tbl_header 	= "";
			$title 			= strtoupper($company_pdf[0]['company_name']);
			$date_time 		= date('d-m-Y h:i:s a');
			$address 		= empty($company_pdf[0]['company_address']) ? '' : "Address : ".strtoupper($company_pdf[0]['company_address']);
			$city_name 		= empty($company_pdf[0]['city_name']) ? '' : ", ".strtoupper($company_pdf[0]['city_name']);
			$state_name 	= empty($company_pdf[0]['state_name']) ? '' : ", ".strtoupper($company_pdf[0]['state_name']);
			$country_name 	= empty($company_pdf[0]['country_name']) ? '' : ", ".strtoupper($company_pdf[0]['country_name']);
			$pincode 		= empty($company_pdf[0]['company_pincode']) ? '' : ", ".strtoupper($company_pdf[0]['company_pincode']);
			$mobile 		= empty($company_pdf[0]['company_mobile']) ? '' : "Mobile No. : ".strtoupper($company_pdf[0]['company_mobile']);
			$email 			= empty($company_pdf[0]['company_email']) ? '' : ", Email. : ".strtolower($company_pdf[0]['company_email']);
			$print_type 	= 'SALES ORDER';

			$entry_no 				= $master_pdf[0]['som_entry_no'];
			$entry_date				= date('d-m-Y', strtotime($master_pdf[0]['som_entry_date']));
			$customer_name			= $master_pdf[0]['customer_name'];
			$billing_address		= $master_pdf[0]['billing_address'];
			$billing_city_name 		= $master_pdf[0]['billing_city_name'];
			$billing_pincode 		= !empty($master_pdf[0]['billing_pincode']) ? ' - '.$master_pdf[0]['billing_pincode']: '';
			$billing_state_name		= $master_pdf[0]['billing_state_name'];
			$billing_country_name 	= $master_pdf[0]['billing_country_name'];
			$delivery_address		= $master_pdf[0]['delivery_address'];
			$delivery_city_name 	= $master_pdf[0]['delivery_city_name'];
			$delivery_pincode 		= !empty($master_pdf[0]['delivery_pincode']) ? ' - '.$master_pdf[0]['delivery_pincode']: '';
			$delivery_state_name	= $master_pdf[0]['delivery_state_name'];
			$delivery_country_name 	= $master_pdf[0]['delivery_country_name'];
			$disc_per 				= $master_pdf[0]['som_disc_per'];
			$transport_name			= $master_pdf[0]['transport_name'];
			$retailer_name			= $master_pdf[0]['retailer_name'];
			$credit_day 			= $master_pdf[0]['som_credit_day'];
			$ref_no 				= $master_pdf[0]['som_ref_no'];
			$executive_name			= $master_pdf[0]['executive_name'];
			$created_by				= $master_pdf[0]['user_fullname'];
			$remark					= $master_pdf[0]['som_remark'];
			$gst_no					= $master_pdf[0]['customer_gst_no'];
			$this->SetFont('Helvetica', 'B', 8);
			$tbl_header='<table border="0" cellpadding="3">
							<tr>
								<td width="80%" style="font-size:14px;" align="center"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$title.'</b></td>
								<td width="20%"style="font-size:12px;" ><b>'.$date_time.'</b></td>
							</tr>
							<tr>
								<td colspan="2" style="font-size:10px;" align="center"><b>'.$address.' '.$city_name.' '.$state_name.' '.$country_name.' '.$pincode.'</b></td>
							</tr>
							<tr>
								<td colspan="2" style="font-size:10px;" align="center"><b>'.$mobile.' '.$email.'</b></td>
							</tr>
							<tr>
								<td colspan="2" style="font-size:12px;" align="center"><b>'.$print_type.'</b></td>
							</tr>
						</table>
						<table border="0" cellpadding="3" >
							<tr>
								<td width="11%" height="30px" style="border-top:1px solid #000; border-bottom:1px solid #000;">
									<b>CUSTOMER</b>&nbsp;&nbsp;&nbsp;:&nbsp;
								</td>
								<td width="39%" height="30px" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
									'.$customer_name.'
								</td>
								<td width="25%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
									<b>ORDER NO</b>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$entry_no.'
								</td>
								<td width="25%" style="border-top:1px solid #000; border-bottom:1px solid #000;">
									<b>ORDER DATE</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$entry_date.'
								</td>
							</tr>
							<tr>
								<td width="50%" height="97px" style="border-bottom:1px solid #000; border-right:1px solid #000;">
									<table cellpadding="2">
										<tr>
											<td width="20%">
												<b>BILLING</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
											</td>
											<td width="80%">
												'.$billing_address.'<br/>
												'.$billing_city_name.' '.$billing_pincode.' <br/>
												'.$billing_state_name.', '.$billing_country_name.'
											</td>
										</tr>
									</table>
								</td>
								<td width="50%" height="97px" style="border-bottom:1px solid #000; border-right:1px solid #000;">
									<table cellpadding="2">
										<tr>
											<td width="21%">
												<b>DELIVERY</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
											</td>
											<td width="79%">
												'.$delivery_address.'<br/>
												'.$delivery_city_name.' '.$delivery_pincode.' <br/>
												'.$delivery_state_name.', '.$delivery_country_name.'
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
									<b>GST NO</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$gst_no.'
								</td>
								<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
									<b>RETAILER</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$retailer_name.'
								</td>
							</tr>
							<tr>
								<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
									<b>DISCOUNT</b>&nbsp;&nbsp;&nbsp;:&nbsp;'.$disc_per.' %
								</td>
								<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
									<b>TRANSPORT</b>&nbsp;:&nbsp;'.$transport_name.'
								</td>
							</tr>
							<tr>
								<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
									<b>CRE. DAYS</b>&nbsp;&nbsp;:&nbsp;'.$credit_day.'
								</td>
								<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
									<b>REF. NO</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$ref_no.'
								</td>
							</tr>
							<tr>
								<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
									<b>EXECUTIVE</b>&nbsp;&nbsp;:&nbsp;'.$created_by.'
								</td>
								<td width="50%" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
									<b>SALES EXE.</b>&nbsp;&nbsp;&nbsp;:&nbsp;'.$executive_name.'
								</td>
							</tr>
							<tr>
								<td width="10%" style="border-top:1px solid #000; border-bottom:1px solid #000;">
									<b>REMARK</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
								</td>
								<td width="90%" height="32px" style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
									'.$remark.'
								</td>
							</tr>
						</table>
						<table cellpadding="3" border="1">
							<tr>
								<th width="5%">#</th>
								<th width="30%">ITEM</th>
								<th width="30%">COLOR</th>
								<th width="10%">QTY</th>
								<th width="10%" align="right">RATE</th>
								<th width="15%" align="right">AMOUNT</th>
							</tr>
						</table>';
			$this->writeHTMLCell(200, 280, 6, 6, $tbl_header, 1, 0, 0, true, 'P', true);
			// $this->SetTopMargin(3);	
			$yy = $this->GetY();
			$yy = $yy + 94;
			// $this->line(18,$yy,18,280);
			// $this->line(82,$yy,82,280);
			// $this->line(146,$yy,146,280);
			// $this->line(166,$yy,166,280);
			// $this->line(186,$yy,186,280);
			$this->SetTopMargin($yy + 0);
		}

		public function Footer() {
    		$tbl_footer='';
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
	$pdf->SetTitle('Sales Order (With MRP) Pdf');
	$pdf->SetSubject('Sales Order (With MRP) Pdf');

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
	$pdf->SetAutoPageBreak(TRUE, 20.5);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) 
	{
	    require_once(dirname(__FILE__).'/lang/eng.php');
	    $pdf->setLanguageArray($l);
	}
	$disc_per = $master_pdf[0]['som_disc_per'];
	$disc_amt = $master_pdf[0]['som_disc_amt'];
	$final_amt= $master_pdf[0]['som_total_amt'];
	$pdf->SetFont('Helvetica', 'B', 9);
	$pdf->AddPage('P');
	$tbl = '<table border="0" cellpadding="5">';
				if(!empty($trans_pdf)){
					$total_mtr = 0;
					$total_amt = 0;
					foreach ($trans_pdf as $key => $value){
						$sr_no		= $key+1;
						$item_name 	= $value['item_name'];
						$ict_name	= $value['ict_name'];
						$mtr		= round($value['sot_mtr'], 2);
						$rate		= round($value['sot_rate'], 2);
						$amt		= round($value['sot_amt'], 2);
						$remark		= empty($value['sot_remark']) ? '' : 'Remark : '.$value['sot_remark'];
						$barcode    = '';
						$total_mtr  = $total_mtr + $mtr;
						$total_amt  = $total_amt + $amt;
						if(!empty($value['barcode_data'])){
							foreach ($value['barcode_data'] as $k => $v) {
								$barcode = empty($barcode) ? 'BARCODE <br/>'.$v['bm_roll_no'].' - '.$v['sobt_mtr'].' MTR.' : $barcode."<br/>".$v['bm_roll_no'].' - '.$v['sobt_mtr'].' MTR';
							}
						}
						if(!empty($remark) || !empty($barcode) || !empty($godown) || !empty($location)){
							$tbl.= '<tr >
										<td width="5%" 	>'.$sr_no.'</td>
										<td width="30%" style="border-left:1px solid #000;">'.$item_name.'</td>
										<td width="30%" style="border-left:1px solid #000;">'.$ict_name.'</td>
										<td width="10%" style="border-left:1px solid #000;">'.$mtr.' MTR.</td>
										<td width="10%" style="border-left:1px solid #000; text-align:right;">'.$rate.'</td>
										<td width="15%" style="border-left:1px solid #000; text-align:right;">'.$amt.'</td>
									</tr>
									<tr >
										<td width="5%"  style="border-bottom:1px dashed #000;"></td>
										<td width="30%" style="border-left:1px solid #000;border-bottom:1px dashed #000;">'.$remark.'</td>
										<td width="30%" style="border-left:1px solid #000;border-bottom:1px dashed #000;">'.$barcode.'</td>
										<td width="10%" style="border-left:1px solid #000;border-bottom:1px dashed #000;">'.$godown.'</td>
										<td width="10%" style="border-left:1px solid #000;border-bottom:1px dashed #000;">'.$location.'</td>
										<td width="15%" style="border-left:1px solid #000;border-bottom:1px dashed #000;"></td>
									</tr>';
						}else{
							$tbl.= '<tr >
										<td width="5%"  style="border-bottom:1px dashed #000;">'.$sr_no.'</td>
										<td width="30%" style="border-left:1px solid #000;border-bottom:1px dashed #000;">'.$item_name.'</td>
										<td width="30%" style="border-left:1px solid #000;border-bottom:1px dashed #000;">'.$ict_name.'</td>
										<td width="10%" style="border-left:1px solid #000;border-bottom:1px dashed #000;">'.$mtr.' MTR.</td>
										<td width="10%" style="border-left:1px solid #000;border-bottom:1px dashed #000; text-align:right;">'.$rate.'</td>
										<td width="15%" style="border-left:1px solid #000;border-bottom:1px dashed #000; text-align:right;">'.$amt.'</td>
									</tr>';
						}
					}
					$tbl .= '<tr>
								<td width="65%" style="border:1px solid #000; font-size:13px; text-align:right;">SUB TOTAL</td>
								<td width="10%" style="border:1px solid #000; font-size:13px;">'.$total_mtr.'</td>
								<td width="10%" style="border:1px solid #000; font-size:13px;"></td>
								<td width="15%" style="border:1px solid #000; font-size:13px; text-align:right;">'.$total_amt.'</td>
							</tr>
							<tr>
								<td width="75%" style="border:1px solid #000; font-size:13px; text-align:right;">DISCOUNT</td>
								<td width="10%" style="border:1px solid #000; font-size:13px;">'.$disc_per.' %</td>
								<td width="15%" style="border:1px solid #000; font-size:13px; text-align:right;">'.$disc_amt.'</td>
							</tr>
							<tr>
								<td width="75%" style="border:1px solid #000; font-size:13px; text-align:right;">TOTAL</td>
								<td width="10%" style="border:1px solid #000; font-size:13px;"></td>
								<td width="15%" style="border:1px solid #000; font-size:13px; text-align:right;">'.$final_amt.'</td>
							</tr>';
				}else{
					$tbl.= '<tr align="center"><td width="100%" style="border-bottom:1px solid #000;" colspan="6">NO PENDING ORDER</td></tr>';
				}
	$tbl.='</table>';
	$pdf->writeHTML($tbl, true, false, false, false, '');
	// $pdf->writeHTMLCell(188, 150, 6, 134, $tbl, 0, 0, 0, true, 'P', true);
	$pdf->IncludeJS("print();");
	// ---------------------------------------------------------

	//Close and output PDF document
	ob_end_clean();
	$pdf->Output('Sales Order.pdf', 'I');
	//============================================================+
	// END OF FILE
	//============================================================+
