<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_pdf {
    
public $param;
public $pdf;
public function __construct($param = "'c', 'A4-L'")
{
    include_once APPPATH.'third_party/mpdf/mpdf.php';
    echo APPPATH.'third_party/mpdf/mpdf.php';exit;
    $this->param =$param;
    $this->pdf = new mPDF($this->param);
}
}


 /* End of file M_pdf.php */
 /* Location: ./application/libraries/M_pdf.php */
 