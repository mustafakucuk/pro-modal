<?php
/*
Plugin Name: Pro Modal
Description: Create your pro modals, edit and publish! It's that easy! Easy, fast, modern, no ad!
Plugin URI: https://wpajans.net/
Author: WPAjans - Mustafa KÜÇÜK
Author URI: https://wpajans.net
Version: 1.0
License: GNU
*/

class Pro_Modal {
  
  public function __construct()
  {
    add_action( 'wp_enqueue_scripts', array( $this, 'Pro_Modal_Enqueue' ) );
    add_action( 'init', array( $this, 'Pro_Modal_CPT' ) );
    add_action( 'add_meta_boxes_pro-modal', array( $this, 'Pro_Modal_Meta_Box' ) );
    add_action( 'save_post_pro-modal', array( $this, 'Pro_Modal_Meta_Box_Save' ) );
    add_action( 'wp_footer', array( $this, 'Pro_Modal_View' ) );
  }
  
  public function Pro_Modal_Enqueue()
  {
    wp_enqueue_style( 'pro-modal', plugins_url( '/assets/css/pro-modal.css', __FILE__ ) );
    wp_enqueue_script( 'pro-modal', plugins_url( '/assets/js/pro-modal.js', __FILE__ ), array(), '1.0.0', true );
  }
  
  public function Pro_Modal_Meta_Box()
  {
    add_meta_box( 'Pro_Modal_Meta_Box', 'Modal Settings' , array( $this, 'Pro_Modal_Meta_Box_Content' ), 'pro-modal', 'advanced', 'high' );
  }
  
  public function Pro_Modal_Meta_Box_Content( $post )
  {
    wp_nonce_field( 'Pro_Modal', 'Pro_Modal' );
    ?>
    <div class="Pro_Modal_Meta_Box_Title" style="border-bottom: 1px solid #eee;margin-bottom:10px"><h1 style="font-size:14px; font-weight:bold;">Style Settings</h1></div>
    <input type="checkbox" name="Pro_Modal_Meta_Box_Option_Title" <?php echo ( get_post_meta( $post->ID, 'Pro_Modal_Meta_Box_Option_Title', true ) == 'on' ? 'checked' : '' ); ?>> Don't show modal title <br><br>
    <select name="Pro_Modal_Meta_Box_Option_Style">
      <option value="1" <?php echo ( get_post_meta( $post->ID, 'Pro_Modal_Meta_Box_Option_Style', true ) == 1 ? 'selected' : '' )?>>Style 1</option>
      <option value="2" <?php echo ( get_post_meta( $post->ID, 'Pro_Modal_Meta_Box_Option_Style', true ) == 2 ? 'selected' : '' )?>>Style 2</option>
    </select>
    <div class="Pro_Modal_Meta_Box_Title" style="border-bottom: 1px solid #eee;margin-bottom:10px"><h1 style="font-size:14px; font-weight:bold;">Trigger Settings</h1></div>    
    <select name="Pro_Modal_Meta_Box_Option_Trigger">
      <option value="1" <?php echo ( get_post_meta( $post->ID, 'Pro_Modal_Meta_Box_Option_Trigger', true ) == 1 ? 'selected' : '' )?>>When site load</option>
      <option value="2" <?php echo ( get_post_meta( $post->ID, 'Pro_Modal_Meta_Box_Option_Trigger', true ) == 2 ? 'selected' : '' )?>>When click a element</option>
    </select>
    <br>
    <input style="width:100%; padding:10px;margin-top:10px" type="text" name="Pro_Modal_Meta_Box_Option_Trigger_Element" placeholder="if you choose 'When click a element', press a element. #idName or .className" value="<?php echo get_post_meta( $post->ID, 'Pro_Modal_Meta_Box_Option_Trigger_Element', true ); ?>">
    <div class="Pro_Modal_Meta_Box_Title" style="border-bottom: 1px solid #eee;margin-bottom:10px"><h1 style="font-size:14px; font-weight:bold;">Display Settings</h1></div>    
    <?php $Pro_Modal_Meta_Box_Option_Display = get_post_meta( $post->ID, 'Pro_Modal_Meta_Box_Option_Display', true); ?>
    <select name="Pro_Modal_Meta_Box_Option_Display[]" multiple>
      <option value="1" <?php echo ( in_array( 1, $Pro_Modal_Meta_Box_Option_Display ) ? 'selected' : '' ); ?>>show on homepage</option>
      <option value="2" <?php echo ( in_array( 2, $Pro_Modal_Meta_Box_Option_Display ) ? 'selected' : '' ); ?>>show on category</option>
      <option value="3" <?php echo ( in_array( 3, $Pro_Modal_Meta_Box_Option_Display ) ? 'selected' : '' ); ?>>show on posts</option>
        <optgroup label="pages">
        <?php foreach( get_pages() as $page )
        {
          echo'<option value="'.$page->ID.'" '.( in_array( $page->ID, $Pro_Modal_Meta_Box_Option_Display ) ? 'selected' : '' ).' >'.get_the_title($page->ID).'</option>';
        }
        ?>
      </optgroup>
    </select>
    <div class="Pro_Modal_Meta_Box_Title" style="border-bottom: 1px solid #eee;margin-bottom:10px"><h1 style="font-size:14px; font-weight:bold;">Cookie Settings</h1></div>
    <select name="Pro_Modal_Meta_Box_Option_Cookie">
      <option value="1" <?php echo ( get_post_meta( $post->ID, 'Pro_Modal_Meta_Box_Option_Cookie', true ) == 1 ? 'selected' : '' ); ?>>Show always</option>
      <option value="2" <?php echo ( get_post_meta( $post->ID, 'Pro_Modal_Meta_Box_Option_Cookie', true ) == 2 ? 'selected' : '' ); ?>>Show only 1 time every user</option>
    </select>
  <?php
  }
  
