<?php

/**
 * Plugin Name: DigitalRep Tag Squares
 * Plugin URI: http://www.digitalrep.info
 * Description: Displays tags in colourful squares
 * Version: 1.0
 * Author: DigitalRep
 * Author URI: http://www.digitalrep.info
 */

class DigitalRep_Tag_Squares extends WP_Widget 
{
  /* Construct our widget. The option 'description' is used in the admin panel where the widget is listed */
  function __construct()
  {
    //string $id_base (slug), string $name (for configuration page, text domain for translation), array $widget_options(description)
    parent::__construct('digitalrep_tag_squares', __('DigitalRep Tag Squares', 'digitalrep-tag-squares'), array( 'description' => __( "Your most used tags displayed in colourful squares!", "digitalrep-tag-squares")));
  }
  
  /* Plugin Logic */
  function widget($args, $instance) 
  {
    extract($args);
   
    $title = $instance['title'];
    $taxonomy = $instance['taxonomy'];
    $cscheme = $instance['color-scheme'];
    $custom = $instance['custom'];
    if(!empty($instance['txtcol']))
    {
      $txtcol = $instance['txtcol'];
    }
    else
    {
      $txtcol = "#000000";
    }
      
    if(empty($title))
    {
      $title = 'Tag Wall';
    }

    echo $before_widget;
   
    echo $before_title . $title . $after_title;
    echo '<div id="tagcloud">';

    $tags = get_terms($taxonomy, array('orderby' => 'id', 'order' => 'DESC', 'hide_empty' => 0, 'number' => 12));
    
    if(empty($tags) || is_wp_error($tags))
    {
      return;
    }
    else
    {
      $counter = 0;
      if($custom != 1)
      {
        if($cscheme == "pastelsgb")
        {
          $colarray = array(
           0 => "#999967", 
           1 => "#666666",
           2 => "#CCCCCC",
           3 => "#CCCC9A");
        }
        elseif($cscheme == "candyeclair")
        {
          $colarray = array(
           0 => "#E75266", 
           1 => "#F29509",
           2 => "#513822",
           3 => "#77C3B7",
           4 => "#DDD034");
        }
        elseif($cscheme == "autumncreme")
        {
          $colarray = array(
           0 => "#EFE29D", 
           1 => "#A1B55D",
           2 => "#E5A33D",
           3 => "#3A3626");
        }
        elseif($cscheme == "migraine")
        {
          $colarray = array(
           0 => "#999999", 
           1 => "#990033",
           2 => "#ffff00",
           3 => "#330033");
        }
      }
      else
      {
       $colarray = array(
        0 => $instance['custom1'],
        1 => $instance['custom2'],
        2 => $instance['custom3'],
        3 => $instance['custom4'],
        4 => $instance['custom5']);
      }    
      
      foreach($tags as $key => $tag) 
      {
        echo "<div style='background: " . $colarray[$counter] . ";' class='tagy'><a href='";
        echo get_term_link(intval($tag->term_id), $tag->taxonomy) . "'>";
        echo "<span style='color:" . $txtcol . ";'>" . $tag->name . "</span></a></div>";
        if($counter == count($colarray)-1)
        {
          $counter = 0;
        }
        else
        {
          $counter++;
        }
      }
    }

    echo "</div>\n";
    echo $after_widget;
  }
  
  /* Saves your widget's options from the admin panel as the user updates them */
  function update( $new_instance, $old_instance ) 
  {
    $instance['title'] = strip_tags(stripslashes($new_instance['title']));
    $instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
    $instance['color-scheme'] = stripslashes($new_instance['color-scheme']);
    $instance['custom'] = $new_instance['custom'];
    $instance['custom1'] = strip_tags(stripslashes($new_instance['custom1']));
    $instance['custom2'] = strip_tags(stripslashes($new_instance['custom2']));
    $instance['custom3'] = strip_tags(stripslashes($new_instance['custom3']));
    $instance['custom4'] = strip_tags(stripslashes($new_instance['custom4']));
    $instance['custom5'] = strip_tags(stripslashes($new_instance['custom5']));
    $instance['txtcol'] = strip_tags(stripslashes($new_instance['txtcol']));
    return $instance;
  }
  
