<?php

/**
 * SMW_Helpers_Pagination - Controls the admin settings page
 * 
 * @package SMW
 * @subpackage Helpers
 * @author  Anthony Humes
 **/
class SMW_Helpers_Pagination {
    /**
     * This variable is set in getInstance and stores the current page
     * 
     * @access protected
     * @var int The current page
     */
    public $current_page;
    /**
     * This variable is set in getInstance and stores the number of items per page
     * 
     * @access protected
     * @var int The number of items per page
     */
    public $per_page;
    /**
     * This variable is set in getInstance and stores the total number of items
     * 
     * @access protected
     * @var int The total number of item
     */
    public $total_count;
    /**
     * This variable is set in getInstance and stores the total number of pages
     * 
     * @access protected
     * @var int The total number of pages
     */
    public $total_pages;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access   public
     * @param    int $page What page is the pagination currently on. Defaults to 1
     * @param    int $per_page Number of items per page
     * @param    int $total_count Total number of items
     * @return   void
     */
    public function __construct($page=1, $per_page=20, $total_count=0){
        $this->url          = SMW::getHelper('url');
        $this->current_page = (int)$page;
        $this->per_page     = (int)$per_page;
        $this->total_count  = (int)$total_count;
        $this->total_pages  = $this->total_pages();
        $this->offset       = $this->offset();
    }
    /**
     * Gives the offset for the pagination based on the current page and the amount per page
     * 
     * @access   public
     * @return   int The offset for the pagination
     */
    public function offset() {
        // Assuming 20 items per page:
        // page 1 has an offset of 0    (1-1) * 20
        // page 2 has an offset of 20   (2-1) * 20
        //   in other words, page 2 starts with item 21
        return ($this->current_page - 1) * $this->per_page;
    }
    /**
     * Returns the total number of pages based on the total number of items and items per page
     * 
     * @access   public
     * @return   int The total number of pages
     */
    public function total_pages() {
        if($this->per_page == 0) {
            $this->total_count = 1;
        } else {
            return ceil($this->total_count/$this->per_page); 		
        }

    }
    /**
     * Returns the previous page
     * 
     * @access  public
     * @return  int
     */
    public function previous_page() {
        return $this->current_page - 1;
    }
    /**
     * Returns the next page
     * 
     * @access  public
     * @return  int
     */
    public function next_page() {
        return $this->current_page + 1;
    }
    /**
     * Return whether or not the page has a page before it
     * 
     * @access  public
     * @return  bool
     */
    public function has_previous_page() {
        return $this->previous_page() >= 1 ? true : false;
    }
    /**
     * Return whether or not the page has a page after it
     * 
     * @access  public
     * @return  bool
     */
    public function has_next_page() {
        return $this->next_page() <= $this->total_pages() ? true : false;
    }
    /**
     * Echos a dropdown which is used to choose the number of items per page
     * 
     * @access  public
     * @param   array $item_array An array of numbers
     * @return  html The total number of pages
     */
    public function items_per_page($item_array) {
        echo '<select name="imgPage" id="imgPage">';
        foreach($item_array as $item) {
            if((int)$item) {
                if($this->per_page == $item){
                    echo '<option value="'.$item.'" selected="selected">'.$item.'</option>';
                } else {
                    echo '<option value="'.$item.'">'.$item.'</option>';
                }
            } elseif($item == 'All') {
                if($this->per_page == $item){
                    echo '<option value="'.$item.'" selected="selected">'.$item.'</option>';
                } else {
                    echo '<option value="'.$item.'">'.$item.'</option>';
            }
            }
        }
        echo '</select>';
    }
    /**
     * Creates the ajax pagination navigation
     * 
     * @access  public
     * @param   int $currentPage The page number that the pagination is currently on
     * @param   int $range The range for the pagination
     * @param   int $totalPages Total number of pages
     * @return  html The pagination navigation for ajax
     */

