# WPU Widget Factory

Easier build for WordPress widgets

```php

class My_Button_Widget extends wpu_widget_factory {

    public $fields = array(
        'button_title' => array(
            'label' => 'Titre'
        ),
        'link' => array(
            'label' => 'URL',
            'type' => 'url'
        ),
        'icon' => array(
            'label' => 'Icône',
            'type' => 'select',
            'values' => array(
                'phone-alt' => 'Téléphone',
                'calendar-check' => 'Calendrier',
                'euro-sign' => 'Euro'
            )
        )
    );

    public function __construct() {
        parent::__construct(
            'my_button_widget',
            '[My] Button',
            array(
                'description' => 'A nice button widget.'
            )
        );
    }

    public function widget($args, $instance) {
        /* Create content */
        $content = '<a href="' . esc_url($instance['link']) . '">';
        $content .= '<i class="fa fa-' . esc_attr($instance['icon']) . '"></i>';
        $content .= esc_html($instance['button_title']);
        $content .= '</a>';

        /* Load it */
        $this->wpu_widget_factory_display($args, $instance, $content);
    }

    /* Form can be overriden */
    // public function form($instance) {
    //     $this->wpu_widget_factory_form($instance);
    // }

    /* Update can be overriden */
    // public function update($new_instance, $old_instance) {
    //     return $this->wpu_widget_factory_update($new_instance, $old_instance);
    // }
}

add_action('widgets_init', function () {
    register_widget('My_Button_Widget');
});
```
