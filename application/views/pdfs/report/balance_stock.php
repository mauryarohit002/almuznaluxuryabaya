<?php	
error_reporting(0);
$this->mypdf_class->tcpdf();
$obj_pdf = new TCPDF('L', PDF_UNIT, array('190','250'), true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);
$title = "BALANCE STOCK";
$file_name = "BALANCE STOCK";
$obj_pdf->SetTitle($title);
$obj_pdf->SetDefaultMonospacedFont('helvetica');
$obj_pdf->SetAutoPageBreak(TRUE, 1);
$obj_pdf->setFontSubsetting(true);
	
$obj_pdf->SetPrintHeader(false);
$obj_pdf->SetPrintFooter(false);
$obj_pdf->SetTopMargin(5);
$obj_pdf->SetLeftMargin(5); //
$obj_pdf->SetRightMargin(5);

$obj_pdf->AddPage();
$obj_pdf->SetFont('Helvetica', 'S', 8);
$tbl = "";
$branch 			= strtoupper($_SESSION['user_branch']);
$supplier_name 		= isset($_GET['brmm_supplier_id']) && !empty($_GET['brmm_supplier_id']) ? $data['data'][0]['supplier_name'] : '';
$branch_name 		= isset($_GET['brmm_branch_id']) && !empty($_GET['brmm_branch_id']) ? $data['data'][0]['branch_name'] : '';
$sku_name 			= isset($_GET['brmm_sku_id']) && !empty($_GET['brmm_sku_id']) ? $data['data'][0]['sku_name'] : '';
$apparel_name 		= isset($_GET['brmm_apparel_id']) && !empty($_GET['brmm_apparel_id']) ? $data['data'][0]['apparel_name'] : '';
$pt_amt_frm 		= (isset($_GET['pt_amt_frm'])) ? $_GET['pt_amt_frm'] : "";
$pt_amt_to 			= (isset($_GET['pt_amt_to']) && $_GET['pt_amt_to'] != '') ? ' TO '.$_GET['pt_amt_to'] : "";
$ot_amt_frm 		= (isset($_GET['ot_amt_frm'])) ? $_GET['ot_amt_frm'] : "";
$ot_amt_to 			= (isset($_GET['ot_amt_to']) && $_GET['ot_amt_to'] != '') ? ' TO '.$_GET['ot_amt_to'] : "";
$sold_amt_frm 		= (isset($_GET['sold_amt_frm'])) ? $_GET['sold_amt_frm'] : "";
$sold_amt_to 		= (isset($_GET['sold_amt_to']) && $_GET['sold_amt_to'] != '') ? ' TO '.$_GET['sold_amt_to'] : "";
$bal_qty_frm 		= (isset($_GET['bal_qty_frm'])) ? $_GET['bal_qty_frm'] : 1;
$bal_qty_to 		= (isset($_GET['bal_qty_to']) && $_GET['bal_qty_to'] != '') ? ' TO '.$_GET['bal_qty_to'] : "";
$bal_amt_frm 		= (isset($_GET['bal_amt_frm'])) ? $_GET['bal_amt_frm'] : "";
$bal_amt_to 		= (isset($_GET['bal_amt_to']) && $_GET['bal_amt_to'] != '') ? ' TO '.$_GET['bal_amt_to'] : "";
$tbl .= <<<EOD
	<br pagebreak="true">
	<table cellpadding="2">
		<tr>
			<td align="center" style="font-size:10px;"><b>BALANCE STOCK</b> (<span style="font-size:10px;">$branch</span>)</td>
		</tr>		
		<tr>
			<td>
				<table border="1" style="font-size:10px;">
					<tr>
						<td width="40%">SUPPLIER : $supplier_name</td>
						<td width="30%">SKU : $sku_name</td>
						<td width="30%">APPAREL : $apparel_name</td>
					</tr>
					<tr>
						<td width="60%">BRANCH : $branch_name</td>
						<td width="20%">PUR AMT : $pt_amt_frm $pt_amt_to</td>
						<td width="20%">SALE AMT : $ot_amt_frm $ot_amt_to</td>
					</tr>
					<tr>
						<td width="20%">SOLD AMT : $sold_amt_frm $sold_amt_to</td>
						<td width="20%">BAL QTY : $bal_qty_frm $bal_qty_to</td>
						<td width="60%">BAL AMT : $bal_amt_frm $bal_amt_to</td>
					</tr>
				</table>		
			</td>
		</tr>		
		<tr>
			<td>
				<table border="1" style="font-size:9px;">
					<tr style="font-weight:bold;background-color:#f2f2f2;">
						<th width="10%">SUPPLIER</th>
						<th width="7%">BRANCH</th>
						<th width="8%">SKU</th>
						<th width="8%">APPAREL</th>
						<th width="10%">BARCODE</th>

						<th width="4%">PUR QTY</th>
						<th width="4%">PUR RT</th>
						<th width="5%">PUR AMT</th>

						<th width="4%">PUR RET</th>
						<th width="4%">OUT</th>
						<th width="4%">IN</th>

						<th width="4%">SALE QTY</th>
						<th width="4%">SALE RT</th>
						<th width="5%">SALE AMT</th>

						<th width="4%">SALE RET</th>
						<th width="7%">SOLD AMT</th>
						<th width="4%">BAL QTY</th>
						<th width="4%">STOCK AMT</th>
					</tr>

