<?php

class HomeController extends MainController {

    private $contentModel;
    private $contentMetadataModel;
    private $TOC;
    private $markdownFormatter;

    public function __construct(
    ContentModel $contentModel,
    ContentMetadataModel $contentMetadataModel,
    MarkdownFormatter $markdownFormatter,
    TOC $TOC) {
        $this->contentModel = $contentModel;
        $this->contentMetadataModel = $contentMetadataModel;
        
        $this->markdownFormatter = $markdownFormatter;
        $this->TOC = $TOC;
    }

    public function main($method, $vars = null) {

        if(method_exists($this, $method)) {
            $this->$method($vars);
        }
    }

    public function index($vars = null) {

        /**
         * Home Intro
         */

        
        $slug = 'home-intro';

        $get_content = $this->contentModel->get_content_by_slug($slug);

        if(!$slug || !$get_content['status']) {
            $this->error_404();
            exit;
        }

        $intro_content = $get_content['result'];

        $props = [
            'id' => $intro_content['id'],
            'title' => "PhotoValiant | Nature Photography by Abhinav Raveendran",
            'excerpt' => "Photography is a great tool to document and learn about biodiversity, ecology, and nature. Beyong that, it is artistically rewarding too.",
        ];

        $props['intro_content_html'] = $this->markdownFormatter->content_html($intro_content['body'], false);

        /**
         * Home Intro Part 2
         */

        $slug = 'home-intro-part-2';

        $get_content = $this->contentModel->get_content_by_slug($slug);

        if(!$slug || !$get_content['status']) {
            $this->error_404();
            exit;
        }

        $intro_content = $get_content['result'];

        $props['intro_content_html_part_2'] = $this->markdownFormatter->content_html($intro_content['body'], false);

        /**
         * Home Gallery
         */

        $args = [
            "per_page" => 10,
            "page_no" => 1,
            "type" => 'gallery',
            "fields" => ["id", "title", "excerpt", "published_date", "modified_date", "featured_image", "slug"]
        ];

        $galleries = $this->contentModel->get_contents($args);       

        foreach($galleries as $gallery) {

            $id = (int)$gallery['id'];
            $published_obj = DateTime::createFromFormat('Y-m-d H:i:s', $gallery['published_date']);
            $published_date = $published_obj->format('M j, Y');

            $modified_obj = DateTime::createFromFormat('Y-m-d H:i:s', $gallery['modified_date']);
            $modified_date = $modified_obj->format('M j, Y');

            $props['galleries'][$id] = [
                'id' => $gallery['id'],
                'title' => $gallery['title'],
                'excerpt' => $gallery['excerpt'],
                'published_date' => $published_date,
                'modified_date' => $modified_date,
                'featured_image' => $gallery['featured_image'],
                'slug' => $gallery['slug'],
                'categories' => [],
                'tags' => []
            ];
            
        }

        /**
         * Home FAQ
         */

        $slug = 'home-faq';

        $get_content = $this->contentModel->get_content_by_slug($slug);

        if(!$slug || !$get_content['status']) {
            $this->error_404();
            exit;
        }

        $faq_content = $get_content['result'];

        $props['faq_content_html'] = $this->markdownFormatter->content_html($faq_content['body'], false);

        /**
         * Home FAQ Description
         */

        $slug = 'home-faq-description';

        $get_content = $this->contentModel->get_content_by_slug($slug);

        if(!$slug || !$get_content['status']) {
            $this->error_404();
            exit;
        }

        $faq_desc_content = $get_content['result'];

        $props['faq_desc_content_html'] = $this->markdownFormatter->content_html($faq_desc_content['body'], false);

        /**
         * Fetch SEO data
         * Prepare the SEO Data Array and add it to props
         * Prepare the JSON-LD Markup and add it to props
         */
        
        $props['seo_data'] = [
            'title' => "PhotoValiant | Nature Photography by Abhinav Raveendran",
            "canonical" => "https://photovaliant.com/",
        ];

        $props['seo_data']['meta_names']['description'] = "Photography is a great tool to document and learn about biodiversity, ecology, and nature. Beyong that, it is artistically rewarding too.";

        $get_content_seo = $this->contentMetadataModel->get_content_seo_data($intro_content['id'], 'page');

        if($get_content_seo['status']) {

            $title = $description = '';

            $content_seo = $get_content_seo['result'];
            foreach($content_seo as $row) {
                switch($row['meta_key']) {
                    case 'seo_title' :
                        $title = $row['meta_value'];
                        break;
                    case 'meta_description' :
                        $description = $row['meta_value'];
                        break;
                }
            }

            $row = $content_seo[0];

            $props['seo_data'][] = [
                "meta_names" => [
                    "description" => $description,
                ],
                
            ];
        }

        $this->render('header', $props);
        $this->render('home-page', $props);
        $this->render('footer', $props);
        
    }

}