    public function create_pagination_ajax($range) {
        
        if($this->current_page != 1) {
            $pagination .= '<a href="#/page/'.($this->current_page - 1).'" rel="address:/page/'.($this->current_page - 1).'" class="page">&laquo; Previous</a>';
        }
        if($this->current_page >= ($range + 3)) {
            $pagination .= '<a href="#/page/1" rel="address:/page/1" class="page">1</a>';
            $pagination .= '<a href="#/page/'.$this->total_pages.'" rel="address:/page/'.$this->total_pages.'" class="ellipse">...</a>';
        } elseif ($this->current_page == ($range + 2)) {
            $pagination .= '<a href="#/page/1" rel="address:/page/1" class="page">1</a>';
        }
        for($i = ($this->current_page - $range);$i < (($this->current_page + $range) + 1); $i++) {
            if(($i > 0) && ($i <= $this->total_pages)){
                if($i == $this->current_page){
                    $pagination .= '<span href="#/page/'.$i.'" rel="address:/page/'.$i.'" class="current">'.$i.'</span>';
                } else {
                    $pagination .= '<a href="#/page/'.$i.'" rel="address:/page/'.$i.'" class="page">'.$i.'</a>';
                }
            }
        }
        if($this->current_page < ($this->total_pages - $range - 1)) {
            $pagination .= '<a href="#/page/'.$this->total_pages.'" rel="address:/page/'.$this->total_pages.'" class="ellipse">...</a>';
            $pagination .= '<a href="#/page/'.$this->total_pages.'" rel="address:/page/'.$this->total_pages.'" class="page">'.$this->total_pages.'</a>';
        } elseif ($this->current_page == ($this->total_pages - $range - 1)) {
            $pagination .= '<a href="#/page/'.$this->total_pages.'" irel="address:/page/'.$this->total_pages.'" class="page">'.$this->total_pages.'</a>';
        }
        if($this->current_page != $this->total_pages) {
            $pagination .= '<a href="#/page/'.($this->current_page + 1).'" rel="address:/page/'.($this->current_page + 1).'" class="page" style="margin-right:0;">Next &raquo;</a>';
        }
        return $pagination;
    }
    function curPageURL() {

    }
    /**
     * Creates the static pagination navigation
     * 
     * @access  public
     * @param   int $range
     * @return  int The total number of pages
     */
    public function create_pagination_static($range) {
        
        //$pageURL = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
        
        if($_SERVER['QUERY_STRING']) {
            if(get_query_var('paged')) {
                $query = $this->url->remove_querystring_var($_SERVER['QUERY_STRING'],'paged');
                $current_url = $this->url->parse($url,'all') . '?' . $query;
                $pageURL = $current_url . '&paged=';
            } else {
                $current_url = $this->url->parse($_SERVER['REQUEST_URI'],'all_query');
                $pageURL = $current_url . '&paged=';
            }
            if($this->current_page != 1) {
                if(($this->current_page - 1) == 1) {
                    $pagination .= '<a href="'.$current_url.'" id="'.($this->current_page - 1).'" class="page previous">&laquo; Previous</a>';
                } else {
                    $pagination .= '<a href="'.$pageURL.($this->current_page - 1).'" id="'.($this->current_page - 1).'" class="page previous">&laquo; Previous</a>';
                }
            }
            if($this->current_page >= ($range + 3)) {
                $pagination .= '<a href="'.$current_url.'" id="1" class="page">1</a>';
                $pagination .= '<span id="'.$this->total_pages.'" class="ellipse">...</span>';
            } elseif ($this->current_page == ($range + 2)) {
                $pagination .= '<a href="'.$current_url.'" id="1" class="page">1</a>';
            }
            for($i = ($this->current_page - $range);$i < (($this->current_page + $range) + 1); $i++) {
                if(($i > 0) && ($i <= $this->total_pages)){
                    if($i == $this->current_page){
                        $pagination .= '<span href="'.$pageURL.$i.'" id="'.$i.'" class="current">'.$i.'</span>';
                    } else {
                        if($i == 1) {
                            $pagination .= '<a href="'.$current_url.'" id="'.$i.'" class="page">'.$i.'</a>';
                        } else {
                            $pagination .= '<a href="'.$pageURL.$i.'" id="'.$i.'" class="page">'.$i.'</a>';
                        }

                    }
                }
            }
            if($this->current_page < ($this->total_pages - $range - 1)) {
                $pagination .= '<span id="'.$this->total_pages.'" class="ellipse">...</span>';
                $pagination .= '<a href="'.$pageURL.$this->total_pages.'" id="'.$this->total_pages.'" class="page">'.$this->total_pages.'</a>';
            } elseif ($this->current_page == ($this->total_pages - $range - 1)) {
                $pagination .= '<a href="'.$pageURL.$this->total_pages.'" id="'.$this->total_pages.'" class="page">'.$this->total_pages.'</a>';
            }
            if($this->current_page != $this->total_pages) {
                $pagination .= '<a href="'.$pageURL.($this->current_page + 1).'" id="'.($this->current_page + 1).'" class="page next" style="margin-right:0;">Next &raquo;</a>';
            }
        } else {
            if(get_query_var('paged')) {
                $url = str_replace('//', '/', $_SERVER['REQUEST_URI']);
                $url = str_replace('/page/' . get_query_var('paged'), '', $url);
                $current_url = $this->url->parse($url,'all');
                $pageURL = $current_url . '/page/';
            } else {
                $current_url = $this->url->parse($_SERVER['REQUEST_URI'],'all');
                $pageURL = $current_url . '/page/';
            }
            if($this->current_page != 1) {
                if(($this->current_page - 1) == 1) {
                    $pagination .= '<a href="'.$current_url.'" id="'.($this->current_page - 1).'" class="page previous">&laquo; Previous</a>';
                } else {
                    $pagination .= '<a href="'.$pageURL.($this->current_page - 1).'" id="'.($this->current_page - 1).'" class="page previous">&laquo; Previous</a>';
                }
            }
            if($this->current_page >= ($range + 3)) {
                $pagination .= '<a href="'.$current_url.'" id="1" class="page">1</a>';
                $pagination .= '<span id="'.$this->total_pages.'" class="ellipse">...</span>';
            } elseif ($this->current_page == ($range + 2)) {
                $pagination .= '<a href="'.$current_url.'" id="1" class="page">1</a>';
            }
            for($i = ($this->current_page - $range);$i < (($this->current_page + $range) + 1); $i++) {
                if(($i > 0) && ($i <= $this->total_pages)){
                    if($i == $this->current_page){
                        $pagination .= '<span href="'.$pageURL.$i.'" id="'.$i.'" class="current">'.$i.'</span>';
                    } else {
                        if($i == 1) {
                            $pagination .= '<a href="'.$current_url.'" id="'.$i.'" class="page">'.$i.'</a>';
                        } else {
                            $pagination .= '<a href="'.$pageURL.$i.'" id="'.$i.'" class="page">'.$i.'</a>';
                        }

                    }
                }
            }
            if($this->current_page < ($this->total_pages - $range - 1)) {
                $pagination .= '<span id="'.$this->total_pages.'" class="ellipse">...</span>';
                $pagination .= '<a href="'.$pageURL.$this->total_pages.'" id="'.$this->total_pages.'" class="page">'.$this->total_pages.'</a>';
            } elseif ($this->current_page == ($this->total_pages - $range - 1)) {
                $pagination .= '<a href="'.$pageURL.$this->total_pages.'" id="'.$this->total_pages.'" class="page">'.$this->total_pages.'</a>';
            }
            if($this->current_page != $this->total_pages) {
                $pagination .= '<a href="'.$pageURL.($this->current_page + 1).'" id="'.($this->current_page + 1).'" class="page next" style="margin-right:0;">Next &raquo;</a>';
            }
            
        }
        
        
        //echo $pagination;
        return $pagination;        
    }
    /**
     * Creates the pagination
     * 
     * @access  public
     * @param   string|int $type The kind of pagination or the id of the post to pull the type of pagination
     * @param   int $range
     * @return  int The total number of pages
     */
    public function create_pagination( $type, $range ) {
        switch($type) {
            case 'static':
                return $this->create_pagination_static($range);
            break;
            case 'ajax':
                return $this->create_pagination_ajax($range);
            default:
                $id = (int)$type;
                $type = get_post_meta($id,'smw_ajax');
                $type = $type[0];
                if($type == 'ajax' || $type == 'static') {
                    return $this->create_pagination($type,$range);
                } else {
                    return $this->create_pagination('ajax',$range);
                }
            break;
        }
    }
}