<?php

namespace Fimacom\Pdf\Common;

use Exception;

class Fpdf
{
    const FPDF_VERSION = '1.6';
    const FPDF_FONTPATH = '/font/';

    public int $page;               //current page number
    public int $n;                  //current object number
    public array $offsets;            //array of object offsets
    public string $buffer;             //buffer holding in-memory PDF
    public array $pages;              //array containing pages
    public int $state;              //current document state
    public bool $compress;           //compression flag
    public float $k;                  //scale factor (number of points in user unit)
    public string $defOrientation;     //default orientation
    public string $curOrientation;     //current orientation
    public array $pageFormats;        //available page formats
    public mixed $defPageFormat;      //default page format
    public mixed $curPageFormat;      //current page format
    public array $pageSizes;          //array storing non-default page sizes
    public float $wPt;
    public float $hPt;           //dimensions of current page in points
    public float $w;
    public float $h;               //dimensions of current page in user unit
    public int $lMargin;            //left margin
    public int $tMargin;            //top margin
    public int $rMargin;            //right margin
    public int $bMargin;            //page break margin
    public int $cMargin;            //cell margin
    public float $x;
    public float $y;               //current position in user unit
    public float $lasth;              //height of last printed cell
    public float $lineWidth;          //line width in user unit
    public array $coreFonts;          //array of standard font names
    public array $fonts;              //array of used fonts
    public array $fontFiles;          //array of font files
    public array $diffs;              //array of encoding differences
    public string $fontFamily;         //current font family
    public string $fontStyle;          //current font style
    public bool $underline;          //underlining flag
    public array $currentFont;        //current font info
    public int $fontSizePt;         //current font size in points
    public float $fontSize;           //current font size in user unit
    public string $drawColor;          //commands for drawing color
    public string $fillColor;          //commands for filling color
    public string $textColor;          //commands for text color
    public bool $colorFlag;          //indicates whether fill and text colors are different
    public float $ws;                 //word spacing
    public array $images;             //array of used images
    public array $pageLinks;          //array of links in pages
    public array $links;              //array of internal links
    public bool $autoPageBreak;      //automatic page breaking
    public float $pageBreakTrigger;   //threshold used to trigger page breaks
    public bool $inHeader;           //flag set when processing header
    public bool $inFooter;           //flag set when processing footer
    public string $zoomMode;           //zoom display mode
    public string $layoutMode;         //layout display mode
    public string $title;              //title
    public string $subject;            //subject
    public string $author;             //author
    public string $keywords;           //keywords
    public string $creator;            //creator
    public string $aliasNbPages;       //alias for total number of pages
    public string $pdfVersion;         //PDF version number

    /**
     * Construtor
     * @param string $orientation
     * @param string $unit
     * @param mixed $format
     * @throws Exception
     */
    public function __construct(string $orientation = 'P', string $unit = 'mm', mixed $format = 'A4')
    {
        //Some checks
        $this->dochecks();
        //Initialization of properties
        $this->page = 0;
        $this->n = 2;
        $this->buffer = '';
        $this->pages = [];
        $this->pageSizes = [];
        $this->state = 0;
        $this->fonts = [];
        $this->fontFiles = [];
        $this->diffs = [];
        $this->images = [];
        $this->links = [];
        $this->inHeader = false;
        $this->inFooter = false;
        $this->lasth = 0;
        $this->fontFamily = '';
        $this->fontStyle = '';
        $this->fontSizePt = 12;
        $this->underline = false;
        $this->drawColor = '0 G';
        $this->fillColor = '0 g';
        $this->textColor = '0 g';
        $this->colorFlag = false;
        $this->ws = 0;
        //Standard fonts
        $this->coreFonts = [
            'courier' => 'Courier',
            'courierB' => 'Courier-Bold',
            'courierI' => 'Courier-Oblique',
            'courierBI' => 'Courier-BoldOblique',
            'helvetica' => 'Helvetica',
            'helveticaB' => 'Helvetica-Bold',
            'helveticaI' => 'Helvetica-Oblique',
            'helveticaBI' => 'Helvetica-BoldOblique',
            'times' => 'Times-Roman',
            'timesB' => 'Times-Bold',
            'timesI' => 'Times-Italic',
            'timesBI' => 'Times-BoldItalic',
            'symbol' => 'Symbol',
            'textilesym' => 'textilesym',
            'zapfdingbats'=>'ZapfDingbats'
        ];
        //Scale factor
        if ($unit === 'pt') {
            $this->k = 1;
        } elseif ($unit === 'mm') {
            $this->k = 72/25.4;
        } elseif ($unit === 'cm') {
            $this->k = 72/2.54;
        } elseif ($unit === 'in') {
            $this->k = 72;
        } else {
            $this->error('Incorrect unit: '.$unit);
        }
        //Page format
        $this->pageFormats = [
            'a3' => [841.89,1190.55],
            'a4' => [595.28,841.89],
            'a5' => [420.94,595.28],
            'letter' => [612,792],
            'legal' => [612,1008]
        ];
        if (is_string($format)) {
            $format = $this->getpageformat($format);
        }
        $this->defPageFormat = $format;
        $this->curPageFormat = $format;
        //Page orientation
        $orientation = strtolower($orientation);
        if ($orientation === 'p' || $orientation === 'portrait') {
            $this->defOrientation = 'P';
            $this->w = $this->defPageFormat[0];
            $this->h = $this->defPageFormat[1];
        } elseif ($orientation === 'l' || $orientation === 'landscape') {
            $this->defOrientation = 'L';
            $this->w = $this->defPageFormat[1];
            $this->h = $this->defPageFormat[0];
        } else {
            $this->error('Incorrect orientation: '.$orientation);
        }
        $this->curOrientation = $this->defOrientation;
        $this->wPt = $this->w * $this->k;
        $this->hPt = $this->h * $this->k;
        //Page margins (1 cm)
        $margin = 28.35 / $this->k;
        $this->setMargins($margin, $margin);
        //Interior cell margin (1 mm)
        $this->cMargin = $margin / 10;
        //Line width (0.2 mm)
        $this->lineWidth = .567 / $this->k;
        //Automatic page break
        $this->setAutoPageBreak(true, 2 * $margin);
        //Full width display mode
        $this->setDisplayMode('fullwidth');
        //Enable compression
        $this->setCompression(true);
        //Set default PDF version number
        $this->pdfVersion='1.8';
    }

    /**
     * Seta as margens
     * @param float $left
     * @param float $top
     * @param float|null $right
     * @return void
     */
    public function setMargins(float $left, float $top, float $right = null): void
    {
        //Set left, top and right margins
        $this->lMargin = $left;
        $this->tMargin = $top;
        if ($right === null) {
            $right = $left;
        }
        $this->rMargin = $right;
    }

    /**
     * Seta margem esquerda
     * @param float $margin
     * @return void
     */
    public function setLeftMargin(float $margin): void
    {
        //Set left margin
        $this->lMargin = $margin;
        if ($this->page > 0 && $this->x < $margin) {
            $this->x = $margin;
        }
    }

    /**
     * Seta a margem superior
     * @param $margin
     * @return void
     */
    public function setTopMargin(float $margin): void
    {
        //Set top margin
        $this->tMargin = $margin;
    }

    /**
     * Seta a margem direita
     * @param float $margin
     * @return void]
     */
    public function setRightMargin(float $margin): void
    {
        //Set right margin
        $this->rMargin = $margin;
    }

    /**
     * Seta quebra de página
     * @param bool $auto
     * @param float $margin
     * @return void
     */
    public function setAutoPageBreak(bool $auto, float $margin = 0): void
    {
        //Set auto page break mode and triggering margin
        $this->autoPageBreak = $auto;
        $this->bMargin = $margin;
        $this->pageBreakTrigger = $this->h - $margin;
    }

