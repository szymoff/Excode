<?php 
// Declare Shortcode that Output all Exhibitor View in Page View

        
function exhibitor_view_shortcode() {
    $pageURL = $_SERVER['REQUEST_URI'];
    if(strpos($pageURL, '/en') !== false)  { 
        $detailsText = "Details";
        $buttonText = "Go back to exhibitors catalog";
        $buttonAddress = "/en/exhibitors-catalog";
    }
    else if(strpos($pageURL, '/ru') !== false) {
        echo "rosyjski";
    } else {
        $detailsText = "Dane szczegółowe";
        $buttonText = "Wróć do katalogu wystawców";
        $buttonAddress = "/katalog-wystawcow";
    }

    ob_start(); ?>
    <div class="exhibitor-header">
        <p><?php echo get_the_content(); ?></p>
        <div class="ex-container">
			<div>        
                <img src="<?php echo get_the_post_thumbnail_url() ?>" />
            </div>
            <div>
                <h3><?php echo __($detailsText , 'ex-list'); ?></h3>
                <div class="details">
                    <ul>
                    <?php
                    $obj = new ExhibitorData;
                    $obj->print_output(); ?>
                    </ul>
                </div>
            </div>
        </div>
		<a href="<?php echo 'http://' . $_SERVER['SERVER_NAME'] . $buttonAddress; ?>" class="back-to-exhibitors"><?php echo $buttonText ?></a>
    </div>
<?php return ob_get_clean();
}

add_shortcode( 'exhibitor_view', 'exhibitor_view_shortcode' );

// FILTER CONTENT
function add_additional_js_content($content) {
    // Take Current content and convert it to shortcode above
	$content = do_shortcode('[exhibitor_view]');
    return $content;
	}
	
// FILTER CONTENT
add_filter( 'the_content', 'add_additional_js_content'); 

// Inlude current page.php view form theme directiory
include(get_template_directory() . '/page.php');
?>