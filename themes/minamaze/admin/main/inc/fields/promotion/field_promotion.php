<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Field_Promotion
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_promotion' ) ) {

    /**
     * Main ReduxFramework_promotion class
     *
     * @since       1.0.0
     */
    class ReduxFramework_promotion extends ReduxFramework {
    
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function __construct( $field = array(), $value ='', $parent ) {
        
            parent::__construct( $parent->sections, $parent->args, $parent->extra_tabs );

            $this->field = $field;
            $this->value = $value;
        
        }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {

        	if ( !isset( $this->field['style'] ) ) {
        		$this->field['style'] = "";
        	}

            if ( empty( $this->field['section'] ) ) {
            	$this->field['section'] = "main";
            }
            if ( empty( $this->field['image'] ) ) {
            	$this->field['image'] = "";
            }
            if ( empty( $this->field['name'] ) ) {
            	$this->field['name'] = "";
            }
            if ( empty( $this->field['desc'] ) ) {
            	$this->field['desc'] = "";
            }
            if ( empty( $this->field['demo'] ) ) {
            	$this->field['demo'] = "";
            }
            if ( empty( $this->field['feat'] ) ) {
            	$this->field['feat'] = "";
            }
            if ( empty( $this->field['join'] ) ) {
            	$this->field['join'] = "http://www.thinkupthemes.com/pricing/";
            }

		$ct = wp_get_theme();
		$theme_data = $ct;
		$item_name = $ct->get('Name');
		$screenshot = $ct->get_screenshot();
		$class = 'screenshot-' . $this->field['name'];

		$customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'redux-framework' ), $ct->display('Name') );

            echo '</td></tr></table>';

				if ( $this->field['section'] == 'header' ) {

					echo	'<div id="redux-promotion-field-header" class="' . $this->field['style'] . $this->field['class'] . '">';
					
					echo	'<div id="promotion-table">';
					echo	'<div id="promotion-header">';
					echo	'<p class="main-title">Upgrade for $31 (10% off)</p>';
//					echo	'<p class="secondary-title">Never code again! Upgrade to use our page builder.<br />Now you can create amazing websites faster than ever before!</p>';
					echo	'<a href="http://www.thinkupthemes.com/themes/minamaze/" target="_blank" class="promotion-button">Upgrade Now</a>';
//					echo	'<a href="http://www.thinkupthemes.com/pricing/" target="_blank"><img src="' . get_stylesheet_directory_uri() . '/admin/main/assets/img/promotion/ThinkUpThemes_Promotion.png"></a>';
					echo	'</div>';

					echo	'<div id="promotion-coupon">';
					echo	'<a href="http://www.thinkupthemes.com/themes/minamaze/" target="_blank">minamaze31<span>Normally $35. Use coupon at checkout.</span></a>';
					echo	'</div>';
					echo	'</div>';

					echo	'<p class="main-title">So... Why upgrade?</p>';
					echo	'<p class="secondary-title">We&#39;re glad you asked! Here&#39;s just some of the amazing features you&#39;ll get when you upgrade...</p>';
					echo	'</div>';
				}

				if ( $this->field['section'] == 'main' ) {

					echo	'<div id="redux-promotion-field-item" class="' . $this->field['style'] . $this->field['class'] . '">';
					echo	'<div class="' . esc_attr( $class ) . ' has-screenshot">';
					echo	'<a href="' . $this->field['feat'] . '" class="promotion-image " title="' . esc_attr( $customize_title ) . '">';
					echo	'<img src="' . $this->field['image'] . '" alt="Premium WordPress Theme - ' . $this->field['name'] . '" />';
					echo	'</a>';
					echo	'</div>';
					echo	'</div>';

				}

				if ( $this->field['section'] == 'footer' ) {

					echo	'<div id="redux-promotion-field-footer" class="' . $this->field['style'] . $this->field['class'] . '">';
					echo '<div id="promotion-footer">';
					echo	'<ul class="theme-button">';
					echo	'<li><a href="http://www.thinkupthemes.com/themes/" target="_blank" class="blue" ><h4>Browse All Themes</h4></a></li>';
					echo	'</ul>';
					echo '</div>';
					echo '</div>';

				}

            echo '<table class="form-table no-border" style="margin-top: 0;"><tbody><tr><th></th><td>';
        
        }

        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {

            wp_enqueue_style(
                'redux-field-promotion-css',
                REDUX_URL . 'inc/fields/promotion/field_promotion.css',
                time(),
                true
            );

        }

    }
}