    /**
     * Seta o modo de ZOOM
     * @param string $zoom
     * @param string $layout
     * @return void
     * @throws Exception
     */
    public function setDisplayMode(string $zoom, string $layout = 'continuous'): void
    {
        //Set display mode in viewer
        if ($zoom === 'fullpage' || $zoom === 'fullwidth' || $zoom === 'real' || $zoom === 'default') {
            $this->zoomMode = $zoom;
        } else {
            $this->error('Incorrect zoom display mode: ' . $zoom);
        }
        if ($layout === 'single' || $layout === 'continuous' || $layout === 'two' || $layout === 'default') {
            $this->layoutMode = $layout;
        } else {
            $this->error('Incorrect layout display mode: '.$layout);
        }
    }


    /**
     * Nivel de compressão de 0-nenhuma a 9-maxima
     * @param int $compress
     * @return void
     */
    public function setCompression(int $compress)
    {
        //Set page compression
        if (function_exists('gzcompress')) {
            $this->compress = $compress;
        } else {
            $this->compress = false;
        }
    }

    /**
     * Seta o titulo do documento
     * @param string $title
     * @param bool $isUTF8
     * @return void
     */
    public function setTitle(string $title, bool $isUTF8 = false)
    {
        //Title of document
        if ($isUTF8) {
            $title = $this->utf8Toutf16($title);
        }
        $this->title = $title;
    }

    /**
     * Seta o assunto do documento
     * @param string $subject
     * @param bool $isUTF8
     * @return void
     */
    public function setSubject(string $subject, $isUTF8 = false)
    {
        //Subject of document
        if ($isUTF8) {
            $subject = $this->utf8Toutf16($subject);
        }
        $this->subject = $subject;
    }

    /**
     * Seta o autor do documento
     * @param string $author
     * @param bool $isUTF8
     * @return void
     */
    public function setAuthor(string $author, bool $isUTF8 = false)
    {
        //Author of document
        if ($isUTF8) {
            $author = $this->utf8Toutf16($author);
        }
        $this->author = $author;
    }

    /**
     * Seta as palavras chave em uma string separadas por virgula, espaço ou ponto-virgula
     * @param string $keywords
     * @param bool $isUTF8
     * @return void
     */
    public function setKeywords(string $keywords, bool $isUTF8 = false)
    {
        //Keywords of document
        if ($isUTF8) {
            $keywords = $this->utf8Toutf16($keywords);
        }
        $this->keywords = $keywords;
    }

    /**
     * Seta o aplicativo criador do PDF
     * @param string $creator
     * @param bool $isUTF8
     * @return void
     */
    public function setCreator(string $creator, bool $isUTF8 = false)
    {
        //Creator of document
        if ($isUTF8) {
            $creator = $this->utf8Toutf16($creator);
        }
        $this->creator = $creator;
    }

    /**
     * Seta o alias para o numero de paginas
     * @param string $alias
     * @return void
     */
    public function aliasNbPages(string $alias = '{nb}')
    {
        //Define an alias for total number of pages
        $this->aliasNbPages = $alias;
    }

    /**
     * Dispara uma exception
     * @param string $msg
     * @return mixed
     * @throws Exception
     */
    public function error($msg)
    {
        throw new Exception($msg);
    }

    /**
     * Abre o PDF
     * @return void
     */
    public function open()
    {
        $this->state = 1;
    }

    /**
     * Fecha o PDF
     * @return void
     */
    public function close()
    {
        //Terminate document
        if ($this->state == 3) {
            return;
        }
        if ($this->page == 0) {
            $this->addPage();
        }
        //Page footer
        $this->inFooter = true;
        $this->footer();
        $this->inFooter = false;
        //Close page
        $this->endPage();
        //Close document
        $this->endDoc();
    }

    /**
     * Adiciona página
     * @param string $orientation
     * @param string $format
     * @return void
     * @throws Exception
     */
    public function addPage(string $orientation = '', string $format = '')
    {
        //Start a new page
        if ($this->state == 0) {
            $this->open();
        }
        $family = $this->fontFamily;
        $style = $this->fontStyle.($this->underline ? 'U' : '');
        $size = $this->fontSizePt;
        $lw = $this->lineWidth;
        $dc = $this->drawColor;
        $fc = $this->fillColor;
        $tc = $this->textColor;
        $cf = $this->colorFlag;
        if ($this->page > 0) {
            //Page footer
            $this->inFooter = true;
            $this->footer();
            $this->inFooter = false;
            //Close page
            $this->endPage();
        }
        //Start new page
        $this->beginPage($orientation, $format);
        //Set line cap style to square
        $this->out('2 J');
        //Set line width
        $this->lineWidth = $lw;
        $this->out(sprintf('%.2F w', $lw*$this->k));
        //Set font
        if ($family) {
            $this->setFont($family, $style, $size);
        }
        //Set colors
        $this->drawColor = $dc;
        if ($dc !== '0 G') {
            $this->out($dc);
        }
        $this->fillColor = $fc;
        if ($fc !== '0 g') {
            $this->out($fc);
        }
        $this->textColor = $tc;
        $this->colorFlag = $cf;
        //Page header
        $this->inHeader = true;
        $this->Header();
        $this->inHeader = false;
        //Restore line width
        if ($this->lineWidth != $lw) {
            $this->lineWidth = $lw;
            $this->out(sprintf('%.2F w', $lw*$this->k));
        }
        //Restore font
        if ($family) {
            $this->setFont($family, $style, $size);
        }
        //Restore colors
        if ($this->drawColor != $dc) {
            $this->drawColor = $dc;
            $this->out($dc);
        }
        if ($this->fillColor != $fc) {
            $this->fillColor = $fc;
            $this->out($fc);
        }
        $this->textColor = $tc;
        $this->colorFlag = $cf;
    }

    public function header()
    {
        //To be implemented in your own inherited class
    }

    public function footer()
    {
        //To be implemented in your own inherited class
    }

    /**
     * Obtem o número da página corrente
     * @return int
     */
    public function pageNo()
    {
        //Get current page number
        return $this->page;
    }

    /**
     * Seta a cor de desenho RGB
     * @param int $r
     * @param int|null $g
     * @param int|null $b
     * @return void
     */
    public function setDrawColor(int $r, int $g = null, int $b = null)
    {
        //Set color for all stroking operations
        if (($r==0 && $g==0 && $b==0) || $g===null) {
            $this->drawColor = sprintf('%.3F G', $r/255);
        } else {
            $this->drawColor = sprintf('%.3F %.3F %.3F RG', $r/255, $g/255, $b/255);
        }
        if ($this->page > 0) {
            $this->out($this->drawColor);
        }
    }


    /**
     * Seta a cor de preenchimento RGB
     * @param int $r
     * @param int|null $g
     * @param int|null $b
     * @return void
     */
    public function setFillColor(int $r, int $g = null, int $b = null)
    {
        //Set color for all filling operations
        if (($r==0 && $g==0 && $b==0) || $g===null) {
            $this->fillColor = sprintf('%.3F g', $r/255);
        } else {
            $this->fillColor = sprintf('%.3F %.3F %.3F rg', $r/255, $g/255, $b/255);
        }
        $this->colorFlag = ($this->fillColor != $this->textColor);
        if ($this->page > 0) {
            $this->out($this->fillColor);
        }
    }

    /**
     * Seta a cor do texto RGB
     * @param int $r
     * @param int|null $g
     * @param int|null $b
     * @return void
     */
    public function settextColor(int $r, int $g = null, int $b = null)
    {
        //Set color for text
        if (($r == 0 && $g == 0 && $b == 0) || $g === null) {
            $this->textColor = sprintf('%.3F g', $r/255);
        } else {
            $this->textColor = sprintf('%.3F %.3F %.3F rg', $r/255, $g/255, $b/255);
        }
        $this->colorFlag = ($this->fillColor !== $this->textColor);
    }


    /**
     * Obtem a largura de uma string
     * @param string $s
     * @return float|int
     */
    public function getStringWidth(string $s)
    {
        //Get width of a string in the current font
        $s = (string)$s;
        $cw =& $this->currentFont['cw'];
        $w = 0;
        $l = strlen($s);
        for ($i=0; $i<$l; $i++) {
            $w += $cw[$s[$i]];
        }
        return $w * $this->fontSize / 1000;
    }

