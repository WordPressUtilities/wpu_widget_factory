<?php

/*
Plugin Name: WPU Widget Factory
Plugin URI: https://github.com/WordPressUtilities/wpu_widget_factory
Description: Easier build for WordPress widgets
Version: 0.1.0
Author: Darklg
Author URI: https://darklg.me/
License: MIT License
License URI: https://opensource.org/licenses/MIT
*/

class wpu_widget_factory extends WP_Widget {

    /* ----------------------------------------------------------
      Fields
    ---------------------------------------------------------- */

    public function wpu_widget_factory_get_field($field_id, $field) {
        if (!isset($field['default_value'])) {
            $field['default_value'] = '';
        }
        if (!isset($field['type'])) {
            $field['type'] = 'text';
        }
        if (!isset($field['label'])) {
            $field['label'] = ucfirst($field_id);
        }
        if (!isset($field['values']) || !is_array($field['values'])) {
            $field['values'] = array();
        }

        return $field;
    }

    public function wpu_widget_factory_form($instance) {
        foreach ($this->fields as $field_id => $field) {
            echo $this->wpu_widget_factory_get_field_edition_html($field_id, $field, $instance);
        }
    }

    function wpu_widget_factory_get_field_edition_html($field_id, $field, $instance) {
        /* Values */
        $field = $this->wpu_widget_factory_get_field($field_id, $field);
        $selected_value = isset($instance[$field_id]) && !empty($instance[$field_id]) ? $instance[$field_id] : $field['default_value'];
        $id_name = 'id="' . $this->get_field_id($field_id) . '" name="' . $this->get_field_name($field_id) . '"';

        $html = '';

        /* Display */
        $html .= '<p>';
        $html .= '<label for="' . $this->get_field_id($field_id) . '">' . esc_html($field['label']) . ':</label> ';
        switch ($field['type']) {
        case 'select':
            $html .= '<select class="widefat" ' . $id_name . '>';
            foreach ($field['values'] as $key => $value) {
                $html .= '<option value="' . esc_attr($key) . '"' . selected($selected_value, $key, false) . '>' . esc_html($value) . '</option>';
            }
            $html .= '</select>';
            break;
        default:
            $html .= '<input class="widefat" ' . $id_name . ' type="' . esc_attr($field['type']) . '" value="' . esc_attr($selected_value) . '">';
        }
        $html .= '</p>';

        return $html;
    }

    /* ----------------------------------------------------------
      Update
    ---------------------------------------------------------- */

    public function wpu_widget_factory_update($new_instance, $old_instance) {
        $instance = array();
        foreach ($this->fields as $field_id => $field) {
            $field = $this->wpu_widget_factory_get_field($field_id, $field);

            /* Default to value */
            if (isset($old_instance[$field_id])) {
                $instance[$field_id] = $old_instance[$field_id];
            }

            /* New value not submitted */
            if (!isset($new_instance[$field_id])) {
                continue;
            }

            $new_value = false;
            $val_tmp = $new_instance[$field_id];

            switch ($field['type']) {
            case 'number':
                if (is_numeric($val_tmp)) {
                    $new_value = $val_tmp;
                }
                break;
            case 'select':
                if (isset($field['values'][$val_tmp])) {
                    $new_value = $val_tmp;
                }
                break;
            default:
                $new_value = strip_tags($val_tmp);

            }

            if ($new_value !== false) {
                $instance[$field_id] = $new_value;
            }

        }
        return $instance;
    }

    /* ----------------------------------------------------------
      Display
    ---------------------------------------------------------- */

    public function wpu_widget_factory_display($args, $instance, $content = '') {
        $title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');
        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        echo $content;
        echo $args['after_widget'];
    }

    /* ----------------------------------------------------------
      Default methods
    ---------------------------------------------------------- */

    public function form($instance) {
        $this->wpu_widget_factory_form($instance);
    }

    public function update($new_instance, $old_instance) {
        return $this->wpu_widget_factory_update($new_instance, $old_instance);
    }
}
