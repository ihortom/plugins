<?php
/*
Plugin Name: Single-Choice Post Taxonomy
Version: 1.0
Description: Add the single-choice custom post taxonomy in addition/replacement to standard multi-choice Category
Author: ProperWeb
Author URI: https://www.properweb.ca

Usage Info: 
1. To remove WP default taxonomy Category, uncomment the corresponding line in 'pweb_remove_default_single_choice_metabox' function.
2. Default slug for this taxonomy is 'company'; thus, the URL to posts bound by the term 'News' would be http://domain.com/company/news/. You can change it to your liking though. Once done, resubmit permalinks to apply the changes.
3. Do not remove the 'default' term called 'Standard'. If no selection made, the post will be saved with this term.
4. If the term's name amended or a new introduced, you might wish to add/amend the relevant taxonomy template
*/

define('PWEB_TAX','pweb_post_type');
define('PWEB_TAX_NAME','Types');

// Add new taxonomy
function pweb_add_single_choice_taxonomy() {
	// create a new taxonomy
	register_taxonomy(
            PWEB_TAX,
            'post',
            array(
                'label' => __( PWEB_TAX_NAME ),
                'rewrite' => array( 'slug' => 'company' ),	//RESUBMIT PERMALINKS TO APPLY (amend 'slug' as required)
                'show_in_nav_menus' => true
            )
	);
	//Populate taxonomy with terms (any but "Standard" could be edited/removed as required)
	wp_insert_term(
            'Standard', // the term - DO NOT REMOVE 
            PWEB_TAX, // the taxonomy
            array(
                'description'=> 'Standard post'
            )
	);
	wp_insert_term(
            'News', // the term 
            PWEB_TAX, // the taxonomy
            array(
                'description'=> 'Latest news',
                'slug' => 'news'
            )
	);
	wp_insert_term(
            'Promotions', // the term 
            PWEB_TAX, // the taxonomy
            array(
                'description'=> 'Current and upcoming promotions',
                'slug' => 'promo'
            )
	);
}
add_action( 'init', 'pweb_add_single_choice_taxonomy' );

//remove default metaboxes
function pweb_remove_default_single_choice_metabox(){
   //remove_meta_box('categorydiv', 'post', 'normal');	//stadndard Categories meta box; replacing with our own single choice meta box
	 remove_meta_box('tagsdiv-'.PWEB_TAX, 'post', 'normal');	//this box is auto-created when adding own metabox; removing to rebuild our way
}
add_action( 'admin_menu', 'pweb_remove_default_single_choice_metabox');

// -->Add new taxonomy meta box on the Post edit screen
function pweb_add_single_choice_metabox() {	 
	$screens = array( 'post');		//only post for now	 
	foreach ( $screens as $screen ) {
            add_meta_box(
                PWEB_TAX.'_div',									//(required) HTML 'id' attribute of the edit screen section
                __( PWEB_TAX_NAME, 'properweb' ),	//(required) Title of the edit screen section, visible to user
                'pweb_print_single_choice_metabox',				//(required) Callback function that prints out the HTML for the edit screen section
                $screen,													//(optional) The type of writing screen on which to show the edit screen section
                'side'	//(optional) The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side')
            );
	}	 
	//add_meta_box( 'post-type_div', 'Post Types','pweb_print_single_choice_metabox','post','side','default');
}
add_action( 'add_meta_boxes', 'pweb_add_single_choice_metabox');

// -->Callback to set up (print) the metabox in Edit screen
function pweb_print_single_choice_metabox( $post ) {
	//Get taxonomy and terms
	$taxonomy = PWEB_TAX;

	//Set up the taxonomy object and get terms
	$tax = get_taxonomy($taxonomy);
	$terms = get_terms($taxonomy,array('hide_empty' => 0));

	//Name of the radio-input
	$name = 'tax_input[' . $taxonomy . ']';

	//Get current and popular terms
	$popular = get_terms( $taxonomy, array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 5, 'hierarchical' => false ) );
	$postterms = get_the_terms( $post->ID,$taxonomy );
	$current = ($postterms ? array_pop($postterms) : false);
	$current = ($current ? $current->term_id : 0);	//id=0 if no term of PWEB_TAX assigned; used to mark default
	//echo "THIS TAX VALUE: ".$current; //for debug
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'pweb_metabox_pweb_post_type', 'pweb_metabox_pweb_post_type_nonce' );
	?>
	<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">

            <!-- Display tabs-->
<!--
            <ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
                <li class="tabs"><a href="#<?php echo $taxonomy; ?>-all" tabindex="3"><?php _e( 'All Types' ); ?></a></li>
                <li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop" tabindex="3"><?php _e( 'Most Used' ); ?></a></li>
            </ul>
-->
            <!-- Display taxonomy terms 
            <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
            -->
                <ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy?> categorychecklist form-no-clear">
                    <?php   
                        foreach($terms as $term){
                            $id = $taxonomy.'-'.$term->term_id;
                            echo "<li id='$id'><label class='selectit'>";
                            echo "<input type='radio' id='in-$id' name='{$name}'";
                            if (!$current && $term->name == 'Standard') echo ' checked="checked"';
                            else checked($current,$term->term_id,true);
                            echo " value='{$term->name}' />{$term->name}";
                            echo "</label></li>";
                        }
                    ?>
                 </ul>
            <!--</div>-->

            <!-- Display popular taxonomy terms -->
<!--
            <div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
                <ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >
                    <?php   
                        foreach($popular as $term){
                            $id = 'popular-'.$taxonomy.'-'.$term->term_id;
                            echo "<li id='$id'><label class='selectit'>";
                            echo "<input type='radio' id='in-$id'";
                            if (!$current && $term->name == 'Standard') echo ' checked="checked" ';
                            else checked($current,$term->term_id,true);
                            echo " value='$term->name' />$term->name";
                            echo "</label></li>";
                        }
                    ?>
                </ul>
            </div>
-->
	</div>
	<?php
}
/**
 * When the post is saved, saves our custom data.
 * @param int $post_id The ID of the post being saved.
 */
function pweb_save_single_choice_metabox_data( $post_id ) {
	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */
	// Check if our nonce is set.
	if ( ! isset( $_POST['pweb_metabox_pweb_post_type_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['pweb_metabox_pweb_post_type_nonce'], 'pweb_metabox_pweb_post_type' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	}
	else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	//same as in pwrf_mytaxonomy_metabox
	$taxonomy = PWEB_TAX;
	$name = 'tax_input[' . $taxonomy . ']';
	
	// Make sure that it is set.
	if ( ! isset( $_POST[$name] ) ) {
		return;
	}
	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST[$name] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_pweb_post_type_key', $my_data );
}
add_action( 'save_post', 'pweb_save_single_choice_metabox_data' );
?>