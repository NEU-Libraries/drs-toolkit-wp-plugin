<?php
add_action('add_meta_boxes_drstk_item_extension', 'adding_item_extension_meta_box');
function adding_item_extension_meta_box($post){
  add_meta_box(
    'drstk_meta_box',
    __('Item ID'),
    'render_item_extension_meta_box',
    'drstk_item_extension',
    'side',
    'high'
  );
}

function render_item_extension_meta_box(){
  global $post;
  wp_nonce_field(basename(__FILE__), "meta-box-nonce");
  ?>
  <div>
    <label for="item-id">Item Identifier</label><br/>
    <small>ie. neu:123 or dpla:890342</small><br/>
    <input name="item-id" type="text" value="<?php echo get_post_meta($post->ID, "item-id", true); ?>">
  </div>
  <?php
}

function save_custom_meta_box($post_id, $post, $update){
  if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
    return $post_id;

  if(!current_user_can("edit_post", $post_id))
      return $post_id;

  if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
    return $post_id;

  $slug = "drstk_item_extension";
  if($slug != $post->post_type)
    return $post_id;

  $item_id = "";

  if(isset($_POST["item-id"])){
    $item_id = $_POST["item-id"];
  }
  update_post_meta($post_id, "item-id", $item_id);
}

add_action("save_post", "save_custom_meta_box", 10, 3);
