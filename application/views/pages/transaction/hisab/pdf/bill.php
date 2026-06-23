<?php
$this->mypdf_class->tcpdf();
global $company_pdf;
global $master_pdf;
global $yy;

$company_pdf    = $company_data;
$master_pdf     = $master_data;

class MYPDF extends TCPDF {
    public function Header() {
        global $company_pdf;
        global $master_pdf ;
        $header = '<table border="0"  cellpadding="3">
                        <tr>
                            <td width="10%" style="padding-top:5px; margin-top:10px; text-align:left">&nbsp;&nbsp;&nbsp;<u><b></b></u></td>  
                            <td width="47%" colspan="1" style="text-align:right; width: 100%;"></td>
                            <td width="28%" style="font-size:14px;"><div style="color:#777;border:1px solid #777;font-weight:normal;">ORIGINAL FOR RECIPIENT </div></td>
                            <td width="15%" style="font-size:14px;text-align:right;font-weight:normal;">ESTIMATE </td>
                        </tr>
                        <tr style="text-align:left">
                            <td width="10%" style="font-size:8.3px;"></td>
                            <td width="80%" align="center">
                                <span style="font-size:24px;font-weight:normal;"><b>'.$company_pdf[0]['company_name'].'</b></span><br>
                                <span style="font-size:11px;font-weight:normal;">'.$company_pdf[0]['address'].' </span><br>
                                <span style="font-size:12px;font-weight:normal;">'.$company_pdf[0]['city_name'].' - '.$company_pdf[0]['pincode'].' '.$company_pdf[0]['state_name'].'</span><br>
                                <span style="font-size:12px;font-weight:normal;"><b>GSTIN :-</b> '.$company_pdf[0]['gstin'].'<br/></span>
                                <span style="font-size:12px;font-weight:normal;"><b>Mobile: -</b> '.$company_pdf[0]['mobile'].' / <b>Email :-</b> '.$company_pdf[0]['email'].'</span>
                            </td>
                            <td width="10%" style="font-size:8.3px;"></td>
                        </tr> 
                    </table>
                    <table border="0" cellpadding="6" style="font-size:12px;border-top: 7px solid #000;">
                        <tr style="background-color:#eee;font-weight:normal;font-size:13px;">
                            <td style="width:15%">Estimate No : </td>
                            <td style="width:20%; text-align:left">'.$master_pdf[0]['entry_no'].'</td>
                            <td style="width:35%; text-align:right"></td>
                            <td style="width:15%; text-align:right">Estimate Date : </td>
                            <td style="width:15%; text-align:right">'.$master_pdf[0]['entry_date'].'</td>
                        </tr>
                        <tr>
                            <td width="60%">
                                <span style="font-size:12px;">BILL TO</span><br>
                                <span style="font-size:11px;font-weight:normal;">'.$master_pdf[0]['customer_name'].'</span><br>
                                <span style="font-size:11px;font-weight:normal;">Mobile : '.$master_pdf[0]['customer_mobile'].'</span><br>
                                <span style="font-size:11px;font-weight:normal;">GST No. : '.$master_pdf[0]['customer_gst_no'].'</span><br>
                                <span style="font-size:11px;font-weight:normal;">'.$master_pdf[0]['customer_address'].' '.$master_pdf[0]['city_name'].' '.$master_pdf[0]['pincode'].' '.$master_pdf[0]['state_name'].'</span><br>
                            </td>
                            <td width="40%" align="right">
                                <span style="font-size:12px;">SHIP TO</span><br>
                                <span style="font-size:11px;font-weight:normal;">'.$master_pdf[0]['em_customer_name'].'</span><br>
                                <span style="font-size:11px;font-weight:normal;">'.$master_pdf[0]['em_customer_mobile'].'</span><br>
                            </td>
                        </tr>
                    </table>
                    <table  border="0" cellpadding="8" style="border-top: 4px solid #000;border-bottom: 4px solid #eee;">
                        <tr style="font-size:14px;">
                            <th style="width:25%;"><b>ITEMS</b></th>
                            <th style="width:15%;text-align:center;"><b>QTY</b></th>
                            <th style="width:15%;text-align:center;"><b>MTR</b></th>
                            <th style="width:15%;text-align:center;"><b>RATE</b></th>
                            <th style="width:15%;text-align:right;"><b>DISC</b></th>
                            <th style="width:15%;text-align:right;"><b>AMOUNT</b></th>
                        </tr>
                    </table>';
        $this->SetFont('freesans', 'B', 12);
        $this->writeHTML($header, true, false, false, false, '');
        $yy = $this->GetY();
        $this->SetTopMargin($yy + 0);
    }
    public function Footer() {
        global $company_pdf ;
        global $master_pdf ;
        global $trans_pdf ;

        $footer ='<table  border="0" cellpadding="4" style="border-bottom: 4px solid #000;border-top: 4px solid #eee;">
                    <tr style="font-size:14px;">
                        <th style="width:25%;"><b>TOTAL</b></th>
                        <th style="width:15%;text-align:center;"><b>'.$master_pdf[0]['total_qty'].'</b></th>
                        <th style="width:15%;text-align:center;"><b>'.$master_pdf[0]['total_mtr'].'</b></th>
                        <th style="width:15%;text-align:center;"><b></b></th>
                        <th style="width:15%;text-align:right;"><b>'.$master_pdf[0]['disc_amt'].'</b></th>
                        <th style="width:15%;text-align:right;"><b>'.$master_pdf[0]['net_amt'].'</b></th>
                    </tr>
                </table>
                <table border="0" style="font-size:13px;font-weight:normal" cellpadding="2">
                    <br><br>
                    <tr>
                        <td colspan="3" rowspan="10" width="40%" style="font-size:12px;font-weight:normal;line-height: 1.6;">
                            <span style="font-size:12px;font-weight:normal;line-height: 1.6;">
                                <br/>
                                <b>BANK DETAILS :</b> <br/> 
                                Name :<br/>
                                IFSC Code :<br/>
                                Bank :  
                            </span>
                            <br/> <br/> <br/> <br/> 
                            <b>TERMS AND CONDITIONS:</b><br/> 
                            1. No delivery on weekends & public holidays.<br/> 
                            2. Any alterations should be informed within 7 days of delivery.<br/> 
                        </td>
                        <td rowspan="10" width="25%" style="font-size:12px;font-weight:normal; text-align: center;">';
                            if($master_pdf[0]['advance_amt'] > 0):
                                $footer .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                                            <b>PAYMENT MODE :</b><br/>
                                            '.$master_pdf[0]['payment_mode'].'';
                            endif;    
                        $footer .='</td>
                        <td width="20%"></td>
                        <td width="15%" align="right"></td>
                    </tr>';
                    if($master_pdf[0]['bill_disc_amt'] > 0):
                        $footer .= '<tr>
                                        <td>DISC @ '.$master_pdf[0]['bill_disc_per'].' %</td>
                                        <td align="right">'.$master_pdf[0]['bill_disc_amt'].'</td>
                                    </tr>';
                    endif;
                    if($master_pdf[0]['round_off'] > 0):
                        $footer .= '<tr>
                                        <td>ROUND OFF</td>
                                        <td align="right">'.$master_pdf[0]['round_off'].'</td>
                                    </tr>';
                    endif;
                    $footer .= '<tr >
                                    <br>
                                    <td style="border-top: 1px solid #000;line-height: 1.6;"><b>TOTAL</b></td>
                                    <td align="right"  style="border-top: 1px solid #000;line-height: 1.6;"><b>'.$master_pdf[0]['total_amt'].'</b></td>
                                </tr>
                                <tr>
                                    <td style="border-top: 1px solid #000;line-height: 1.6;">Received Amount </td>
                                    <td style="border-top: 1px solid #000;line-height: 1.6;" align="right">'.$master_pdf[0]['advance_amt'].'</td>
                                </tr>
                                <tr>
                                    <td style="border-top: 1px solid #000;line-height: 1.6;"><b>Balance</b></td>
                                    <td style="border-top: 1px solid #000;line-height: 1.6;" align="right"><b>'.$master_pdf[0]['balance_amt'].'</b></td>
                                </tr>
                                <tr>
                                    <td align="right" colspan="2"><b>Estimate Amount (in words)</b><br>'.number_to_word($master_pdf[0]['balance_amt']).'</td>
                                </tr>
                </table>';
        $this->SetFont('freesans', 'B', 12);
        $this->writeHTMLCell(200, 100, 5, 210, $footer, 0, 0, 0, true, 'P', true);
    }
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('ESTIMATE');
$pdf->SetSubject('ESTIMATE');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(5, PDF_MARGIN_TOP, 7);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(5);
$pdf->SetAutoPageBreak(TRUE, 90);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdf->AddPage();
$pdf->SetFont('freesans', '', 8);
$body = '<br/><br/><br/><br/><br/>
        <table  border="0" cellpadding="8">';
            foreach($trans_data as $key => $value) :
                $apparel_fabric = (!empty($value['apparel_name']) && !empty($value['fabric_name'])) ? $value['apparel_name'].' - '.$value['fabric_name'].' - '.$value['design_name'] : (empty($value['apparel_name']) ? $value['fabric_name'].' - '.$value['design_name'] : $value['apparel_name']);
                $body .= '<tr style="font-size:12px;">
                            <td style="width:25%;border-bottom-color:#ccc;">'.$apparel_fabric.'</td>
                            <td style="width:15%;text-align:center;border-bottom-color:#ccc;">'.$value['qty'].'</td>
                            <td style="width:15%;text-align:center;border-bottom-color:#ccc;">'.$value['mtr'].'</td>
                            <td style="width:15%;text-align:center;border-bottom-color:#ccc;">'.$value['rate'].'</td>
                            <td style="width:15%;text-align:right;border-bottom-color:#ccc;">'.$value['disc_amt'].' <br/> <span style="font-size:9px;">('.$value['disc_per'].' %) </span></td>
                            <td style="width:15%;text-align:right;border-bottom-color:#ccc;">'.$value['total_amt'].'</td>
                        </tr>';
            endforeach;
$body .= '</table>';
$pdf->writeHTMLCell(200, 106, 4, 78, $body, 0, 0, 0, true, 'P', true);
$pdf->Output('ESTIMATE-PRINT.pdf', 'I');