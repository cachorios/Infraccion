<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cacho
 * Date: 4/07/13
 * Time: 14:32
 * To change this template use File | Settings | File Templates.
 */

namespace Infraccion\infraccionBundle\pdf;
use Infraccion\infraccionBundle\Entity\Infraccion;
use TCPDF;
use TCPDF_IMAGES;
use Lar\UsuarioBundle\Twig\LarExtension;

require_once("c:/web/infraccion/web/tcpdf/tcpdf_include.php");

class infraPdf extends  tcpdf {
    private $subtitulo;
    private $logo_empresa;
    private $posY = 0;


    public function Header() {
        // Logo
        $imgtype = TCPDF_IMAGES::getImageFileType($this->header_logo);
        $this->Image($this->header_logo, 15 , 10, 22, '', $imgtype, '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetTextColorArray($this->header_text_color);
        $this->posY = 10;
        foreach($this->logo_empresa as $line){
            $this->SetFont('freeserif',  $line[2], $line[1]);
            //$this->posY = $this->posY +8;
            $this->setY($this->posY += 3);
            $this->Cell(0, 0, $line[0], 0, false, 'R', 0, '', 0, false, 'M', 'M');

        }
        // Set font
        $this->SetFont('freeserif', 'BU', 18);
        // Title

        $this->setY(30);
        $this->Cell(0, 0, $this->header_title, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->setY(36);
        $this->SetFont('helvetica', 'N', 12);
        $this->Cell(0, 0, $this->subtitulo, 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }


    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-10);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
       // $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //$this->Cell(0, 10, '1', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public  function init($titulo="")
    {

        //$this = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->SetCreator("LAR");
        $this->SetAuthor('Infraccion by LAR');
        $this->SetTitle($titulo);
        // remove default header/footer

        // set header and footer fonts
        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $this->SetMargins(15, 10, 10);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // META --------------------------------------
        $l['a_meta_charset'] = 'UTF-8';
        $l['a_meta_dir'] = 'ltr';
        $l['a_meta_language'] = 'es';
        // TRANSLATIONS --------------------------------------
        $l['w_page'] = 'página';
        $this->setLanguageArray($l);
        // --------------


    }

    /**
     * @param string $ln
     * @param int $lw
     * @param string $ht
     * @param string $hs
     * @param array $tc
     * @param array $lc
     * @param $subtitulo
     * @param array $logo_empresa texto y tamoño de fuente, color
     */

    public function setHeaderData($ln='', $lw=0, $ht='', $hs='', $tc=array(0,0,0), $lc=array(0,0,0), $logo_empresa = array()) {
        $this->header_logo = $ln;
        $this->header_logo_width = $lw;
        $this->header_title = $ht;
        $this->header_string = $hs;
        $this->header_text_color = $tc;
        $this->header_line_color = $lc;
        $this->subtitulo = $hs;
        $this->logo_empresa = $logo_empresa;
    }


    public function cedulaBody(Infraccion $entity,$fecha)
    {
        $this->SetTextColorArray($this->header_text_color);
        //$this->posY += 22;$this->setY($this->posY);
        $this->setY($this->getY()+32);
        $this->SetFont('helvetica', 'N', 9.5);
        $this->MultiCell(0, 5, $this->fechaText( $entity->getMunicipio()->getLocalidad(),$entity->getMunicipio()->getProvincia(), $fecha ) , 0, 'R', 0, 1, '', '', true);
        $this->setY($this->getY()+0);

        $text = sprintf(
            '<div style="text-align: justify; line-height: 1.6px;">Por la presente, se notifica que se ha labrado acta de Infracción Nº S%08d; que da inicio a las actuaciones contravencionales ante el Tribunal Administrativo de Faltas de la Ciudad de Garupá, cito en Av. Las Américas Nº 3217. Por ello se lo CITA, EMPLAZA Y NOTIFICA para comparecer, dentro del plazo máximo de diez (10) días corridos, contados desde la notificación de la presente, a formular descargo y ofrecer las pruebas que tuviere y que estime convenientes, con relación a la infracción que se le imputa, munido de Documento Nacional de Identidad y Licencia de Conducir habilitante, en su caso, y el DUPLICADO de la presente acta de infracción, pudiendo ser asistido por un letrado. En caso de residir a una distancia mayor a 60 Kilómetros del asiento del Juez competente que corresponde a la jurisdicción del lugar de comisión de la presunta infracción, podrá optar por comparecer personalmente ante el juez, ejercer su defensa por escrito, o prorrogar la competencia ante el juez competente de su domicilio. En caso de que el imputado se encuentre domiciliado en otra provincia, solo procederá la prórroga, cuando la jurisdicción a la que pertenezca el juez del domicilio se encuentre adherida a la Ley Nacional de Tránsito y exista un convenio de reciprocidad.-</div> ',
            $entity->getNroInfraccion() );

        $this->writeHTML($text, true, false, true, false, '');

        $this->setY($this->getY()+2);
        $this->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(90, 90, 90)));
        $this->Line(15,$this->getY(),197,$this->getY() );

        $this->setY($this->getY()+4);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(180, 0, sprintf("ACTA DE INFRACCIN DE TRANSITO Nº S%08d",$entity->getNroInfraccion()), 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $this->setY($this->getY()+3);

        $this->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(80, 80, 80)));
        $this->SetTextColor(95,95,95);
        $this->SetFillColor(240,240,240);
        $this->SetFont('helvetica', 'B', 9);
        $this->MultiCell(55, 4, "Apellido y Nombre:", 1, 'L', 1, 0);
        $this->MultiCell(59, 4, "DNI/CUIT: ", 1, 'L', 1, 0);
        $this->MultiCell(68, 4, "Infracción Imputada: ", 1, 'L', 1, 0);