  public function Pro_Modal_Meta_Box_Save( $post_id )
  {
    if ( !isset( $_POST["Pro_Modal"] ) || !wp_verify_nonce( $_POST["Pro_Modal"], 'Pro_Modal' ) ){
    	return;
    }
    
    if( $_POST["Pro_Modal_Meta_Box_Option_Title"] ){
      $Pro_Modal_Meta_Box_Option_Title = $_POST["Pro_Modal_Meta_Box_Option_Title"];
      update_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Title', $Pro_Modal_Meta_Box_Option_Title );
    }else{
      delete_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Title' );
    }
    
    if( $_POST["Pro_Modal_Meta_Box_Option_Style"] ){
      $Pro_Modal_Meta_Box_Option_Style = intval( $_POST["Pro_Modal_Meta_Box_Option_Style"] ); 
      update_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Style', $Pro_Modal_Meta_Box_Option_Style );
    }
    
    if( $_POST["Pro_Modal_Meta_Box_Option_Trigger"] ){
      $Pro_Modal_Meta_Box_Option_Trigger = intval( $_POST["Pro_Modal_Meta_Box_Option_Trigger"] ); 
      update_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Trigger', $Pro_Modal_Meta_Box_Option_Trigger );
    }
    
    if( $_POST["Pro_Modal_Meta_Box_Option_Trigger_Element"] ){
      $Pro_Modal_Meta_Box_Option_Trigger_Element = sanitize_text_field( $_POST["Pro_Modal_Meta_Box_Option_Trigger_Element"] ); 
      update_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Trigger_Element', $Pro_Modal_Meta_Box_Option_Trigger_Element );
    }else{
      delete_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Trigger_Element' );
    }
    
    if( $_POST["Pro_Modal_Meta_Box_Option_Display"] ){
      $Pro_Modal_Meta_Box_Option_Display = $_POST["Pro_Modal_Meta_Box_Option_Display"];
      update_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Display', $Pro_Modal_Meta_Box_Option_Display );
    }else{
      delete_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Display' );
    }
    
    if( $_POST["Pro_Modal_Meta_Box_Option_Cookie"] ){
      $Pro_Modal_Meta_Box_Option_Cookie = $_POST["Pro_Modal_Meta_Box_Option_Cookie"];
      update_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Cookie', $Pro_Modal_Meta_Box_Option_Cookie );
    }
  }
  
