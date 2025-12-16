<?php

use League\HTMLToMarkdown\HtmlConverter;

class ExtractionController extends MainController
{

    public $contentModel;
    public $dom;
    public $xpath;
    public $htmlConverter;
    public $htmlConverter2;
    public $parsedown;

    public function __construct(
        ContentModel $contentModel,
        HtmlConverter $htmlConverter,
        HtmlConverter $htmlConverter2,
        Parsedown $parsedown
    ) {
        $this->contentModel = $contentModel;
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->htmlConverter = $htmlConverter;
        $this->htmlConverter2 = $htmlConverter2;
        $this->parsedown = $parsedown;

    }

    public function main($method, $vars = null)
    {

        if (method_exists($this, $method)) {
            $this->$method($vars);
        }
    }

    private function getMariadbDateTime($timestamp)
    {
        $dt = new DateTime($timestamp);
        $dt->setTimezone(new DateTimeZone('UTC'));

        $mariadbDatetime = $dt->format('Y-m-d H:i:s');

        return $mariadbDatetime;

    }

    public function handleSlug($s)
    {

        $data = [];

        $data['slug'] = $s;

        $html = file_get_contents("./../temp/$s.html");
        @$this->dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NODEFDTD);

        $this->xpath = new DOMXpath($this->dom);

        $pageTitleNodeList = $this->xpath->query("//h1[contains(concat(' ', normalize-space(@class), ' '), ' page-title ')]");

        $pageTitle = $pageTitleNodeList->item(0)->nodeValue;

        $data['title'] = $pageTitle;

        $data['published_date'] = $data['modified_date'] = "2015-01-10 00:00:00";

        $publishedDateNodeList = $this->xpath->query('//meta[@property="article:published_time"]');

        if ($publishedDateNodeList->count() > 0) {
            $publishedDateNode = $publishedDateNodeList->item(0);
            $publishedDateISO = $publishedDateNode->getAttribute('content');
            $publishedDate = $this->getMariadbDateTime($publishedDateISO);
            $data['published_date'] = $publishedDate;
        }

        $modifiedDateNodeList = $this->xpath->query('//meta[@property="article:modified_time"]');

        if ($modifiedDateNodeList->count() > 0) {
            $modifiedDateNode = $modifiedDateNodeList->item(0);
            $modifiedDateISO = $modifiedDateNode->getAttribute('content');
            $modifiedDate = $this->getMariadbDateTime($modifiedDateISO);
            $data['modified_date'] = $modifiedDate;
        }

        $data['featured_image'] = "";

        $featuredImageNodeList = $this->xpath->query('//meta[@property="og:image"]');

        if ($featuredImageNodeList->count() > 0) {
            $featuredImageNode = $featuredImageNodeList->item(0);
            $featuredImage = str_replace("https://cdn.photovaliant.com/wp-content/uploads/", "", $featuredImageNode->getAttribute('content'));
            $data['featured_image'] = $featuredImage;
        }

        $data['excerpt'] = "";

        $excerptNodeList = $this->xpath->query('//meta[@property="og:description"]');

        if ($excerptNodeList->count() > 0) {
            $excerptNode = $excerptNodeList->item(0);
            $excerpt = $excerptNode->getAttribute('content');
            $data['excerpt'] = $excerpt;
        }

        $entryContent = $this->xpath->query("//div[contains(concat(' ', normalize-space(@class), ' '), ' entry-content ')]");

        $entryNode = $entryContent->item(0);

        $imageNodes = $entryNode->getElementsByTagName('img');

        $images = [];

