<?php

class EditorParser {
    public $jsonData;
    public $dataArray;
    public $html;

    public function __construct() {}

    public function json2html($jsonData) {
        $this->jsonData = $jsonData;
        $this->html = '';

        $this->jsonToArray();
        $this->parseArray();

        // var_dump($this->html);
        return $this->html;
    }

    private function jsonToArray() {
        $this->dataArray = json_decode($this->jsonData, true);
        // var_dump($this->dataArray);
    }

    private function parseArray() {
        foreach($this->dataArray['blocks'] as $block) {
            switch($block['type']) {
                case 'paragraph':
                    $this->paragraph($block);
                    break;
                case 'header':
                    $this->header($block);
                    break;
                case 'list':
                    $this->list($block);
                    break;
                case 'image':
                    $this->image($block);
                    break;                   
            }
        }
    }

    private function paragraph ($block) {
        $text = $block['data']['text'];
        $html = '<p>' . $text . '</p>';
        $this->html .= $html;
    }

    private function header ($block) {
        $text = $block['data']['text'];
        $level = $block['data']['level'];

        $html = '<h' . $level . '>' . $text . '</h' . $level . '>';

        $this->html .= $html;
    }

    private function list ($block) {
        $style = $block['data']['style'];

        $tag = 'ul';

        if($style === 'ordered') {
            $tag = 'ol';
        }

        $html = '<' . $tag . '>';

        foreach($block['data']['items'] as $item) {
            $html .= '<li>' . $item . '</li>';
        }

        $html .= '</' . $tag . '>';

        $this->html .= $html;
    }

    private function image ($block) {

        $url = $block['data']['file']['url'];
        $caption = $block['data']['caption'];
        $withBorder = $block['data']['withBorder'];
        $stretched = $block['data']['stretched'];
        $withBackground = $block['data']['withBackground'];

        $html = '<figure class="content-image';

        if($withBorder) {
            $html .= ' with-border';
        }
        if($stretched) {
            $html .= ' stretched';
        }
        if($withBackground) {
            $html .= ' with-background';
        }

        $html .= '">';

        $html .= '<img src="' . $url . '" alt="' . $caption . '">';

        if(!empty($caption)) {
            $html .= '<figcaption>' . $caption . '</figcaption>';
        }

        $html .= '</figure>';
        $this->html .= $html;

    }
}