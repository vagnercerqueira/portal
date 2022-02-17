<?php

namespace App\Libraries\Fpdf;

require('fpdf.php');

class PDF_MC_Table extends FPDF
{

    var $widths;
    var $aligns;
    var $titulo = 'TITULO DO PDF';
    var $SubTitulo = null;
    var $cabecalho = true;
    var $PrecabContent = [];
    var $PrecabSize = [];
    var $PrecabBord = [];
    //var $dt_header = date('d/m/Y');
    var $cabecalhos = array();

    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }
    function SetTitulo($a)
    {
        //Set the array of column alignments
        $this->titulo = $a;
    }
    function SetSubTitulo($a)
    {
        $this->SubTitulo = $a;
    }
    function SetPreCabecalhos($size = [], $content = [], $border = true)
    {
        $this->PrecabContent = $content;
        $this->PrecabSize = $size;
        $this->PrecabBord = $border;
    }
    function SetCabecalhos($a)
    {
        //Set the array of column alignments
        $this->cabecalhos = $a;
    }
    function setBoolcabecalho($a = true)
    {
        //Set the array of column alignments
        $this->cabecalho = $a;
    }

    function Row($data)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 5, $data[$i], 0, $a, 0, 1);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function Header()
    {
        if ($this->cabecalho == !true)
            return;
        // Logo
        $this->Image(FCPATH . 'assets/img/empresa/logo_sicon.jpg', 10, 7, 25, 25);
        // Arial bold 15				
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(30, 8, "", 0, 0, 'R');

        $tw = $this->CurOrientation == 'P' ? 123 : 200;

        $this->Cell($tw, 9, $this->titulo, 0, 0, 'C');
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(40, 10, utf8_decode("Gerado em: ") . date('d/m/Y H:i'), 0, 1, 'C');


        $this->Ln(3);
        $this->SetFont('Arial', 'B', 10);
        $t = $this->CurOrientation == 'P' ? 193 : 275;
        $this->Cell($t, 7, $this->SubTitulo, "B", 1, 'C');
        $this->SetFont('Arial', '', 9);

        $this->Ln(3);

        foreach ($this->PrecabContent as $k => $v) {
            $this->Cell($this->PrecabSize[$k], 7, $v, 1, 0);
            //var $Precabecalhos = [];
            //var $PrecabSize = [];
            //var $PrecabBord = [];
            if (count($this->PrecabContent) == ($k + 1))
                $this->Ln(8);
        }

        $this->SetFillColor(220, 220, 220);
        foreach ($this->cabecalhos as $k => $v) {
            $this->Cell($this->widths[$k], 10, $v, 1, 0, 'C', 1);
        }
        $this->Ln(10);
    }

    function Footer()
    {

        $this->SetY(-15);

        $this->SetFont('Arial', 'I', 8);

        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}