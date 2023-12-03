<?php

namespace App\Service;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private $dompdf;

    public function __construct()
    {
        $this->dompdf = new Dompdf();
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $this->dompdf->setOptions($pdfOptions);
    }

    public function generatePdfFromHtml($htmlContent): string
    {
        $this->dompdf->loadHtml($htmlContent);
        $this->dompdf->render();
        return $this->dompdf->output();
    }
}