EOD;
					if(!empty($data['data'])):
						$sr_no = 1;
						foreach ($data['data'] as $key => $value):
							$supplier_name 	= $value['supplier_name'];
							$branch_name 	= $value['branch_name'];
							$sku_name 		= $value['sku_name'];
							$apparel_name	= $value['apparel_name'];
							$barcode		= $value['barcode'];
							$pt_qty 		= $value['pt_qty'];
							$pt_rate 		= round($value['pt_rate'], 2);
							$pt_amt 		= round($value['pt_amt'],2);
							$prt_qty 		= $value['prt_qty'];
							$outward_qty 	= $value['outward_qty'];
							$inward_qty 	= $value['inward_qty'];
							$ot_qty 		= $value['ot_qty'];
							$ot_rate 		= round($value['ot_rate'], 2);
							$ot_amt 		= round($value['ot_amt'], 2);
							$ort_qty 		= $value['ort_qty'];
							$sold_amt 		= round($value['sold_amt'], 2);
							$bal_qty 		= $value['bal_qty'];
							$bal_amt 		= round($value['bal_amt'], 2);

							$tbl .= <<<EOD
							<tr>
								<td >$supplier_name</td>
								<td >$branch_name</td>
								<td >$sku_name</td>
								<td >$apparel_name</td>
								<td >$barcode</td>
								<td >$brand_name</td>
								<td >$pt_qty</td>
								<td >$pt_rate</td>
								<td >$pt_amt</td>
								<td >$prt_qty</td>
								<td >$outward_qty</td>
								<td >$inward_qty</td>
								<td >$ot_qty</td>
								<td >$ot_rate</td>
								<td >$ot_amt</td>
								<td >$ort_qty</td>
								<td >$sold_amt</td>
								<td >$bal_qty</td>
								<td >$bal_amt</td>
							</tr>
EOD;
						$sr_no++;
						endforeach;
					endif;
$pt_qty = $data['totals']['pt_qty'];
$pt_amt = round($data['totals']['pt_amt'], 2);
$prt_qty= $data['totals']['prt_qty'];
$outward_qty= $data['totals']['outward_qty'];
$inward_qty= $data['totals']['inward_qty'];
$ot_qty = $data['totals']['ot_qty'];
$ot_amt = round($data['totals']['ot_amt'], 2);
$ort_qty= $data['totals']['ort_qty'];
$sold_amt= round($data['totals']['sold_amt'], 2);
$bal_qty= $data['totals']['bal_qty'];
$bal_amt= round($data['totals']['bal_amt'], 2);
$tbl .= <<<EOD
							<tr style="font-weight:bold;">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>TOTALS</td>
								<td>$pt_qty</td>
								<td></td>
								<td>$pt_amt</td>
								<td>$prt_qty</td>
								<td>$outward_qtyy</td>
								<td>$inward_qty</td>
								<td>$ot_qty</td>
								<td></td>
								<td>$ot_amt</td>
								<td>$ort_qty</td>
								<td>$sold_amt</td>
								<td>$bal_qty</td>
								<td>$bal_amt</td>
							</tr>
				</table>		
			</td>
		</tr>		
	</table>
EOD;

$obj_pdf->writeHTML($tbl, true, false, false, false, '');
$height = $obj_pdf->getY();
$obj_pdf->deletePage(1);
$obj_pdf->setPage($obj_pdf->getPage()); 
$obj_pdf->Output($file_name, 'I');
?>