  public function Pro_Modal_CPT()
  {
    $labels = array(
      'name'               => 'Modals',
      'singular_name'      => 'Modal',
      'menu_name'          => 'Pro Modal',
      'name_admin_bar'     => 'Modal',
      'add_new'            => 'Add New Modal',
      'add_new_item'       => 'Add New Modal',
      'new_item'           => 'New Modal',
      'edit_item'          => 'Edit Modal',
      'all_items'          => 'All Modals',
      'not_found'          => 'No modals found.',
  );
  
  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'menu_icon'          => plugins_url( '/assets/img/icon.png', __FILE__ ),
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'pro-modal' ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor' )
  );
  
  register_post_type( 'pro-modal', $args );
  }
      
  public function Pro_Modal_View()
  {
    $getID = get_the_ID();
    $post_ids = [];
    query_posts( array( 'post_type' => 'pro-modal' ) );
    if( have_posts() ) : while( have_posts() ) : the_post();
      $post_ids[] = get_the_ID();
    endwhile; endif; wp_reset_query();
    foreach( $post_ids as $post_id ){
      $Pro_Modal_Meta_Box_Option_Style = get_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Style', true );
      $Pro_Modal_Meta_Box_Option_Trigger = get_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Trigger', true );
      $Pro_Modal_Meta_Box_Option_Trigger_Element = get_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Trigger_Element', true );
      $Pro_Modal_Meta_Box_Option_Display = get_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Display', true );
      $Pro_Modal_Meta_Box_Option_Cookie = get_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Cookie', true );
      $Pro_Modal_Meta_Box_Option_Title = get_post_meta( $post_id, 'Pro_Modal_Meta_Box_Option_Title', true );
      if( $Pro_Modal_Meta_Box_Option_Cookie == 2 && !isset($_COOKIE["pro_modal-$post_id"]) || $Pro_Modal_Meta_Box_Option_Cookie == 1 ){
        if( is_home() && in_array( 1, $Pro_Modal_Meta_Box_Option_Display ) or is_category() && in_array( 2, $Pro_Modal_Meta_Box_Option_Display ) or is_single() && in_array( 3, $Pro_Modal_Meta_Box_Option_Display ) or is_page() && in_array( $getID, $Pro_Modal_Meta_Box_Option_Display ) ){
          if( $Pro_Modal_Meta_Box_Option_Style == 1 ){
            $view = '<div class="pro-modal" id="pro-modal-1" data-modalID="'.$post_id.'" data-modalTrigger="'.$Pro_Modal_Meta_Box_Option_Trigger.'" data-modalTriggerElement="'.$Pro_Modal_Meta_Box_Option_Trigger_Element.'" data-modalCookie="'.$Pro_Modal_Meta_Box_Option_Cookie.'">';
            $view .= '<div class="modal-wrapper '.( $Pro_Modal_Meta_Box_Option_Trigger == 1 ? 'open' : '' ).'"><div class="modal">';
            $view .= '<div class="head">'.( $Pro_Modal_Meta_Box_Option_Title == 'on' ? '' : '<span>'.get_the_title( $post_id ).'</span>' ).'<a class="btn-close trigger" href="javascript:;"></a></div>';
            $view .= '<div class="content">'.get_post_field('post_content', $post_id).'</div>';
            $view .= '</div></div></div>';
          }elseif( $Pro_Modal_Meta_Box_Option_Style == 2 ){
            $view = '<div class="pro-modal" id="pro-modal-2" data-modalID="'.$post_id.'" data-modalTrigger="'.$Pro_Modal_Meta_Box_Option_Trigger.'" data-modalTriggerElement="'.$Pro_Modal_Meta_Box_Option_Trigger_Element.'" data-modalCookie="'.$Pro_Modal_Meta_Box_Option_Cookie.'">';
            $view .= '<div class="modal-wrapper '.( $Pro_Modal_Meta_Box_Option_Trigger == 1 ? 'open' : '' ).'"><div class="modal"><div class="modal-inset"><div class="btn-close"></div>';
            $view .= '<div class="modal-body">'.( $Pro_Modal_Meta_Box_Option_Title == 'on' ? '' : '<h3>'.get_the_title( $post_id ).'</h3>' ).get_post_field('post_content', $post_id).'</div>';
            $view .= '</div></div></div></div>';  
          }
          echo $view;
        }
      }
    }
  }
}

new Pro_Modal;