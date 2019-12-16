<?php
if ( !class_exists('ExhibitorsCustomFields') ) {
	class ExhibitorsCustomFields {
        /**
        * @var  array  $postTypes to append Plugin Custom Fields
        */
        var $postTypes = array( "exhibitor");
        /**
        * @var  array  $customFields  Defines the custom fields available
		*/

		var $customFields = array(

			array(
                "name"          => "email",
                "title"         => "Email:",
                "callback"   	=> "ecf_display",
                "type"          => "text",
			),
			array(
                "name"          => "phone",
                "title"         => "Telefon:",
                "callback"   	=> "ecf_display",
                "type"          => "text",
			),
			array(
                "name"          => "website",
                "title"         => "Strona Internetowa:",
                "callback"   	=> "ecf_display",
                "type"          => "text",
			),
			array(
                "name"          => "country",
                "title"         => "Kraj:",
                "callback"   	=> "ecf_display",
                "type"          => "text",
			),
			array(
                "name"          => "hall",
                "title"         => "Hala:",
                "callback"   	=> "ecf_display",
                "type"          => "text",
			),
			array(
                "name"          => "stand",
                "title"         => "Stoisko:",
                "callback"   	=> "ecf_display",
                "type"          => "text",
			)
        );
        
		// Construct new Exhibitor
		public function __construct() {
			$pageURL = $_SERVER['REQUEST_URI'];
			if(strpos($pageURL, '/en') !== false)  { 
				$companyText = "Company";
				$emailText = "Email";
				$websiteText = "Website";
				$phoneText = "Phone";
				$countryText = "Country";
				$descriptionText = "Description";
				$hallText = "Hall";
				$standText = "Stand";
			}
			else if(strpos($pageURL, '/ru') !== false) {
				echo "rosyjski";
			} else {
				$companyText = "Firma";
				$emailText = "Email";
				$websiteText = "Strona internetowa";
				$phoneText = "Telefon";
				$countryText = "Kraj";
				$descriptionText = "Opis";
				$hallText = "Hala";
				$standText = "Stoisko";
			}
			// Add actions to each element in $customFields array
			add_action( 'add_meta_boxes', array( $this, 'register_meta' ) );
			add_action( 'save_post', array( $this, 'ecf_save_meta_box' ) );
			/**
			 * Meta box display callback.
			 *
			 * @param WP_Post $post Current post object.
			 */
			// Function that determinate how each custom field is displaing
			function ecf_display( $post, $type){ 
				?>
				<p class="meta-options ecf_field">
					<?php 
					// Swich -> Case function to future upgrade
					switch ($type['args'][2]) {
						case "text": ?>
							<label for="<?= $type['args'][0]; ?>"><?= $type['args'][1]; ?></label>
							<input id="<?= $type['args'][0]; ?>" type="text" name="<?= $type['args'][0]; ?>" value="<?php echo esc_attr( get_post_meta( get_the_ID(), $type['args'][0] , true ) ); ?>">
					<?php		
						// Brakre this function at the end
						break;
					}
					?>
				</p>
			<?php
			}	
		}
		// Function that loop through each custom field and register it
		public function register_meta() {
			foreach($this->customFields as $customField){
				add_meta_box( 'ecf-'.$customField['name'], __( $customField['title'], 'exlist' ), $customField['callback'], $postTypes , 'advanced', 'high', $type = array($customField['name'], $customField['title'], $customField['type']) );
			}
        }
		   
		/**
		 * Save meta box content.
		 *
		 * @param int $post_id Post ID
		 */
		// Function that loop through each custom field and make save mode
		public function ecf_save_meta_box( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
			if ( $parent_id = wp_is_post_revision( $post_id ) ) {
				$post_id = $parent_id;
			}
			
			$fields = array();

			foreach($this->customFields as $customField){
				array_push( $fields, $customField['name'] );
			}
			
			foreach ( $fields as $field ) {
				if ( array_key_exists( $field, $_POST ) ) {
					update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
				}
			}
		}
    }
    
class ExhibitorData extends ExhibitorsCustomFields{

    public function __construct() {
        return $this->customFields;
    }
    // Function that loop through each custom field and register it
    public function print_output() {
        foreach($this->customFields as $customField){
            if(get_post_meta( get_the_ID(), $customField['name'] , true )): ?>
                <li class="details-item">
                    <strong><?php echo __($customField['title'] , 'ex-list'); ?></strong>  <?php echo get_post_meta( get_the_ID(), $customField['name'] , true ); ?>
				</li>
                <?php
            endif;
        }
    }
}
}

?>