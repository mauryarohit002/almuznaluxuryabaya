<?php
// Include the main TCPDF library (search for installation path).
$this->mypdf_class->tcpdf(); 
global $master_pdf;
global $order_pdf; 
global $yy;
$master_pdf     = $master_data; 
$order_pdf     	= $order_data;
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF  
{
    //Page header
    public function Header()  
    {
        global $master_pdf;
        global $order_pdf;
        
        $this->SetFont('times', '', 12,false);

        $tbl_header = '<table border="1" cellpadding="4"> 
                        <tr>
                        	<td width="100%" align="center;"><b>RECEIPT VOUCHER</b></td>
                        </tr>
                        <tr>
                            <td width="60%" ><b>Entry NO : </b>'.$master_pdf[0]['entry_no'].'</td>
                            <td width="40%" align="right"><b>Entry Date : </b> '.$master_pdf[0]['entry_date'].'</td>
                        </tr>
                        <tr>
                            <td width="60%" ><b>Order Amt : </b>'.$master_pdf[0]['receipt_order_amt'].'</td>
                            <td width="40%" align="right"><b>Receipt Amt : </b> '.$master_pdf[0]['receipt_amt'].'</td>
                        </tr>
                        <tr>
                            <td width="100%" ><b>Customer : </b>'.$master_pdf[0]['customer_name'].'</td>
                        </tr>
                    </table>
                  ';

        $this->writeHTML($tbl_header, true, false, false, false, '');
        $yy = $this->GetY(); 
        $yy = $yy - 6;
        $this->SetTopMargin($yy + 1);
    }  

    // Page footer
    public function Footer() 
    {
       
               $this->SetFont('helvetica', 'I', 8);
               // $tbl_footer ='
               //          <table  border="1"  cellpadding="3">
                          
               //              <tr>
               //                  <td width="50%" align="center"><b></b><br><br><br><br>RECEIVER SIGN</td>
               //                  <td width="50%" align="center"><b></b><br><br><br><br>CHECKED BY</td>
               //              </tr>
               //          </table>';
               //      $this->writeHTMLCell(0, 0, 6, 55, $tbl_footer, 0, 0, 0, true, 'L', true);
                    // Set font

                    $this->Cell(0, 53, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
               
            }

}

$pdf = new MYPDF("L", PDF_UNIT, array('148','210'), true, 'UTF-8', false); 
$file_name = 'Receipt.pdf';
$file_path = 'Receipt.pdf';
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('RECEIPT INVOICE Pdf');
$pdf->SetSubject('RECEIPT INVOICE Pdf');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(5, PDF_MARGIN_TOP, 5,true);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(20);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 25);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}


$pdf->SetFont('times', '', 9,false);
$body = "";

$pdf->AddPage();

$body .= '<table cellpadding="3" >
			<tr>
				<td>
					<table border="1" cellpadding="3">
						<tr>
							<td width="100%" align="center"><b>ORDER</b> </td>
						</tr>
						<tr style="font-size:10px">
							<td width="20%" align="center"><b>ENTRY NO</b></td>
							<td  width="20%" align="center"><b>ENTRY DATE</b></td>
							<td  width="20%" align="center"><b>BALANCE AMT</b></td>
							<td  width="20%" align="center"><b>ADJUST AMT</b></td>
							<td  width="20%" align="center"><b>PENDING AMT</b></td>
						</tr>
					';
						if(!empty($order_pdf)):
						 foreach($order_pdf as $key => $value) :
						 	$pending = $value['rot_total_amt'] - $value['rot_adjust_amt'];
			                $body .= '<tr style="font-size:12px">
			                            <td width="20%" align="center">'.$value['rot_entry_no'].'</td>
			                            <td width="20%" align="center">'.$value['rot_entry_date'].'</td>
			                            <td width="20%" align="center">'.$value['rot_total_amt'].'</td>
			                            <td width="20%" align="center">'.$value['rot_adjust_amt'].'</td>
			                            <td width="20%" align="center">'.$pending.'</td>
			                        </tr>'; 
			            endforeach;
                             $body .= ' <tr>
                                        <td width="50%" align="center"><b></b><br><br><br>RECEIVER SIGN</td>
                                        <td width="50%" align="center"><b></b><br><br><br>CHECKED BY</td>
                                    </tr>'; 
			            else:
			            	$body .= '<tr style="font-size:10px">
			            				<td width="100%" align="center" style="color:red">No Record Found!</td>
			            			</tr>';
			            endif;
				$body .= '</table>
				</td>
				
			</tr>		
					'; 
           
$body .= '</table>'; 


$pdf->writeHTML($body, true, false, false, false, '');

//Close and output PDF document
if(true)
{

    $pdf->Output($file_path, 'I');
}


//============================================================+
// END OF FILE
//============================================================+