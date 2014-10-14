<?php
class Grid extends BaseGrid{

    public function __construct(){
        parent::__construct();
    }

    public function showItem($shiftFirst = true){
        echo '
        <div class="lm">
            <div class="mini-section">
                '.(Chapters::$current['item']['teaser'] ? '<div class="section">'.decodeHTMLEntities(Chapters::$current['item']['teaser']).'</div>' : '').'
                '.(Chapters::$current['item']['body'] ? '<div class="text section">'.decodeHTMLEntities(Chapters::$current['item']['body']).'</div>' : '').'
                <div class="clear"></div>
            </div>';

        echo Templates::parse('{{itemGallery}}', true);

        echo '</div>';
    }
}
?>