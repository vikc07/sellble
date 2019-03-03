<?php
App::uses('Component', 'Controller');

class BarcodeComponent extends Component{
    public function initialize(Controller $controller) {
        $this->controller = $controller;
    }

    public function generate($text){
        require_once( 'barcode/class/BCGFontFile.php' );
        require_once( 'barcode/class/BCGColor.php' );
        require_once( 'barcode/class/BCGDrawing.php' );

        // Including the barcode technology
        require_once( 'barcode/class/BCGcode39.barcode.php' );

        // Loading Font
        $font = new BCGFontFile(dirname(__FILE__) . '/barcode/font/Arial.ttf', 7);

        if( !$text ){
            die("Text required");
        }

        // The arguments are R, G, B for color.
        $color_black = new BCGColor( 0, 0, 0 );
        $color_white = new BCGColor( 255, 255, 255 );

        $drawException = null;
        try {
            $code = new BCGcode39();
            $code->setScale(1); // Resolution - QL700 Resolution 2
            $code->setThickness(40); // Thickness - QL700 Thickness
            $code->setForegroundColor($color_black); // Color of bars
            $code->setBackgroundColor($color_white); // Color of spaces
            $code->setFont(0); // Font (or 0)
            $code->parse($text); // Text
        }
        catch(Exception $exception) {
            $drawException = $exception;
        }

        /* Here is the list of the arguments
        1 - Filename (empty : display on screen)
        2 - Background color */
        $drawing = new BCGDrawing(IMAGES . $text .'.png', $color_white);
        if($drawException) {
            $drawing->drawException( $drawException );
        }
        else {
            $drawing->setBarcode($code);
            $drawing->draw();
        }

        // Draw (or save) the image into PNG format.
        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
    }
}