    /**
     * Desenha uma linha horizontal na largura especificada
     * @param float $width
     * @return void
     */
    public function setLineWidth(float $width): void
    {
        //Set line width
        $this->lineWidth = $width;
        if ($this->page > 0) {
            $this->out(sprintf('%.2F w', $width * $this->k));
        }
    }

    /**
     * Desenha uma linha por coordenadas
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     * @return void
     */
    public function line(float $x1, float $y1, float $x2, float $y2): void
    {
        //Draw a line
        $this->out(
            sprintf(
                '%.2F %.2F m %.2F %.2F l S',
                $x1*$this->k,
                ($this->h-$y1)*$this->k,
                $x2*$this->k,
                ($this->h-$y2)*$this->k
            )
        );
    }

    /**
     * Desenha um retangulo por coordenadas
     * @param float $x
     * @param float $y
     * @param float $w
     * @param float $h
     * @param string $style  F ou FD ou DF
     * @return void
     */
    public function rect(float $x, float $y, float $w, float $h, string $style = ''): void
    {
        //Draw a rectangle
        if ($style === 'F') {
            $op = 'f';
        } elseif ($style === 'FD' || $style === 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }
        $this->out(
            sprintf(
                '%.2F %.2F %.2F %.2F re %s',
                $x * $this->k,
                ($this->h-$y) * $this->k,
                $w * $this->k,
                -$h * $this->k,
                $op
            )
        );
    }

    /**
     * Adiciona uma fonte
     * @param string $family
     * @param string $style
     * @param string $file
     * @return void
     * @throws Exception
     */
    public function addFont(string $family, string $style = '', string $file = ''): void
    {
        //Add a TrueType or Type1 font
        $family = strtolower($family);
        if ($file == '') {
            $file = str_replace(' ', '', $family).strtolower($style).'.php';
        }
        if ($family === 'arial') {
            $family='helvetica';
        }
        $style = strtoupper($style);
        if ($style === 'IB') {
            $style = 'BI';
        }
        $fontkey = $family.$style;
        if (isset($this->fonts[$fontkey])) {
            return;
        }
        $name = null;
        $type = null;
        $desc = null;
        $up = null;
        $ut = null;
        $cw = null;
        $enc = null;
        $diff = null;
        $originalsize = null;
        $size1 = null;
        $size2 = null;
        include $this->getFontPath() . $file;
        if (!isset($name)) {
            $this->error('Could not include font definition file');
        }
        $i = count($this->fonts)+1;
        $this->fonts[$fontkey] = [
            'i' => $i,
            'type' => $type,
            'name' => $name,
            'desc' => $desc,
            'up' => $up,
            'ut' => $ut,
            'cw' => $cw,
            'enc' => $enc,
            'file' => $file
        ];
        if ($diff) {
            //Search existing encodings
            $d = 0;
            $nb = count($this->diffs);
            for ($i=1; $i<=$nb; $i++) {
                if ($this->diffs[$i] == $diff) {
                    $d = $i;
                    break;
                }
            }
            if ($d == 0) {
                $d = $nb+1;
                $this->diffs[$d] = $diff;
            }
            $this->fonts[$fontkey]['diff'] = $d;
        }
        if ($file) {
            if ($type === 'TrueType') {
                $this->fontFiles[$file] = ['length1'=> $originalsize];
            } else {
                $this->fontFiles[$file] = ['length1'=> $size1, 'length2' => $size2];
            }
        }
    }

    /**
     * Seleciona a font; e o tamanho dado em PONTOS
     * @param string $family
     * @param string $style
     * @param int $size
     * @return void
     * @throws Exception
     */
    public function setFont(string $family, string $style = '', int $size = 0): void
    {
        //Select a font; size given in points
        global $fpdf_charwidths;
        $family = strtolower($family);
        if ($family == '') {
            $family = $this->fontFamily;
        }
        if ($family === 'arial') {
            $family = 'helvetica';
        } elseif ($family === 'symbol' || $family === 'zapfdingbats' || $family === 'textilesym') {
            $style = '';
        }
        $style = strtoupper($style);
        if (str_contains($style, 'U') !== false) {
            $this->underline = true;
            $style = str_replace('U', '', $style);
        } else {
            $this->underline = false;
        }
        if ($style === 'IB') {
            $style = 'BI';
        }
        if ($size == 0) {
            $size = $this->fontSizePt;
        }
        //Test if font is already selected
        if ($this->fontFamily == $family && $this->fontStyle == $style && $this->fontSizePt == $size) {
            return;
        }
        //Test if used for the first time
        $fontkey = $family.$style;
        if (!isset($this->fonts[$fontkey])) {
            //Check if one of the standard fonts
            if (isset($this->coreFonts[$fontkey])) {
                if (!isset($fpdf_charwidths[$fontkey])) {
                    //Load metric file
                    $file=$family;
                    if ($family === 'times' || $family === 'helvetica') {
                        $file .= strtolower($style);
                    }
                    include $this->getFontPath().$file.'.php';
                    if (!isset($fpdf_charwidths[$fontkey])) {
                        $this->error('Could not include font metric file');
                    }
                }
                $i = count($this->fonts)+1;
                $name = $this->coreFonts[$fontkey];
                $cw = $fpdf_charwidths[$fontkey];
                $this->fonts[$fontkey] = [
                    'i' => $i,
                    'type' => 'core',
                    'name' => $name,
                    'up' => -100,
                    'ut' => 50,
                    'cw' => $cw
                ];
            } else {
                $this->error('Undefined font: '.$family.' '.$style);
            }
        }
        //Select it
        $this->fontFamily = $family;
        $this->fontStyle = $style;
        $this->fontSizePt = $size;
        $this->fontSize = $size/$this->k;
        $this->currentFont =& $this->fonts[$fontkey];
        if ($this->page > 0) {
            $this->out(sprintf('BT /F%d %.2F Tf ET', $this->currentFont['i'], $this->fontSizePt));
        }
    }

    /**
     * Seta o tamanho da fonte em PONTOS
     * @param int $size
     * @return void
     */
    public function setFontSize(int $size): void
    {
        //Set font size in points
        if ($this->fontSizePt == $size) {
            return;
        }
        $this->fontSizePt = $size;
        $this->fontSize = $size / $this->k;
        if ($this->page > 0) {
            $this->out(sprintf('BT /F%d %.2F Tf ET', $this->currentFont['i'], $this->fontSizePt));
        }
    }

    /**
     * Adiciona os links ao PDF
     * @return int|null
     */
    public function addlink(): int
    {
        //Create a new internal link
        $n = count($this->links) + 1;
        $this->links[$n] = [0, 0];
        return $n;
    }

    /**
     * Seta um link
     * @param string $link
     * @param float $y
     * @param int $page
     * @return void
     */
    public function setlink(string $link, float $y = 0, int $page = -1): void
    {
        //Set destination of internal link
        if ($y == -1) {
            $y = $this->y;
        }
        if ($page == -1) {
            $page = $this->page;
        }
        $this->links[$link] = [$page, $y];
    }

    /**
     * Cria um link
     * @param float $x
     * @param float $y
     * @param float $w
     * @param float $h
     * @param string $link
     * @return void
     */
    public function link(float $x, float $y, float $w, float $h, string $link): void
    {
        //Put a link on the page
        $this->pageLinks[$this->page][] = [
            $x * $this->k,
            $this->hPt - $y * $this->k,
            $w * $this->k,
            $h * $this->k,
            $link
        ];
    }

