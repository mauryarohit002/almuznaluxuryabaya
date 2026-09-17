<?php
error_reporting(0);
$this->mypdf_class->tcpdf();
global $company_pdf;
global $master_pdf;
global $branch_pdf;
global $yy;
$company_pdf    = $company_data;
$master_pdf     = $master_data;
$branch_pdf  = $branch_data; 

$path           = isset($master_pdf[0]['file']) ? $master_pdf[0]['file'] : '';
class MYPDF extends TCPDF {
    public function Header() { 
        global $company_pdf;
        global $master_pdf;
        
        $logo = base_url('public/assets/dist/images/logo.jpeg');
        $this->Image($logo,30, 10, 45, 12, 'JPEG');   
        $header='
                <table>
                    <tr>
                        <td style="text-align:center"><b>ORDER RETURN BILL</b></td>
                    </tr>
                </table>
                <table border="1" cellpadding="3" >
                    <tr>
                        <td width="50%" style="height: 120px; font-size:11px">
                            <br/><br/><br/><br/><br/><br/>
                            <table>
                                <tr>
                                    <td width="100%" style="text-align:center">'.$company_pdf[0]['address'].' '.$company_pdf[0]['city_name'].' - '.$company_pdf[0]['pincode'].'</td>
                                </tr>
                                <tr> 
                                    <td width="50%"><b>GSTIN : </b>'.$company_pdf[0]['gst_no'].'</td>
                                    <td width="50%"><b>POS : </b>'.$company_pdf[0]['state_name'].'</td>
                                </tr>
                            </table>
                        </td>
                        <td width="50%">
                            <table>
                                <tr>
                                    <td><b>Customer : </b>'.$master_pdf[0]['customer_name'].'</td>
                                </tr>
                                <tr> 
                                    <td style="height:50px"><b>Address : </b>'.$master_pdf[0]['customer_address'].'</td>
                                </tr>
                                 <tr> 
                                    <td><b>Mobile : </b>'.$master_pdf[0]['customer_mobile'].'</td>
                                </tr>
                                 <tr> 
                                    <td><b>GSTIN : </b>'.$master_pdf[0]['customer_gst_no'].'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <table>
                                <tr>
                                    <td><b>ENTRY No : </b>'.$master_pdf[0]['entry_no'].'</td>
                                     <td><b>ENTRY DATE : </b>'.$master_pdf[0]['entry_date'].'</td>
                                </tr>
                            </table>
                        </td>
                         <td width="50%">
                            <table>
                                <tr>
                                    <td><b>GST NO : </b>'.$master_pdf[0]['gst_no'].'</td>
                                     <td><b>State : </b>'.$master_pdf[0]['state_name'].'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                 <table cellpadding="5" border="1" style="font-size: 11px; font-weight:bold;">
                    <tr>
                        <td width="4%">SR.</td>
                        <td width="30%">DESCRIPTION OF GOODS</td>
                        <td width="10%" align="center">HSN/SM ID</td>
                        <td width="7%" align="center">QTY</td> 
                        <td width="11%" align="center">RATE</td>
                        <td width="10%" align="center">DISC AMT</td>
                        <td width="13%" align="center">TAXABLE</td>
                        <td width="15%" align="center">TOTAL</td>
                    </tr>
                 </table>';

        $this->writeHTML($header, true, false, false, false, '');
        $yy = $this->GetY();
        $yy = $yy - 10;
        $this->line(5,$yy,5,230);
        $this->line(13,$yy,13,230);
        $this->line(73,$yy,73,230);           
        $this->line(93,$yy,93,230);           
        $this->line(107,$yy,107,230);           
        $this->line(129,$yy,129,230);           
        $this->line(149,$yy,149,230);           
        $this->line(175,$yy,175,230);   
        $this->line(205,$yy,205,230);     
        $this->SetTopMargin($yy + 3);
    }
    public function Footer() {
    global $company_pdf;
    global $master_pdf;
    global $branch_pdf;

    $this->SetY(-70); 
    $total_gst = $master_pdf[0]['sgst_amt'] + $master_pdf[0]['cgst_amt'] + $master_pdf[0]['igst_amt'];

    $word_number = number_to_word($master_pdf[0]['total_amt']);

    $footer ='
        <table cellpadding="3" border="1" style="font-size:10px;">       
            <tr style="font-weight:bold; background-color:#f0f0f0;">
                    <td width="44%" align="right"><b>TOTAL</b></td>
                    <td width="7%" align="center">'.$master_pdf[0]['total_qty'].'</td>
                    <td width="11%"></td>
                    <td width="10%" align="center">'.$master_pdf[0]['disc_amt'].'</td>
                    <td width="13%"></td>
                    <td width="15%" align="center">'.$master_pdf[0]['total_amt'].'</td>
            </tr>
        </table>';

    $footer .= '
        <table border="1" style="font-size:11px;">
            <tr>
                <td width="50%">
                    <table cellpadding="4" border="1">
                        <tr>
                            <td style="height:50px"><b><span style="font-family:dejavusans;">&#8377;</span> (In Words)</b> : '.$word_number.'
                            </td>
                        </tr>
                    </table> 
                    <br/><br/>
                    ';

                $footer .= '
                            <table cellpadding="5" style="line-height:.9">
                               <tr>
                                    <td>Firm Name: <b>'.strtoupper($branch_pdf[0]['branch_bank_firm']).'</b></td>
                               </tr>
                                <tr>
                                    <td>Bank Name: <b>'.strtoupper($branch_pdf[0]['branch_bank_name']).'</b></td>
                               </tr>
                               <tr>
                                    <td>A/C No: <b>'.strtoupper($branch_pdf[0]['branch_acc']).'</b></td>
                               </tr> 
                               <tr>
                                    <td>IFSC CODE : <b>'.strtoupper($branch_pdf[0]['branch_ifsc']).'</b></td>
                               </tr>   
                            </table>           
                </td>
                <td width="50%">
                    <table border="1" cellpadding="5" style="font-size:10px;">
                        <tr>
                            <td width="60%" align="right"><b>DISCOUNT AMOUNT</b></td>
                            <td width="40%" align="right">'.number_format($master_pdf[0]['disc_amt'],2).'</td>
                        </tr>
                        <tr>
                            <td width="60%" align="right"><b>TAXABLE VALUE</b></td>
                            <td width="40%" align="right">'.number_format($master_pdf[0]['taxable_amt'],2).'</td>
                        </tr>
                        <tr>
                            <td width="60%" align="right"><b>TOTAL GST</b></td>
                            <td width="40%" align="right">'.number_format($total_gst,2).'</td>
                        </tr>
                        <tr>
                            <td width="60%" align="right" style="background-color:#ccc;"><b>GRAND TOTAL</b></td>
                            <td width="40%" align="right" style="background-color:#ccc;"><b> '.number_format($master_pdf[0]['total_amt'],2).'</b></td>
                        </tr>
                        
                    </table>
                    <table border="1" cellpadding="4" style="font-size:10px;">
                        <tr>
                            <td width="50%" align="center"><b>FOR '.$company_pdf[0]['company_name'].'</b></td>
                            <td width="50%" align="center"><b>Receiver\'s Signature</b></td>
                        </tr>
                        <tr>
                            <td align="center" height="25"><br/><br/><br/><br/>Authorised Signatory</td>
                            <td align="center"></td>
                        </tr>
                    </table>
                </td>
            </tr>
         </table>';

    $footer .= '';

    $this->writeHTML($footer, true, false, false, false, '');
}
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('ORDER RETURN-INVOICE');
$pdf->SetSubject('ORDER RETURN-INVOICE');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(5, 3, 5); // Increased top margin to 35mm
$pdf->SetHeaderMargin(3);
$pdf->SetFooterMargin(25); // Reduced footer margin to 25mm
$pdf->SetAutoPageBreak(TRUE, 30); // Set auto page break with bottom margin 30mm
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->AddPage();
$pdf->SetFont('freesans', '', 10);

$body = '<table cellpadding="5" border="1" style="font-size:9px;">';
if(!empty($trans_data)){
    foreach ($trans_data as $key => $value) { 
            if($value['trans_type']=='READYMADE'){
                $item_name = $value['sku_name'];
            }else if($value['trans_type']=='SKU'){
                $item_name = $value['sku_name'] .' '.$value['apparel_name'];
            }else{
                 $item_name = $value['apparel_name'];
            }
        $cnt = $key+1;
        $body.='<tr>
                    <td width="4%">'.$cnt.'.</td>
                    <td width="30%">'.$item_name.'</td>
                    <td width="10%" align="center">'.$value['hsn_name'].'</td> 
                    <td width="7%" align="center">'.$value['qty'].'</td>   
                    <td width="11%" align="center">'.round($value['rate']).'</td>
                    <td width="10%" align="center">'.round($value['disc_amt']).'</td>
                    <td width="13%" align="center">'.$value['taxable_amt'].'</td>
                    <td width="15%" align="center">'.round($value['total_amt']).'</td>
                </tr>'; 
    }
  
    
}
$body.='</table>';

$pdf->writeHTML($body, true, false, false, false, '');
$pdf->IncludeJS("print();");

if(empty($path)){
    $pdf->Output('ORDER RETURN-INVOICE-'.$master_pdf[0]['entry_no'].'.pdf', 'I');
}else{
    $pdf->Output($path, 'F');
}
?>