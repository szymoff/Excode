<?php

/*

VC component

*/

add_action( 'vc_before_init', 'vc_exhibitors_list' );

function vc_exhibitors_list() {

  vc_map( array(

      "name" => __( "Exhibitors List", "ex-list" ),

      "base" => "exhibitors_list",

      "class" => "exhibitors",

	  "category" => __( "Content", "ex-list"),

	  "params" => array(

		array(

		  "type" => "checkbox",

		  "heading" => __( "Show Company Name", "ex-list" ),

		  "param_name" => "show_company",

		  "description" => __( "If You want to show Exhibitor's Name set 'Yes'", "ex-list" ),

		  "value" => Array(

			esc_html__("Yes, please", 'ex-list') => 'yes'

		  ) ,

		),

		array(

		  "type" => "checkbox",

		  "heading" => __( "Show Stand", "ex-list" ),

		  "param_name" => "show_stand",

		  "value" => Array(

			esc_html__("Yes, please", 'ex-list') => 'yes'

		  ) ,

		  "description" => __( "If You want to show Exhibitor's Stand set 'Yes'", "ex-list" )

		),

		array(

		  "type" => "checkbox",

		  "heading" => __( "Show Email", "ex-list" ),

		  "param_name" => "show_email",

		  "value" => Array(

			esc_html__("Yes, please", 'ex-list') => 'yes'

		  ) ,

		  "description" => __( "If You want to show Exhibitor's Email set 'Yes'", "ex-list" ),

		),

		array(

		  "type" => "checkbox",

		  "heading" => __( "Show Website", "ex-list" ),

		  "param_name" => "show_website",

		  "description" => __( "If You want to show Exhibitor's website set 'Yes'", "ex-list" )

		),

		array(

			"type" => "checkbox",

			"heading" => __( "Show Hall", "ex-list" ),

			"param_name" => "show_hall",

			"description" => __( "If You want to show Exhibitor's Hall set 'Yes'", "ex-list" )

		  ),

		array(

			"type" => "checkbox",

			"heading" => __( "Show Phone Number", "ex-list" ),

			"param_name" => "show_phone",

			"description" => __( "If You want to show Exhibitor's Phone Number set 'Yes'", "ex-list" )

		),

		array(

			"type" => "checkbox",

			"heading" => __( "Show Country", "ex-list" ),

			"param_name" => "show_country",

			"description" => __( "If You want to show Exhibitor's Country set 'Yes'", "ex-list" )

		),

		array(

			"type" => "checkbox",

			"heading" => __( "Show Excerpt", "ex-list" ),

			"param_name" => "show_excerpt",

			"description" => __( "If You want to show Exhibitor's Excerpt set 'Yes'", "ex-list" )

		),

	  )

		)); 

	}

	// Main Shortcode

	add_shortcode( 'exhibitors_list', 'exhibitors_list_shortcode' );

	function exhibitors_list_shortcode($atts) {

		extract( shortcode_atts( array(

			'show_company' => 'false',

			'show_stand' => 'false',

			'show_email' => 'false',

			'show_website' => 'false',

			'show_hall' => 'false',

			'show_phone' => 'false',

			'show_country' => 'false',

			'show_excerpt' => 'false',

		), $atts ) );

	ob_start();

	// The taxonomy maker

	function show_taxes($tax){

		return get_post_meta( get_the_ID(), $tax , true );

	}

	// Query Args

	$query_args = array(

		'post_type' => 'exhibitor',

		'posts_per_page' => -1

	);

	// The Query

	$the_query = new WP_Query( $query_args );

	// The Loop

	if ( $the_query->have_posts() ) {



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

			$sortText = "Sort by";

			$searchText = "Search by company name";

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

			$sortText = "Sortuj według";

			$searchText = "Szukaj według nazwy firmy";

		}



		// Table heders

		$show_me_company = ($show_company == 'false') ? "" : "<th>". __($companyText, 'ex-list') . "</th>";

		$show_me_email = ($show_email == 'false') ? "" : "<th>". __($emailText, 'ex-list') . "</th>";

		$show_me_website = ($show_website == 'false') ? "" : "<th>". __($websiteText, 'ex-list') . "</th>";

		$show_me_phone = ($show_phone == 'false') ? "" : "<th>". __($phoneText, 'ex-list') . "</th>";

		$show_me_country = ($show_country == 'false') ? "" : "<th>". __($countryText, 'ex-list') . "</th>";

		$show_me_excerpt = ($show_excerpt == 'false') ? "" : "<th>". __($descriptionText, 'ex-list') . "</th>";

		$show_me_hall = ($show_hall == 'false') ? "" : "<th>". __($hallText, 'ex-list') . "</th>";

		$show_me_stand = ($show_stand == 'false') ? "" : "<th>". __($standText, 'ex-list') . "</th>";



		$show_me_company2 = ($show_company == 'false') ? "" :  "<option class='sort-option'>". __($companyText, 'ex-list') . "</option>";

		$show_me_email2 = ($show_email == 'false') ? "" : "<option class='sort-option'>". __($emailText, 'ex-list') . "</option>";

		$show_me_website2 = ($show_website == 'false') ? "" : "<option class='sort-option'>". __($websiteText, 'ex-list') . "</option>";

		$show_me_country2 = ($show_country == 'false') ? "" : "<option class='sort-option'>". __($countryText, 'ex-list') . "</option>";

		$show_me_hall2 = ($show_hall == 'false') ? "" : "<option class='sort-option'>". __($hallText, 'ex-list') . "</option>";

		$show_me_stand2 = ($show_stand == 'false') ? "" : "<option class='sort-option'>". __($standText, 'ex-list') . "</option>";

		$select = "<div class='box'><div class='sorting-box'><select id='sorting'><option value='' selected disabled hidden>". __($sortText, 'ex-list') . "</option>" . $show_me_company2 . $show_me_email2 . $show_me_website2 .  $show_me_hall2 . $show_me_stand2. "</select></div><div class='searching-box'><input id='search-bar' type='text' placeholder='". __($searchText, 'ex-list') . "'></input></div></div>";

		// PRINT OUTPUT

		//$wiget_output = '<input type="text" id="myInput" placeholder="Szukaj wystawców.." title="Exhibitors Search">';

		$wiget_output = $select . '<table id="table-exhibitor" class="exhibitors-table">';

		$wiget_output .= '<thead><tr>';

		// Exhibitors Data List

		$wiget_output .=  $show_me_company . $show_me_email . $show_me_website . $show_me_phone . $show_me_country . $show_me_excerpt . $show_me_hall . $show_me_stand;

		$wiget_output .= '</tr></thead>';

		$wiget_output .= '<tbody id="table-content">';

		$companies = array();



		while ( $the_query->have_posts() ) {

			$the_query->the_post();

			// Table data columns

			$show_me_company_details = ($show_company == 'false') ? "" : "<td><a style='color: #777777; text-decoration: underline' href='".get_the_permalink($post->ID)."'>".get_the_title()."</a></td>";

			$show_me_stand_details = ($show_stand == 'false') ? "" : '<td>'.show_taxes('stand').'</td>';

			$show_me_email_details = ($show_email == 'false') ? "" : '<td>'.show_taxes('email').'</td>';

			$show_me_website_details = ($show_website == 'false') ? "" : '<td>'.show_taxes('website').'</td>';

			$show_me_hall_details = ($show_hall == 'false') ? "" : '<td>'.show_taxes('hall').'</td>';

			$show_me_phone_details = ($show_phone == 'false') ? "" : '<td>'.show_taxes('phone').'</td>';

			$show_me_country_details = ($show_country == 'false') ? "" : '<td>'.show_taxes('country').'</td>';

			$show_me_excerpt_details = ($show_excerpt == 'false') ? "" : '<td>'.get_the_excerpt().'</td>';

			// Current Exhibitors Output

			$wiget_output .= '<tr>';

			$wiget_output .=  $show_me_company_details . $show_me_email_details . $show_me_website_details . $show_me_phone_details . $show_me_country_details . $show_me_excerpt_details . $show_me_hall_details . $show_me_stand_details;

			$wiget_output .= '</tr>';

			$counter++;	

		}

		$wiget_output .= '</tbody></table>';

		$wiget_output .= '<br>';

		/* Restore original Post Data */

		wp_reset_postdata();

	} 

	// END of OUTPUT

	ob_get_clean();

	return $wiget_output;

}

?>