        $this->Ln();
        $this->SetFont('helvetica', 'N', 9);
        $this->SetFillColor(255,255,255);

        if($entity->getAutomotor()->getDni() && $entity->getAutomotor()->getCuitCuil()){
            $text = "DNI ". number_format($entity->getAutomotor()->getDni(),0, ',', '.') . ' - CUIT ' .LarExtension::cuitformat($entity->getAutomotor()->getCuitCuil());
        }else{
            $text ="";
            if($entity->getAutomotor()->getDni()){
                $text = "DNI ". number_format($entity->getAutomotor()->getDni(),0, ',', '.');
            }
            if($entity->getAutomotor()->getCuitCuil()){
                $text = 'CUIT ' . LarExtension::cuitformat($entity->getAutomotor()->getCuitCuil());
            }
        }

        $this->MultiCell(55, 4, $entity->getAutomotor()->getNombre() , 1, 'L', 1, 0);
        $this->MultiCell(59, 4, $text, 1, 'L', 1, 0);
        $this->MultiCell(68, 4, $entity->getTipoInfraccion()->getNombre(), 1, 'L', 1, 0);

        $this->Ln();
        $this->SetFillColor(240,240,240);
        $this->SetFont('helvetica', 'B', 9);
        $this->MultiCell(114, 4, "Domicilio:", 1, 'L', 1, 0);
        $this->MultiCell(68, 4, "Lugar: ", 1, 'L', 1, 0);

        $this->Ln();
        $this->SetFont('helvetica', 'N', 9);
        $this->SetFillColor(255,255,255);
        $this->MultiCell(114, 4, $entity->getAutomotor()->getDomicilio(), 1, 'L', 1, 0);
        $this->MultiCell(68, 4, $entity->getUbicacion()->getUbicacion(), 1, 'L', 1, 0);

        $this->Ln();
        $this->SetFillColor(240,240,240);
        $this->SetFont('helvetica', 'B', 9);
        $this->MultiCell(55, 4, "Código Postal:", 1, 'L', 1, 0);
        $this->MultiCell(59, 4, "Localidad: ", 1, 'L', 1, 0);
        $this->MultiCell(23, 4, "Fecha: ", 1, 'L', 1, 0);
        $this->MultiCell(22, 4, "Hora: ", 1, 'L', 1, 0);
        $this->MultiCell(23, 4, "Dominio: ", 1, 'L', 1, 0);

        $this->Ln();
        $this->SetFont('helvetica', 'N', 9);
        $this->SetFillColor(255,255,255);
        $this->MultiCell(55, 4, $entity->getAutomotor()->getCodigoPostal(), 1, 'L', 1, 0);
        $this->MultiCell(59, 4, $entity->getAutomotor()->getLocalidad(), 1, 'L', 1, 0);
        $this->MultiCell(23, 4, $entity->getFecha()->format('d/m/Y'), 1, 'L', 1, 0);
        $this->MultiCell(22, 4, $entity->getFecha()->format('H:i:s'), 1, 'L', 1, 0);
        $this->SetFont('helvetica', 'B', 9);
        $this->MultiCell(23, 4, $entity->getDominio(), 1, 'L', 1, 0);