  /* The form that shows up in the admin panel when the user enters options for the widget.
     $instance['form_attribute_name'] contains the options the user entered into the form  */
  function form($instance) 
  {
   if(!empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']))
   {
     $current_taxonomy = $instance['taxonomy'];
   }
   else
   {
     $current_taxonomy = 'post_tags';
   }
   if(!empty($instance['custom']))
   {
     $checked = $instance['custom'];
   }
   else
   {
     $checked = 0;
   }
   if(!empty($instance['color-scheme']))
   {
     $current_scheme = $instance['color-scheme'];
   }
   else
   {
     $current_scheme = "Candy Eclair";
   }

   $schemes = array("migraine" => "Migraine", "candyeclair" => "Candy Eclair", "pastelsgb" => "Pastels (Green, Blue)", "autumncreme" => "Autumn Creme");
    ?>
     <!-- Title -->
     <p>
       <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'digitalrep-tag-squares') ?></label>
       <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
            name="<?php echo $this->get_field_name('title'); ?>" 
            value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" />
     </p>  
     <!-- Use custom colours? -->
     <p>
      <input id="<?php echo $this->get_field_id('custom'); ?>" name="<?php echo $this->get_field_name('custom'); ?>"
      type="checkbox" value="1" <?php if($checked == "1") { echo "checked"; } ?> />
       <label for="<?php echo $this->get_field_id('custom'); ?>"><?php _e('Use Custom Colours?', 'digitalrep-tag-squares') ?></label>
     </p>
     
     <!-- Custom Field 1 -->
     <p>
       <label for="<?php echo $this->get_field_id('custom1'); ?>"><?php _e('Enter Colours: (i.e. blue or #003366)', 'digitalrep-tag-squares') ?></label>
       <input type="text" class="widefat" id="<?php echo $this->get_field_id('custom1'); ?>" 
            name="<?php echo $this->get_field_name('custom1'); ?>" <?php if($checked == "0") { echo "disabled"; } ?>
            value="<?php if (isset($instance['custom1'])) { echo esc_attr( $instance['custom1'] ); } ?>" />
     <!-- Custom Field 2 -->
       <input type="text" class="widefat" id="<?php echo $this->get_field_id('custom2'); ?>" 
            name="<?php echo $this->get_field_name('custom2'); ?>" <?php if($checked == "0") { echo "disabled"; } ?>
            value="<?php if (isset($instance['custom2'])) { echo esc_attr( $instance['custom2'] ); } ?>" />
     <!-- Custom Field 3 -->
       <input type="text" class="widefat" id="<?php echo $this->get_field_id('custom3'); ?>" 
            name="<?php echo $this->get_field_name('custom3'); ?>" <?php if($checked == "0") { echo "disabled"; } ?>
            value="<?php if (isset($instance['custom3'])) { echo esc_attr( $instance['custom3'] ); } ?>" />
     <!-- Custom Field 4 -->
       <input type="text" class="widefat" id="<?php echo $this->get_field_id('custom4'); ?>" 
            name="<?php echo $this->get_field_name('custom4'); ?>" <?php if($checked == "0") { echo "disabled"; } ?>
            value="<?php if (isset($instance['custom4'])) { echo esc_attr( $instance['custom4'] ); } ?>" />
     <!-- Custom Field 5 -->
       <input type="text" class="widefat" id="<?php echo $this->get_field_id('custom5'); ?>" 
            name="<?php echo $this->get_field_name('custom5'); ?>" <?php if($checked == "0") { echo "disabled"; } ?>
            value="<?php if (isset($instance['custom5'])) { echo esc_attr( $instance['custom5'] ); } ?>" />
     </p>
     
     <!-- Colour Scheme -->
     <p>
       <label for="<?php echo $this->get_field_id('color-scheme'); ?>"><?php _e('Colour Scheme:', 'digitalrep-tag-squares') ?></label>
      <select <?php if($checked == "1") { echo "disabled"; } ?> class="widefat" id="<?php echo $this->get_field_id('color-scheme'); ?>"
            name="<?php echo $this->get_field_name('color-scheme'); ?>">
        <?php 
          foreach($schemes as $item => $name) : ?>
            <option value="<?php echo $item ?>"
              <?php 
            if($current_scheme == $item)
            {
              echo 'selected="selected"';
            }
            ?>>
             <?php echo $name; ?>
            </option>
        <?php endforeach; ?>
      </select>
     </p>
     
     <!-- Text Colour -->
     <p>
       <label for="<?php echo $this->get_field_id('txtcol'); ?>"><?php _e('Text Colour: (i.e. blue or #003366)', 'digitalrep-tag-squares') ?></label>
       <input type="text" class="widefat" id="<?php echo $this->get_field_id('txtcol'); ?>" 
            name="<?php echo $this->get_field_name('txtcol'); ?>" 
            value="<?php if (isset($instance['txtcol'])) { echo esc_attr( $instance['txtcol'] ); } ?>" />
     </p>
     
     <!-- Taxonomy -->
     <p>
       <label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:', 'digitalrep-tag-squares') ?></label>
       <select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>"
             name="<?php echo $this->get_field_name('taxonomy'); ?>">
   <?php 
         foreach(get_taxonomies() as $taxonomy) :
          $tax = get_taxonomy($taxonomy);
          if(!$tax->show_tagcloud || empty($tax->labels->name))
            continue;
   ?>
           <option value="<?php echo esc_attr($taxonomy) ?>"
           <?php selected($taxonomy, $current_taxonomy) ?>>
             <?php echo $tax->labels->name; ?>
           </option>
   <?php 
         endforeach; 
   ?>
       </select>
      </p>
   <?php
  }
}

function drep_include_js()
{
  wp_enqueue_style('style-name', '/wp-content/plugins/digitalrep_tag_squares/digitalrep_tag_squares.css');
  wp_enqueue_script('boo', 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
  wp_enqueue_script('arbitrary string', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js');
  wp_enqueue_script('purple monkey dishwasher', '/wp-content/plugins/digitalrep_tag_squares/js/bounce.js');
}

function drep_widgets_init()
{
  register_widget('DigitalRep_Tag_Squares');
}

add_action('widgets_init', 'drep_widgets_init');
add_action('wp_enqueue_scripts', 'drep_include_js');


?>
