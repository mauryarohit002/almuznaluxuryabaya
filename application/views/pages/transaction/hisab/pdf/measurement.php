<?php
$this->mypdf_class->tcpdf();
class MYPDF extends TCPDF {
    public function Header() {}
    public function Footer() {}
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('MEASUREMENT SLIP');
$pdf->SetSubject('MEASUREMENT SLIP');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(5, 0, 7);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(74);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdf->AddPage();
$pdf->SetFont('freesans', 'B', 9);
$body = '';
foreach ($trans_data as $key => $value):
    $body .= '
                <table border="0"  cellpadding="3">
                    <tr>
                        <td width="100%" style="font-size:14px;text-align:center;font-weight:normal;">ESTIMATE MEASUREMENT SLIP </td>
                    </tr>
                </table>
                <table border="0" cellpadding="6" style="font-size:12px;border-top: 7px solid #000; border-bottom: 7px solid #000;">
                    <tr style="background-color:#eee;font-weight:normal;font-size:13px;">
                        <td style="width:33%">No : '.$value['entry_no'].'</td>
                        <td style="width:34%" align="center">'.$value['apparel_name'].'</td>
                        <td style="width:33%; text-align:right">Date : '.$value['entry_date'].'</td>
                    </tr>
                </table>
                <br/>
                <br/>
                <table border="0" cellpadding="5">
                    <tr>';
                            if(empty($value['measurement_data'])):
                                $body .= '<td style="width: 100%; text-align: center; color: red;">NO MEASUREMENT ADDED !!!</td>';
                            else:
                                $cnt = 0;
                                foreach ($value['measurement_data'] as $k => $v):
                                    $cnt++;
                                    if($cnt == 8):
                                        $body .= '</tr><tr>';
                                        $cnt = 0;
                                    endif;
                                    $body .= '<td style="width: 14%; border: 1px solid #000; font-size: 15px;">
                                                <table border="0"  cellpadding="3">
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #000;">'.$v['measurement_name'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #000;">'.$v['value1'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>'.$v['value2'].'</td>
                                                    </tr>
                                                </table>';
                                    $body .= '</td>';
                                endforeach;
                            endif;
        $body .= '  </tr>
                  </table>
                  <br/><br/>
                  <table border="0" cellpadding="5">
                    <tr><td style="width: 100%;">STYLE</td></tr>
                    <tr>';
                        if(empty($value['style_data'])):
                                $body .= '<td style="width: 100%; text-align: center; color: red;">NO STYLE ADDED !!!</td>';
                            else:
                                $cnt = 0;
                                foreach ($value['style_data'] as $k => $v):
                                    $cnt++;
                                    if($cnt == 8):
                                        $body .= '</tr><tr>';
                                        $cnt = 0;
                                    endif;
                                    $body .= '<td style="width: 14%; border: 1px solid #000; font-size: 15px;">
                                                <table border="0"  cellpadding="3">
                                                    <tr>
                                                        <td>'.$v['style_name'].'</td>
                                                    </tr>
                                                </table>';
                                    $body .= '</td>';
                                endforeach;
                            endif;
        $body .= '  </tr>
            </table>
            <br/><br/>
            <hr/>
            <br/><br/>';
endforeach;
$pdf->writeHTMLCell(200, 106, 5, 5, $body, 0, 0, 0, true, 'P', true);
$pdf->Output('ESTIMATE-MEASUREMENT-SLIP.pdf', 'I');