        $this->Ln();
        $this->SetFillColor(240,240,240);
        $this->SetFont('helvetica', 'B', 9);
        $this->MultiCell(114, 4, "Provincia:", 1, 'L', 1, 0);
        $this->MultiCell(34, 4, "Marca: ", 1, 'L', 1, 0);
        $this->MultiCell(34, 4, "Modelo: ", 1, 'L', 1, 0);

        $this->Ln();
        $this->SetFont('helvetica', 'N', 9);
        $this->SetFillColor(255,255,255);
        $this->MultiCell(114, 4, $entity->getAutomotor()->getProvincia(), 1, 'L', 1, 0);
        $this->MultiCell(34, 4, $entity->getAutomotor()->getMarca(), 1, 'L', 1, 0);
        $this->MultiCell(34, 4, $entity->getAutomotor()->getModelo(), 1, 'L', 1, 0);

        $this->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(90, 90, 90)));
        $this->Line(15,$this->getY()+6,197,$this->getY()+6);

        $this->setY($this->getY()+10);
        $this->SetFont('helvetica', 'N', 9);
        $this->setX(15);
        $this->Cell(40, 0, "Disposición legal infringida: ", 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 0, "Ley Nacional de Tránsito N°24.449, Mod. Y Reg. Vigente CC.", 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $this->setY($this->getY()+3);
        $this->SetFont('helvetica', 'N', 10);
        $monto = number_format($entity->getTipoInfraccion()->getImporte(),2, ',', '.');
        $uf = number_format($entity->getMunicipio()->getUnidadFiscal(),3, ',', '.');
        $total = number_format($entity->getTipoInfraccion()->getImporte() * $entity->getMunicipio()->getUnidadFiscal(),2, ',', '.');
        $text = "<div>Monto mínimo de la infracción:
                <strong>". $monto." U.F.</strong>
                - valor U.F. : <strong>".$uf."</strong>
                - Importe:
                <strong>$total</strong>";
        $this->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(90, 90, 90)));
        $this->writeHTMLCell(182, 5, '', $this->getY(), $text, 1, 2, true, 'C', true);
        $this->Ln();
        $this->setY($this->getY() -3) ;
        $text ="<strong>Nota:</strong> Serán solo válidos los descargos recibidos personalmente, desde juzgado competente o
                mediante carta certificada acompañado de copia de la presente, fotocopia de DNI y declaración
                y/o prueba correspondiente al descargo en cuestión.-";

        $this->writeHTMLCell(120, 0, '', $this->getY(), $text, 0, 0, true, true, 'L', true);

        $this->Ln();
        $this->setY($this->getY() +4) ;
        $this->Cell(0, 0, "QUEDA UD. DEBIDAMENTE NOTIFICADO.-", 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $imgtype = TCPDF_IMAGES::getImageFileType("uploads/".$entity->getMunicipio()->getFirma());
        $this->Image("uploads/".$entity->getMunicipio()->getFirma(), 140 , $this->getY() -22, 50, 25, $imgtype, '', 'M', false, 300, '', false, false, 0, "CM", false, false);

        $this->setY($this->getY() +14) ;
        $this->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(90, 90, 90)));
        $this->Line(15,$this->getY(),197,$this->getY() );

        $this->Ln();
        $this->setY($this->getY() ) ;
        $this->SetFont('helvetica', 'BU', 9);
        $this->Cell(0, 0, "PAGO VOLUNTARIO CON DESCUENTO DEL CINCUENTA POR CIENTO (50%) PRIMER VENCIMIENTO", 0, false, 'C', 0, '', 0, false, 'M', 'M');

        $this->Ln();
        $this->setY($this->getY() +1) ;
        $this->SetFont('helvetica', 'N', 9);
        $this->Cell(0, 0, "Código de Barras valido únicamente para: Pago Fácil, Rapi-Pago, Cobro Express, RIPSA Pagos, Provincia Pagos.-", 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $this->Image("img/medio_pago.jpg", 15 , $this->getY()+3, 45, 25, 'JPEG', '', 'M', false, 300, '', false, false, 0, "LM", false, false);



        $this->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(80, 80, 80)));
        $this->SetTextColor(95,95,95);
        $this->SetFillColor(240,240,240);
        $this->SetFont('helvetica', 'B', 9);

        $this->setY($this->getY() -10);
        $this->setX(66);
        $this->MultiCell(40, 4, "Primer Vencimiento",  1,'C', 1, 0);
        $this->MultiCell(40, 4, "Segundo Vencimiento", 1,'C', 1, 0);

        $this->SetFont('helvetica', 'B', 12);

        $this->Ln();
        $this->setX(66);
        $this->SetFillColor(255,255,255);
        $this->MultiCell(40, 4, $entity->getVto1()->format('d/m/Y'),  1,'C', 1, 0);
        $this->MultiCell(40, 4, $entity->getVto2()->format('d/m/Y'), 1,'C', 1, 0);
        $this->Ln();
        $this->setX(66);

        $imp = $entity->getTipoInfraccion()->getImporte() * $entity->getMunicipio()->getUnidadFiscal();
        $this->MultiCell(40, 4, number_format($imp/2,2, ',', '.'),  1,'C', 1, 0);
        $this->MultiCell(40, 4, number_format($imp,2, ',', '.'), 1,'C', 1, 0);

        $this->Image("cbarra/".$entity->getMunicipio()->getCodigo().'/'.$entity->getNroInfraccion().".jpg", 147 , $this->getY() -13, 52, 25, 'JPEG', '', 'M', false, 300, '', false, false, 0, "LM", false, false);

        $this->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(90, 90, 90)));

        $this->setY( $this->getY() +8);
        $this->Line(15,$this->getY()+8,197,$this->getY()+8);

        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->Ln();

        $this->SetFont('helvetica', 'BU', 12);
        $this->Cell(0, 0, "DOCUMENTACION CON VENCIMIENTO", 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $this->Ln();
        $this->Ln();
        $this->SetFont('helvetica', 'N', 10);
        $this->Cell(120, 0, "Destino", 'B', false, 'L', 0, '', 0, false, 'M', 'M');$this->Ln();$this->Ln();
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(120, 0, $entity->getAutomotor()->getNombre(), 0, false, 'L', 0, '', 0, false, 'M', 'M');$this->Ln();
        $this->SetFont('helvetica', 'B', 10);

        if($entity->getAutomotor()->getDni() && $entity->getAutomotor()->getCuitCuil()){
            $text = "DNI ". number_format($entity->getAutomotor()->getDni(),0, ',', '.') . ' - CUIT ' .LarExtension::cuitformat($entity->getAutomotor()->getCuitCuil());
        }else{
            $text ="";
            if($entity->getAutomotor()->getDni()){
                $text = "DNI ". number_format($entity->getAutomotor()->getDni(),0, ',', '.');
            }
            if($entity->getAutomotor()->getCuitCuil()){
                $text = 'CUIT ' . LarExtension::cuitformat($entity->getAutomotor()->getCuitCuil());
            }
        }
        $this->Cell(120, 0, $text, 0, false, 'L', 0, '', 0, false, 'M', 'M');$this->Ln();
        $this->Cell(120, 0, $entity->getAutomotor()->getDomicilio(), 0, false, 'L', 0, '', 0, false, 'M', 'M');$this->Ln();
        $this->Cell(120, 0, $entity->getAutomotor()->getLocalidad().', CP '. $entity->getAutomotor()->getCodigoPostal() .' - ' .$entity->getAutomotor()->getProvincia() , 0, false, 'L', 0, '', 0, false, 'M', 'M');$this->Ln();

        $this->Image("img/franqueo.jpg", 162, $this->getY() -47, 35, 20, 'JPEG', '', 'M', false, 300, '', false, false, 0, "RM", false, false);

    }

    private function fechaText($localidad, $provincia,\DateTime $fecha)
    {
        $meses = array('Enero', 'Febrero','Marzo', 'Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre', 'Diciembre');
        return $localidad.", ".$provincia.', '. $fecha->format('d').' de '. $meses[$fecha->format('n') -1] . ' de ' . $fecha->format('Y')    ;
    }


}