    /**
     * Insere um texto
     * @param float $x
     * @param float $y
     * @param string $txt
     * @return void
     */
    public function text(float $x, float $y, string $txt): void
    {
        //Output a string
        $s = sprintf(
            'BT %.2F %.2F Td (%s) Tj ET',
            $x * $this->k,
            ($this->h - $y) * $this->k,
            $this->escape($txt)
        );
        if ($this->underline && $txt != '') {
            $s .= ' ' . $this->doUnderLine($x, $y, $txt);
        }
        if ($this->colorFlag) {
            $s = 'q ' . $this->textColor . ' ' . $s . ' Q';
        }
        $this->out($s);
    }

    /**
     *
     * @return bool
     */
    public function acceptPageBreak(): bool
    {
        //Accept automatic page break or not
        return $this->autoPageBreak;
    }

    /**
     * @param float $w
     * @param float $h
     * @param string $txt
     * @param $border
     * @param $ln
     * @param $align
     * @param $fill
     * @param $link
     * @return void
     */
    public function cell(
        float $w,
        float $h = 0,
        string $txt = '',
        string $border = null,
        $ln = 0,
        string $align = '',
        $fill = false,
        string $link = ''
    ): void  {
        //Output a cell
        $border = strtolower($border);
        $align = strtolower($align);
        $k = $this->k;
        if ($this->y + $h > $this->pageBreakTrigger
            && !$this->inHeader
            && !$this->inFooter
            && $this->acceptPageBreak()
        ) {
            //Automatic page break
            $x = $this->x;
            $ws = $this->ws;
            if ($ws > 0) {
                $this->ws = 0;
                $this->out('0 Tw');
            }
            $this->addPage($this->curOrientation, $this->curPageFormat);
            $this->x = $x;
            if ($ws > 0) {
                $this->ws = $ws;
                $this->out(sprintf('%.3F Tw', $ws*$k));
            }
        }
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $s = '';
        if ($fill || $border === 'all') {
            if ($fill) {
                $op = ($border === 'all') ? 'B' : 'f';
            } else {
                $op = 'S';
            }
            $s = sprintf(
                '%.2F %.2F %.2F %.2F re %s ',
                $this->x * $k,
                ($this->h - $this->y) * $k,
                $w * $k,
                -$h * $k,
                $op
            );
        }
        if (!empty($border) && $border !== 'all') {
            $x = $this->x;
            $y = $this->y;
            if (strpos($border, 'l') !== false) {
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x*$k, ($this->h-$y)*$k, $x*$k, ($this->h-($y+$h))*$k);
            }
            if (strpos($border, 't') !== false) {
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x*$k, ($this->h-$y)*$k, ($x+$w)*$k, ($this->h-$y)*$k);
            }
            if (strpos($border, 'r') !== false) {
                $s .= sprintf(
                    '%.2F %.2F m %.2F %.2F l S ',
                    ($x + $w) * $k,
                    ($this->h - $y) * $k,
                    ($x+$w) * $k,
                    ($this->h - ($y + $h)) * $k
                );
            }
            if (strpos($border, 'b') !== false) {
                $s .= sprintf(
                    '%.2F %.2F m %.2F %.2F l S ',
                    $x * $k,
                    ($this->h - ($y + $h)) * $k,
                    ($x + $w) * $k,
                    ($this->h - ($y + $h)) * $k
                );
            }
        }
        if ($txt !== '') {
            if ($align === 'r') {
                $dx = $w-$this->cMargin-$this->getStringWidth($txt);
            } elseif ($align === 'c') {
                $dx = ($w-$this->getStringWidth($txt)) / 2;
            } else {
                $dx = $this->cMargin;
            }
            if ($this->colorFlag) {
                $s .= 'q ' . $this->textColor . ' ';
            }
            $txt2 = str_replace([')', '(', '\\'],['\\)', '\\(', '\\\\'], $txt);
            $s .= sprintf(
                'BT %.2F %.2F Td (%s) Tj ET',
                ($this->x + $dx) * $k,
                ($this->h-($this->y + 0.5 * $h + 0.3 * $this->fontSize)) * $k,
                $txt2
            );
            if ($this->underline) {
                $s .= ' ' . $this->doUnderLine($this->x + $dx, $this->y + 0.5 * $h + 0.3 * $this->fontSize, $txt);
            }
            if ($this->colorFlag) {
                $s.=' Q';
            }
            if ($link) {
                $this->link(
                    $this->x + $dx,
                    $this->y + 0.5 * $h - 0.5 * $this->fontSize,
                    $this->getStringWidth($txt),
                    $this->fontSize,
                    $link
                );
            }
        }
        if ($s) {
            $this->out($s);
        }
        $this->lasth = $h;
        if ($ln > 0) {
            //Go to next line
            $this->y += $h;
            if ($ln == 1) {
                $this->x = $this->lMargin;
            }
        } else {
            $this->x += $w;
        }
    }

    /**
     * Cria uma grade de celulas
     * @param float $w
     * @param float $h
     * @param string $txt
     * @param mixed $border
     * @param string $align
     * @param bool $fill
     * @return void
     */
    public function multicell(float $w, float $h, string $txt, mixed $border = 0, string $align = 'J', bool $fill = false)
    {
        //Output text with automatic or explicit line breaks
        $cw =& $this->currentFont['cw'];
        if ($w == 0) {
            $w = $this->w-$this->rMargin-$this->x;
        }
        $wmax = ($w-2*$this->cMargin)*1000/$this->fontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb>0 && $s[$nb-1] === "\n") {
            $nb--;
        }
        $b = 0;
        if ($border) {
            if ($border == 1) {
                $border = 'LTRB';
                $b = 'LRT';
                $b2 = 'LR';
            } else {
                $b2 = '';
                if (strpos($border, 'L') !== false) {
                    $b2 .= 'L';
                }
                if (strpos($border, 'R') !== false) {
                    $b2 .= 'R';
                }
                $b=(strpos($border, 'T') !== false) ? $b2.'T' : $b2;
            }
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        while ($i<$nb) {
            //Get next character
            $c = $s[$i];
            if ($c === "\n") {
                //Explicit line break
                if ($this->ws > 0) {
                    $this->ws = 0;
                    $this->out('0 Tw');
                }
                $this->cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl == 2) {
                    $b=$b2;
                }
                continue;
            }
            if ($c === ' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                //Automatic line break
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        $this->out('0 Tw');
                    }
                    $this->cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
                } else {
                    if ($align === 'J') {
                        $this->ws = ($ns>1) ? ($wmax-$ls) / 1000 * $this->fontSize / ($ns-1) : 0;
                        $this->out(sprintf('%.3F Tw', $this->ws * $this->k));
                    }
                    $this->cell($w, $h, substr($s, $j, $sep-$j), $b, 2, $align, $fill);
                    $i = $sep+1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl == 2) {
                    $b = $b2;
                }
            } else {
                $i++;
            }
        }
        //Last chunk
        if ($this->ws > 0) {
            $this->ws = 0;
            $this->out('0 Tw');
        }
        if ($border && strpos($border, 'B') !== false) {
            $b .= 'B';
        }
        $this->cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
        $this->x = $this->lMargin;
    }


    public function write($h, $txt, $link = '')
    {
        //Output text in flowing mode
        $cw =& $this->currentFont['cw'];
        $w = $this->w-$this->rMargin-$this->x;
        $wmax = ($w-2*$this->cMargin)*1000/$this->fontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            //Get next character
            $c=$s[$i];
            if ($c === "\n") {
                //Explicit line break
                $this->cell($w, $h, substr($s, $j, $i-$j), 0, 2, '', 0, $link);
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                if ($nl == 1) {
                    $this->x = $this->lMargin;
                    $w = $this->w-$this->rMargin-$this->x;
                    $wmax = ($w-2*$this->cMargin)*1000/$this->fontSize;
                }
                $nl++;
                continue;
            }
            if ($c === ' ') {
                $sep = $i;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                //Automatic line break
                if ($sep == -1) {
                    if ($this->x > $this->lMargin) {
                        //Move to next line
                        $this->x = $this->lMargin;
                        $this->y += $h;
                        $w = $this->w-$this->rMargin-$this->x;
                        $wmax = ($w-2*$this->cMargin)*1000/$this->fontSize;
                        $i++;
                        $nl++;
                        continue;
                    }
                    if ($i == $j) {
                        $i++;
                    }
                    $this->cell($w, $h, substr($s, $j, $i-$j), 0, 2, '', 0, $link);
                } else {
                    $this->cell($w, $h, substr($s, $j, $sep-$j), 0, 2, '', 0, $link);
                    $i = $sep+1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                if ($nl == 1) {
                    $this->x = $this->lMargin;
                    $w = $this->w-$this->rMargin-$this->x;
                    $wmax = ($w-2*$this->cMargin)*1000/$this->fontSize;
                }
                $nl++;
            } else {
                $i++;
            }
        }
        //Last chunk
        if ($i != $j) {
            $this->cell($l/1000*$this->fontSize, $h, substr($s, $j), 0, 0, '', 0, $link);
        }
    }

    public function ln($h = null)
    {
        //Line feed; default value is last cell height
        $this->x = $this->lMargin;
        if ($h === null) {
            $this->y += $this->lasth;
        } else {
            $this->y += $h;
        }
    }

    /**
     * Insere uma imagem
     * @param string $file
     * @param float|null $x
     * @param float|null $y
     * @param float $w
     * @param float $h
     * @param string $type
     * @param string $link
     * @return void
     * @throws Exception
     */
    public function image(string $file, float $x = null, float $y = null, float $w = 0, float $h = 0, string $type = '', string $link = '')
    {
        //Put an image on the page
        if (!isset($this->images[$file])) {
            //First use of this image, get info
            if (empty($type)) {
                $pos = strrpos($file, '.');
                if (!$pos) {
                    $this->error('Image file has no extension and no type was specified: '.$file);
                }
                $type = substr($file, $pos+1);
            }
            $type = strtolower($type);
            if ($type === 'jpeg') {
                $type = 'jpg';
            }
            $mtd = 'parse'.strtoupper($type);
            if (!method_exists($this, $mtd)) {
                $this->error('Tipo da imagem não é suportado: ' . $type);
            }
            $info = $this->$mtd($file);
            $info['i'] = count($this->images)+1;
            $this->images[$file] = $info;
        } else {
            $info = $this->images[$file];
        }
        //Automatic width and height calculation if needed
        if ($w == 0 && $h == 0) {
            //Put image at 72 dpi
            $w = $info['w'] / $this->k;
            $h = $info['h'] / $this->k;
        } elseif ($w == 0) {
            $w = $h*$info['w']/$info['h'];
        } elseif ($h == 0) {
            $h = $w*$info['h']/$info['w'];
        }
        //Flowing mode
        if ($y === null) {
            if ($this->y+$h > $this->pageBreakTrigger
                && !$this->inHeader
                && !$this->inFooter
                && $this->acceptPageBreak()
            ) {
                //Automatic page break
                $x2 = $this->x;
                $this->addPage($this->curOrientation, $this->curPageFormat);
                $this->x = $x2;
            }
            $y = $this->y;
            $this->y += $h;
        }
        if ($x === null) {
            $x = $this->x;
        }
        $this->out(
            sprintf(
                'q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q',
                $w*$this->k,
                $h*$this->k,
                $x*$this->k,
                ($this->h-($y+$h))*$this->k,
                $info['i']
            )
        );
        if ($link) {
            $this->link($x, $y, $w, $h, $link);
        }
    }


    /**
     * Retorna a ultima posição de X
     * @return float
     */
    public function getX(): float
    {
        return $this->x;
    }


    /**
     * Seta a posição de X
     * @param float $x
     * @return void
     */
    public function setX($x)
    {
        if ($x >= 0) {
            $this->x = $x;
        } else {
            $this->x = $this->w + $x;
        }
    }


    /**
     * Retorna a posição de Y
     * @return float
     */
    public function getY(): float
    {
        return $this->y;
    }


    /**
     * Seta a posição de Y
     * @param float $y
     * @return void
     */
    public function setY(float $y)
    {
        $this->x = $this->lMargin;
        if ($y >= 0) {
            $this->y = $y;
        } else {
            $this->y = $this->h + $y;
        }
    }

    /**
     * Seta a posição de X e Y
     * @param float $x
     * @param float $y
     * @return void
     */
    public function setXY(float $x, float $y)
    {
        $this->setY($y);
        $this->setX($x);
    }

    /**
     * Retorna a string com o PDF
     * @return string
     */
    public function getPdf(): string
    {
        if ($this->state < 3) {
            $this->close();
        }
        return $this->buffer;
    }

    /**
     * Descarrega o PDF para download
     * @param string $name
     * @param string $dest
     * @return string
     * @throws Exception
     */
    public function output(string $name = '', string $dest = '')
    {
        //Output PDF to some destination
        if ($this->state < 3) {
            $this->close();
        }
        $dest = strtoupper($dest);
        if ($dest == '') {
            if ($name == '') {
                $name = 'doc.pdf';
                $dest = 'I';
            } else {
                $dest = 'F';
            }
        }
        switch ($dest) {
            case 'I':
                //Send to standard output
                if (ob_get_length()) {
                    $this->error('Some data has already been output, can\'t send PDF file');
                }
                if (php_sapi_name() !== 'cli') {
                    //We send to a browser
                    header('Content-Type: application/pdf');
                    if (headers_sent()) {
                        $this->error('Some data has already been output, can\'t send PDF file');
                    }
                    header('Content-Length: '.strlen($this->buffer));
                    header('Content-Disposition: inline; filename="'.$name.'"');
                    header('Cache-Control: private, max-age=0, must-revalidate');
                    header('Pragma: public');
                    ini_set('zlib.output_compression', '0');
                }
                echo $this->buffer;
                break;
            case 'D':
                //Download file
                if (ob_get_length()) {
                    $this->error('Some data has already been output, can\'t send PDF file');
                }
                header('Content-Type: application/x-download');
                if (headers_sent()) {
                    $this->error('Some data has already been output, can\'t send PDF file');
                }
                header('Content-Length: ' . strlen($this->buffer));
                header('Content-Disposition: attachment; filename="'.$name.'"');
                header('Cache-Control: private, max-age=0, must-revalidate');
                header('Pragma: public');
                ini_set('zlib.output_compression', '0');
                echo $this->buffer;
                break;
            case 'F':
                //Save to local file
                $f = fopen($name, 'wb');
                if (!$f) {
                    $this->error('Unable to create output file: '.$name);
                }
                fwrite($f, $this->buffer, strlen($this->buffer));
                fclose($f);
                break;
            case 'S':
                //Return as a string
                return $this->buffer;
            default:
                $this->error('Incorrect output destination: '.$dest);
        }
        return '';
    }

    /**
     * Checa a versão
     * @return void
     * @throws Exception
     */
    protected function dochecks()
    {
        //Check availability of %F
        if (sprintf('%.1F', 1.0) != '1.0') {
            $this->error('This version of PHP is not supported');
        }
    }


    /**
     * Retorno o formato escolhido da pagina
     * @param string $format
     * @return float[]|int[]
     * @throws Exception
     */
    protected function getpageformat(string $format)
    {
        $format = strtolower($format);
        if (!isset($this->pageFormats[$format])) {
            $this->error('Unknown page format: '.$format);
        }
        $a = $this->pageFormats[$format];
        return [$a[0] / $this->k, $a[1] / $this->k];
    }


    /**
     * Retorna o path das fontes
     * @return string
     */
    protected function getFontPath()
    {
        return __DIR__ . self::FPDF_FONTPATH;
    }


    /**
     * Inicia nova página
     * @param string $orientation
     * @param mixed $format
     * @return void
     * @throws Exception
     */
    protected function beginPage(string $orientation, mixed $format)
    {
        $this->page++;
        $this->pages[$this->page] = '';
        $this->state = 2;
        $this->x = $this->lMargin;
        $this->y = $this->tMargin;
        $this->fontFamily = '';
        //Check page size
        if ($orientation == '') {
            $orientation = $this->defOrientation;
        } else {
            $orientation = strtoupper($orientation[0]);
        }
        if ($format == '') {
            $format = $this->defPageFormat;
        } else {
            if (is_string($format)) {
                $format = $this->getpageformat($format);
            }
        }
        if ($orientation !== $this->curOrientation
            || $format[0] !== $this->curPageFormat[0]
            || $format[1] !== $this->curPageFormat[1]
        ) {
            //New size
            if ($orientation === 'P') {
                $this->w = $format[0];
                $this->h = $format[1];
            } else {
                $this->w = $format[1];
                $this->h = $format[0];
            }
            $this->wPt = $this->w * $this->k;
            $this->hPt = $this->h * $this->k;
            $this->pageBreakTrigger = $this->h - $this->bMargin;
            $this->curOrientation = $orientation;
            $this->curPageFormat = $format;
        }
        if ($orientation !== $this->defOrientation
            || $format[0] !== $this->defPageFormat[0]
            || $format[1] !== $this->defPageFormat[1]
        ) {
            $this->PageSizes[$this->page] = [$this->wPt, $this->hPt];
        }
    }

    /**
     * Finaliza a página
     * @return void
     */
    protected function endPage()
    {
        $this->state = 1;
    }


    /**
     * Escapa o texto
     * @param string $s
     * @return array|string|string[]
     */
    protected function escape(string $s)
    {
        //Escape special characters in strings
        $s = str_replace(array('\\', '(', ')', "\r"), array('\\\\', '\\(', '\\)', '\\r'), $s);
        return $s;
    }


    /**
     * Formata a string de texto
     * @param string $s
     * @return string
     */
    protected function textString(string $s)
    {
        return '('.$this->escape($s).')';
    }


    /**
     * Converte UTF-8 to UTF-16BE com BOM
     * @param string $s
     * @return string
     */
    protected function utf8Toutf16(string $s)
    {
        $res = "\xFE\xFF";
        $nb = strlen($s);
        $i = 0;
        while ($i < $nb) {
            $c1 = ord($s[$i++]);
            if ($c1 >= 224) {
                //3-byte character
                $c2 = ord($s[$i++]);
                $c3 = ord($s[$i++]);
                $res .= chr((($c1 & 0x0F)<<4) + (($c2 & 0x3C)>>2));
                $res .= chr((($c2 & 0x03)<<6) + ($c3 & 0x3F));
            } elseif ($c1 >= 192) {
                //2-byte character
                $c2 = ord($s[$i++]);
                $res .= chr(($c1 & 0x1C)>>2);
                $res .= chr((($c1 & 0x03)<<6) + ($c2 & 0x3F));
            } else {
                //Single-byte character
                $res .= "\0".chr($c1);
            }
        }
        return $res;
    }


    /**
     * Insere um texto com sublinhado
     * @param $x
     * @param $y
     * @param $txt
     * @return string
     */
    protected function doUnderLine(float $x, float $y, string $txt)
    {
        $up = $this->currentFont['up'];
        $ut = $this->currentFont['ut'];
        $w = $this->getStringWidth($txt)+$this->ws*substr_count($txt, ' ');
        return sprintf(
            '%.2F %.2F %.2F %.2F re f',
            $x * $this->k,
            ($this->h- ($y - $up / 1000 * $this->fontSize)) * $this->k,
            $w * $this->k,
            -$ut/1000 * $this->fontSizePt
        );
    }


    /**
     * Obtem informações de imagem JPG
     * @param string $file
     * @return array
     * @throws Exception
     */
    protected function parseJPG(string $file)
    {
        $a = getImageSize($file);
        if (!$a) {
            $this->error('Missing or incorrect image file: ' . $file);
        }
        if ($a[2] != 2) {
            $this->error('Not a JPEG file: ' . $file);
        }
        if (!isset($a['channels']) || $a['channels'] == 3) {
            $colspace = 'DeviceRGB';
        } elseif ($a['channels'] == 4) {
            $colspace = 'DeviceCMYK';
        } else {
            $colspace='DeviceGray';
        }
        $bpc = isset($a['bits']) ? $a['bits'] : 8;
        //Read whole file
        $f = fopen($file, 'rb');
        $data = '';
        while (!feof($f)) {
            $data .= fread($f, 8192);
        }
        fclose($f);
        return ['w'=>$a[0], 'h'=>$a[1], 'cs'=>$colspace, 'bpc'=>$bpc, 'f'=>'DCTDecode', 'data'=>$data];
    }


    /**
     * Obtem informações de imagem PNG
     * @param string $file
     * @return array
     * @throws Exception
     */
    protected function parsePNG(string $file)
    {
        //Extract info from a PNG file
        $f = fopen($file, 'rb');
        if (!$f) {
            $this->error('Can\'t open image file: ' . $file);
        }
        //Check signature
        if ($this->readstream($f, 8) !== chr(137)
            . 'PNG'
            . chr(13)
            . chr(10)
            . chr(26)
            . chr(10)
        ) {
            $this->error('Not a PNG file: '.$file);
        }
        //Read header chunk
        $this->readstream($f, 4);
        if ($this->readstream($f, 4) !== 'IHDR') {
            $this->error('Incorrect PNG file: '.$file);
        }
        $w = $this->readint($f);
        $h = $this->readint($f);
        $bpc = ord($this->readstream($f, 1));
        if ($bpc>8) {
            $this->error('16-bit depth not supported: '.$file);
        }
        $ct = ord($this->readstream($f, 1));
        if ($ct == 0) {
            $colspace = 'DeviceGray';
        } elseif ($ct == 2) {
            $colspace = 'DeviceRGB';
        } elseif ($ct == 3) {
            $colspace = 'Indexed';
        } else {
            $this->error('Alpha channel not supported: '.$file);
        }
        if (ord($this->readstream($f, 1)) != 0) {
            $this->error('Unknown compression method: '.$file);
        }
        if (ord($this->readstream($f, 1)) != 0) {
            $this->error('Unknown filter method: '.$file);
        }
        if (ord($this->readstream($f, 1)) != 0) {
            $this->error('Interlacing not supported: '.$file);
        }
        $this->readstream($f, 4);
        $parms = '/DecodeParms <</Predictor 15 /Colors '
            . ($ct==2 ? 3 : 1)
            . ' /BitsPerComponent '
            . $bpc
            . ' /Columns '
            . $w
            . '>>';
        //Scan chunks looking for palette, transparency and image data
        $pal = '';
        $trns = '';
        $data = '';
        do {
            $n = $this->readint($f);
            $type = $this->readstream($f, 4);
            if ($type === 'PLTE') {
                //Read palette
                $pal = $this->readstream($f, $n);
                $this->readstream($f, 4);
            } elseif ($type === 'tRNS') {
                //Read transparency info
                $t = $this->readstream($f, $n);
                if ($ct == 0) {
                    $trns = [ord(substr($t, 1, 1))];
                } elseif ($ct == 2) {
                    $trns = [ord(substr($t, 1, 1)), ord(substr($t, 3, 1)), ord(substr($t, 5, 1))];
                } else {
                    $pos = strpos($t, chr(0));
                    if ($pos !== false) {
                        $trns = [$pos];
                    }
                }
                $this->readstream($f, 4);
            } elseif ($type === 'IDAT') {
                //Read image data block
                $data .= $this->readstream($f, $n);
                $this->readstream($f, 4);
            } elseif ($type === 'IEND') {
                break;
            } else {
                $this->readstream($f, $n+4);
            }
        } while ($n);
        if ($colspace === 'Indexed' && empty($pal)) {
            $this->error('Missing palette in '.$file);
        }
        fclose($f);
        return [
            'w'=>$w,
            'h'=>$h,
            'cs'=>$colspace,
            'bpc'=>$bpc,
            'f'=>'FlateDecode',
            'parms'=>$parms,
            'pal'=>$pal,
            'trns'=>$trns,
            'data'=>$data
        ];
    }

    /**
     * Read n bytes from stream
     * @param $f
     * @param $n
     * @return string
     * @throws Exception
     */
    protected function readstream($f, $n)
    {
        //Read n bytes from stream
        $res='';
        while ($n > 0 && !feof($f)) {
            $s=fread($f, $n);
            if ($s === false) {
                $this->error('Error while reading stream');
            }
            $n -= strlen($s);
            $res .= $s;
        }
        if ($n > 0) {
            $this->error('Unexpected end of stream');
        }
        return $res;
    }


    /**
     * Read a 4-byte integer from stream
     * @param $f
     * @return mixed
     * @throws Exception
     */
    protected function readint($f)
    {
        //Read a 4-byte integer from stream
        $a = unpack('Ni', $this->readstream($f, 4));
        return $a['i'];
    }


    /**
     * Obtem dados de uma imagem GIF
     * @param string $file
     * @return array
     * @throws Exception
     */
    protected function parseGIF(string $file)
    {
        //Extract info from a GIF file (via PNG conversion)
        if (!function_exists('imagepng')) {
            $this->error('GD extension is required for GIF support');
        }
        if (!function_exists('imagecreatefromgif')) {
            $this->error('GD has no GIF read support');
        }
        $im = imagecreatefromgif($file);
        if (!$im) {
            $this->error('Missing or incorrect image file: '.$file);
        }
        imageinterlace($im, 0);
        $tmp = tempnam('.', 'gif');
        if (!$tmp) {
            $this->error('Unable to create a temporary file');
        }
        if (!imagepng($im, $tmp)) {
            $this->error('Error while saving to temporary file');
        }
        imagedestroy($im);
        $info = $this->parsePNG($tmp);
        unlink($tmp);
        return $info;
    }


    /**
     * Cria um novo objeto PDF
     * @return void
     */
    protected function newObj()
    {
        //Begin a new object
        $this->n++;
        $this->offsets[$this->n] = strlen($this->buffer);
        $this->out($this->n.' 0 obj');
    }


    /**
     * Descarrega o pdf em stream
     * @param string $s
     * @return void
     */
    protected function putStream(string $s)
    {
        $this->out('stream');
        $this->out($s);
        $this->out('endstream');
    }

    /**
     * Carrega o buffer do PDF
     * @param string $s
     * @return void
     */
    protected function out(string $s)
    {
        //Add a line to the document
        if ($this->state == 2) {
            $this->pages[$this->page].=$s."\n";
        } else {
            $this->buffer .= $s."\n";
        }
    }

    /**
     * Insere paginas
     * @return void
     */
    protected function putPages()
    {
        $nb = $this->page;
        if (!empty($this->aliasNbPages)) {
            //Replace number of pages
            for ($n=1; $n<=$nb; $n++) {
                $this->pages[$n] = str_replace($this->aliasNbPages, $nb, $this->pages[$n]);
            }
        }
        if ($this->defOrientation === 'P') {
            $wPt = $this->defPageFormat[0] * $this->k;
            $hPt = $this->defPageFormat[1] * $this->k;
        } else {
            $wPt = $this->defPageFormat[1] * $this->k;
            $hPt = $this->defPageFormat[0] * $this->k;
        }
        $filter = ($this->compress) ? '/Filter /FlateDecode ' : '';
        for ($n=1; $n <= $nb; $n++) {
            //Page
            $this->newObj();
            $this->out('<</Type /Page');
            $this->out('/Parent 1 0 R');
            if (isset($this->PageSizes[$n])) {
                $this->out(
                    sprintf(
                        '/MediaBox [0 0 %.2F %.2F]',
                        $this->PageSizes[$n][0],
                        $this->PageSizes[$n][1]
                    )
                );
            }
            $this->out('/Resources 2 0 R');
            if (isset($this->pageLinks[$n])) {
                //Links
                $annots = '/Annots [';
                foreach ($this->pageLinks[$n] as $pl) {
                    $rect = sprintf(
                        '%.2F %.2F %.2F %.2F',
                        $pl[0],
                        $pl[1],
                        $pl[0] + $pl[2],
                        $pl[1] - $pl[3]
                    );
                    $annots .= '<</Type /Annot /Subtype /Link /Rect ['.$rect.'] /Border [0 0 0] ';
                    if (is_string($pl[4])) {
                        $annots .= '/A <</S /URI /URI '.$this->textString($pl[4]).'>>>>';
                    } else {
                        $l = $this->links[$pl[4]];
                        $h = isset($this->PageSizes[$l[0]]) ? $this->PageSizes[$l[0]][1] : $hPt;
                        $annots .= sprintf(
                            '/Dest [%d 0 R /XYZ 0 %.2F null]>>',
                            1 + 2 * $l[0],
                            $h-$l[1] * $this->k
                        );
                    }
                }
                $this->out($annots.']');
            }
            $this->out('/Contents '.($this->n+1).' 0 R>>');
            $this->out('endobj');
            //Page content
            $p = ($this->compress) ? gzcompress($this->pages[$n]) : $this->pages[$n];
            $this->newObj();
            $this->out('<<'.$filter.'/Length '.strlen($p).'>>');
            $this->putStream($p);
            $this->out('endobj');
        }
        //Pages root
        $this->offsets[1]=strlen($this->buffer);
        $this->out('1 0 obj');
        $this->out('<</Type /Pages');
        $kids = '/Kids [';
        for ($i=0; $i<$nb; $i++) {
            $kids .= (3+2*$i).' 0 R ';
        }
        $this->out($kids.']');
        $this->out('/Count '.$nb);
        $this->out(sprintf('/MediaBox [0 0 %.2F %.2F]', $wPt, $hPt));
        $this->out('>>');
        $this->out('endobj');
    }


    /**
     * Insere fontes
     * @return void
     * @throws Exception
     */
    protected function putFonts()
    {
        $nf = $this->n;
        foreach ($this->diffs as $diff) {
            //Encodings
            $this->newObj();
            $this->out('<</Type /Encoding /BaseEncoding /WinAnsiEncoding /Differences ['.$diff.']>>');
            $this->out('endobj');
        }
        foreach ($this->fontFiles as $file => $info) {
            //Font file embedding
            $this->newObj();
            $this->fontFiles[$file]['n'] = $this->n;
            $font = '';
            $f = fopen($this->getFontPath().$file, 'rb', 1);
            if (!$f) {
                $this->error('Font file not found');
            }
            while (!feof($f)) {
                $font .= fread($f, 8192);
            }
            fclose($f);
            $compressed = (substr($file, -2) === '.z');
            if (!$compressed && isset($info['length2'])) {
                $header = (ord($font[0]) == 128);
                if ($header) {
                    //Strip first binary header
                    $font = substr($font, 6);
                }
                if ($header && ord($font[$info['length1']]) == 128) {
                    //Strip second binary header
                    $font = substr($font, 0, $info['length1']) . substr($font, $info['length1'] + 6);
                }
            }
            $this->out('<</Length '.strlen($font));
            if ($compressed) {
                $this->out('/Filter /FlateDecode');
            }
            $this->out('/Length1 '.$info['length1']);
            if (isset($info['length2'])) {
                $this->out('/Length2 '.$info['length2'].' /Length3 0');
            }
            $this->out('>>');
            $this->putStream($font);
            $this->out('endobj');
        }
        foreach ($this->fonts as $k => $font) {
            //Font objects
            $this->fonts[$k]['n']=$this->n+1;
            $type = $font['type'];
            $name = $font['name'];
            if ($type === 'core') {
                //Standard font
                $this->newObj();
                $this->out('<</Type /Font');
                $this->out('/BaseFont /'.$name);
                $this->out('/Subtype /Type1');
                if ($name !== 'Symbol' && $name !== 'ZapfDingbats' && $name !== 'textilesym') {
                    $this->out('/Encoding /WinAnsiEncoding');
                }
                $this->out('>>');
                $this->out('endobj');
            } elseif ($type === 'Type1' || $type === 'TrueType') {
                //Additional Type1 or TrueType font
                $this->newObj();
                $this->out('<</Type /Font');
                $this->out('/BaseFont /'.$name);
                $this->out('/Subtype /'.$type);
                $this->out('/FirstChar 32 /LastChar 255');
                $this->out('/Widths '.($this->n+1).' 0 R');
                $this->out('/FontDescriptor '.($this->n+2).' 0 R');
                if ($font['enc']) {
                    if (isset($font['diff'])) {
                        $this->out('/Encoding '.($nf+$font['diff']).' 0 R');
                    } else {
                        $this->out('/Encoding /WinAnsiEncoding');
                    }
                }
                $this->out('>>');
                $this->out('endobj');
                //Widths
                $this->newObj();
                $cw =& $font['cw'];
                $s = '[';
                for ($i = 32; $i <= 255; $i++) {
                    $s .= $cw[chr($i)] . ' ';
                }
                $this->out($s.']');
                $this->out('endobj');
                //Descriptor
                $this->newObj();
                $s = '<</Type /FontDescriptor /FontName /'.$name;
                foreach ($font['desc'] as $key => $v) {
                    $s .= ' /' . $key . ' ' . $v;
                }
                $file=$font['file'];
                if ($file) {
                    $s .= ' /FontFile' . ($type === 'Type1' ? '' : '2') . ' ' . $this->fontFiles[$file]['n'] . ' 0 R';
                }
                $this->out($s . '>>');
                $this->out('endobj');
            } else {
                //Allow for additional types
                $mtd = '_put' . strtolower($type);
                if (!method_exists($this, $mtd)) {
                    $this->error('Unsupported font type: '.$type);
                }
                $this->$mtd($font);
            }
        }
    }

    /**
     * Insere as imagens
     * @return void
     */
    protected function putImages()
    {
        $filter = ($this->compress) ? '/Filter /FlateDecode ' : '';
        reset($this->images);
        foreach ($this->images as $file => $info) {
            $this->newObj();
            $this->images[$file]['n'] = $this->n;
            $this->out('<</Type /XObject');
            $this->out('/Subtype /Image');
            $this->out('/Width ' . $info['w']);
            $this->out('/Height ' . $info['h']);
            if ($info['cs'] ==='Indexed') {
                $this->out('/ColorSpace [/Indexed /DeviceRGB '
                    . (strlen($info['pal'])/3-1)
                    . ' '
                    . ($this->n+1).' 0 R]'
                );
            } else {
                $this->out('/ColorSpace /' . $info['cs']);
                if ($info['cs'] ==='DeviceCMYK') {
                    $this->out('/Decode [1 0 1 0 1 0 1 0]');
                }
            }
            $this->out('/BitsPerComponent ' . $info['bpc']);
            if (isset($info['f'])) {
                $this->out('/Filter /' . $info['f']);
            }
            if (isset($info['parms'])) {
                $this->out($info['parms']);
            }
            if (isset($info['trns']) && is_array($info['trns'])) {
                $trns = '';
                foreach($info['trns'] as $t) {
                    $trns .= $t . ' ' . $t . ' ';
                }
                //for ($i = 0, $iMax = count($info['trns']); $i< $iMax; $i++) {
                //    $trns.=$info['trns'][$i] . ' '.$info['trns'][$i] . ' ';
                //}
                $this->out('/Mask [' . $trns . ']');
            }
            $this->out('/Length ' . strlen($info['data']) . '>>');
            $this->putStream($info['data']);
            unset($this->images[$file]['data']);
            $this->out('endobj');
            //Palette
            if ($info['cs'] === 'Indexed') {
                $this->newObj();
                $pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
                $this->out('<<'.$filter.'/Length '.strlen($pal).'>>');
                $this->putStream($pal);
                $this->out('endobj');
            }
        }
    }

    /**
     * Insere o dicionario de objetos
     * @return void
     */
    protected function putXobjectDict()
    {
        foreach ($this->images as $image) {
            $this->out('/I' . $image['i'] . ' ' . $image['n'] . ' 0 R');
        }
    }

    /**
     * Insere o dicionario de recursos
     * @return void
     */
    protected function putResourceDict()
    {
        $this->out('/ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
        $this->out('/Font <<');
        foreach ($this->fonts as $font) {
            $this->out('/F' . $font['i'] . ' ' . $font['n'] . ' 0 R');
        }
        $this->out('>>');
        $this->out('/XObject <<');
        $this->putXobjectDict();
        $this->out('>>');
    }

    /**
     * Insere os recursos
     * @return void
     */
    protected function putResources()
    {
        $this->putFonts();
        $this->putImages();
        //Resource dictionary
        $this->offsets[2] = strlen($this->buffer);
        $this->out('2 0 obj');
        $this->out('<<');
        $this->putResourceDict();
        $this->out('>>');
        $this->out('endobj');
    }

    /**
     * Insere o info
     * @return void
     */
    protected function putInfo()
    {
        $this->out('/Producer '.$this->textString('FPDF '. self::FPDF_VERSION));
        if (!empty($this->title)) {
            $this->out('/Title '.$this->textString($this->title));
        }
        if (!empty($this->subject)) {
            $this->out('/Subject '.$this->textString($this->subject));
        }
        if (!empty($this->author)) {
            $this->out('/Author '.$this->textString($this->author));
        }
        if (!empty($this->keywords)) {
            $this->out('/Keywords '.$this->textString($this->keywords));
        }
        if (!empty($this->creator)) {
            $this->out('/Creator '.$this->textString($this->creator));
        }
        $this->out('/CreationDate '.$this->textString('D:'.@date('YmdHis')));
    }

    /**
     * Insere o catalogo
     * @return void
     */
    protected function putCatalog()
    {
        $this->out('/Type /Catalog');
        $this->out('/Pages 1 0 R');
        if ($this->zoomMode === 'fullpage') {
            $this->out('/OpenAction [3 0 R /Fit]');
        } elseif ($this->zoomMode === 'fullwidth') {
            $this->out('/OpenAction [3 0 R /FitH null]');
        } elseif ($this->zoomMode === 'real') {
            $this->out('/OpenAction [3 0 R /XYZ null null 1]');
        } elseif (!is_string($this->zoomMode)) {
            $this->out('/OpenAction [3 0 R /XYZ null null '.($this->zoomMode/100).']');
        }
        if ($this->layoutMode === 'single') {
            $this->out('/PageLayout /SinglePage');
        } elseif ($this->layoutMode === 'continuous') {
            $this->out('/PageLayout /OneColumn');
        } elseif ($this->layoutMode === 'two') {
            $this->out('/PageLayout /TwoColumnLeft');
        }
    }

    /**
     * Insere o header
     * @return void
     */
    protected function putHeader()
    {
        $this->out('%PDF-'.$this->pdfVersion);
    }

    /**
     * Insere o trailer
     * @return void
     */
    protected function putTrailer()
    {
        $this->out('/Size '.($this->n+1));
        $this->out('/Root '.$this->n.' 0 R');
        $this->out('/Info '.($this->n-1).' 0 R');
    }

    /**
     * Finaliza o documento
     * @return void
     */
    protected function endDoc()
    {
        $this->putHeader();
        $this->putPages();
        $this->putResources();
        //Info
        $this->newObj();
        $this->out('<<');
        $this->putInfo();
        $this->out('>>');
        $this->out('endobj');
        //Catalog
        $this->newObj();
        $this->out('<<');
        $this->putCatalog();
        $this->out('>>');
        $this->out('endobj');
        //Cross-ref
        $o=strlen($this->buffer);
        $this->out('xref');
        $this->out('0 '.($this->n+1));
        $this->out('0000000000 65535 f ');
        for ($i=1; $i<=$this->n; $i++) {
            $this->out(sprintf('%010d 00000 n ', $this->offsets[$i]));
        }
        //Trailer
        $this->out('trailer');
        $this->out('<<');
        $this->putTrailer();
        $this->out('>>');
        $this->out('startxref');
        $this->out($o);
        $this->out('%%EOF');
        $this->state=3;
    }
}