        if ($imageNodes->count() > 0) {
            foreach ($imageNodes as $imageNode) {

                $src = str_replace("cdn.photovaliant.com/wp-content", "cdn-2.photovaliant.com/photovaliant", $imageNode->getAttribute('data-src'));

                $srcSet = str_replace("cdn.photovaliant.com/wp-content", "cdn-2.photovaliant.com/photovaliant", $imageNode->getAttribute('data-srcset'));

                $alt = !empty($imageNode->getAttribute('alt')) ? $imageNode->getAttribute('alt') : "";

                $images[$src]['src'] = $src;
                $images[$src]['srcset'] = $srcSet;
                $images[$src]['alt'] = $alt;
                if ($imageNode->parentNode->nodeName === 'a') {
                    $href = str_replace("cdn.photovaliant.com/wp-content", "cdn-2.photovaliant.com/photovaliant", $imageNode->parentNode->getAttribute('href'));
                    $images[$src]['href'] = $href;
                    $imageNode->parentNode->setAttribute('href', $href);
                } else {
                    $images[$src]['href'] = "#";
                }
                $imageNode->setAttribute('src', $src);

                $images[$src]['caption'] = '';

                if ($imageNode->parentNode->nodeName === 'a') {
                    if ($imageNode->parentNode->parentNode->nodeName === 'figure') {
                        $figcaptionNode = $imageNode->parentNode->nextSibling;
                        if ($figcaptionNode) {
                            $images[$src]['caption'] = $figcaptionNode->textContent;
                            $figcaptionNode->remove();
                        }
                    }
                } else {
                    if ($imageNode->parentNode->nodeName === 'figure') {
                        $figcaptionNode = $imageNode->nextSibling;
                        if ($figcaptionNode) {
                            $images[$src]['caption'] = $figcaptionNode->textContent;
                            $figcaptionNode->remove();
                        }
                    }
                }
            }
        }

        $eh = $this->dom->saveHTML($entryNode);
        $entryHtml = str_replace("\n\n", "\n", $eh);
        file_put_contents("entry-1.html", $entryHtml);
        $markdown = $this->htmlConverter->convert($entryHtml);

        $markdown = preg_replace(
            '/(\[!\[[^\]]*\]\([^)]+\)\]\([^)]+\)|!\[[^\]]*\]\([^)]+\)|<a\b[^>]*>\s*<img\b[^>]*>\s*<\/a>|<img\b[^>]*>)(?=[^\s\]\)])/i',
            "$1\n\n",
            $markdown
        );



        file_put_contents("markdown-1.md", $markdown);
        $entryHtml2 = $this->parsedown->text($markdown);

        @$this->dom->loadHTML(mb_convert_encoding($entryHtml2, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NODEFDTD);

        $this->xpath = new DOMXpath($this->dom);

        $imgNodes = $this->xpath->query("//img");

        foreach ($imgNodes as $img) {
            $src = $img->getAttribute('src');
            $figureElem = $this->dom->createElement('figure');
            $anchorElem = $this->dom->createElement('custom-anchor');
            $imgElem = $this->dom->createElement('custom-image');
            $figcaptionElem = $this->dom->createElement('figcaption');
            $anchorElem->setAttribute('href', $images[$src]['href']);
            $imgElem->setAttribute('src', $src);
            $imgElem->setAttribute('srcset', $images[$src]['srcset']);
            $imgElem->setAttribute('alt', $images[$src]['alt']);
            $figcaptionElem->textContent = $images[$src]['caption'];

            $anchorElem->appendChild($imgElem);
            $figureElem->appendChild($anchorElem);
            $figureElem->appendChild($figcaptionElem);

            if ($img->parentNode->nodeName === 'a') {
                $img->parentNode->parentNode->insertBefore($figureElem, $img->parentNode);
            } else {
                $img->parentNode->insertBefore($figureElem, $img);
            }
        }

        $imgNodesArray = [];
        foreach ($imgNodes as $img) {
            $imgNodesArray[] = $img;
        }

        foreach ($imgNodesArray as $value) {
            if ($value->parentNode->nodeName === 'a') {
                $value->parentNode->remove();
            } else {
                $value->remove();
            }
        }

        $entryHtml3 = $this->dom->saveHTML();
        $markdown2 = $this->htmlConverter2->convert($entryHtml3);

        $markdown3 = str_replace(
            ["<html><body>", "<custom-anchor", "<custom-image", "</custom-image>", "</custom-anchor>", "</body></html>"],
            ["", "<a", "<img", "", "</a>", ""],
            $markdown2
        );

        file_put_contents("entry-html.html", $entryHtml3);
        file_put_contents("entry-markdown", $markdown3);

        $data['body'] = $markdown3;
        $data['author'] = 'abhinavr';
        $data['status'] = 'publish';
        $data['type'] = 'article';

        $this->contentModel->insert_content($data);
    }

    public function index()
    {
        $slugs = file_get_contents("./../temp/slugs.txt");
        $slugsArray = explode("\n", $slugs);

        foreach ($slugsArray as $s) {
            $this->handleSlug($s);
        }
    }
}