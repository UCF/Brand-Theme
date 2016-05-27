<?php

/**
 * Base Shortcode class
 **/
abstract class Shortcode {
    public
        $name        = 'Shortcode', // The name of the shortcode.
        $command     = 'shortcode', // The command used to call the shortcode.
        $description = 'This is the description of the shortcode.', // The description of the shortcode.
        $callback    = 'callback',
        $wysiwyg     = True; // Whether to add it to the shortcode Wysiwyg modal.

    /*
     * Register the shortcode.
     * @since v0.1.0
     * @author Jim Barnes
     * @return void
     */
    public function register_shortcode() {
        add_shortcode( $this->command, array( $this, $this->callback ) );
    }

    /*
     * Returns parameters for the shortcode.
     * @since v0.1.0
     * @author Jo Dickson
     * @return array
     */
    public function params() {
        return array();
    }

    /*
     * Returns an individual parameter options (e.g. 'default', 'choices') by
     * the parameter's ID.  Specify a specific $option to just return that option.
     * @since v0.1.0
     * @author Jo Dickson
     * @return mixed
     */
    public function get_param_options( $param_id, $option=null ) {
        $params = $this->params();
        $retval = false;

        if ( $params ) {
            foreach ( $params as $param ) {
                if ( $param['id'] === $param_id ) {
                    if ( $option && isset( $param[$option] ) ) {
                        return $param[$option];
                    }
                    else {
                        return $param;
                    }
                }
            }
        }

        return $retval;
    }

    /*
     * Returns an array of shortcode attributes with default values applied
     * as necessary.
     * @since v0.1.0
     * @author Jo Dickson
     * @return array
     */
    public function get_shortcode_atts( $atts ) {
        $params = $this->params();
        $retval = $pairs = array();

        if ( is_array( $params ) && !empty( $params ) ) {
            foreach ( $params as $param ) {
                $pairs[$param['id']] = isset( $param['default'] ) ? $param['default'] : '';
            }

            $retval = shortcode_atts( $pairs, $atts, $this->command );
        }

        return $retval;
    }

    /*
     * Returns the html option markup.
     * @since v0.1.0
     * @author Jim Barnes
     * @return string
     */
    public function get_option_markup() {
        return sprintf('<option value="%s">%s</option>', $this->command, $this->name);
    }

    /*
     * Returns the description html markup.
     * @since v0.1.0
     * @author Jim Barnes
     * @return string
     */
    public function get_description_markup() {
        return sprintf('<li class="shortcode-%s">%s</li>', $this->command, $this->description);
    }

    /*
     * Returns the form html markup.
     * @since v0.1.0
     * @author Jim Barnes
     * @return string
     */
    public function get_form_markup() {
        ob_start();
?>
        <li class="shortcode-<?php echo $this->command; ?>">
            <h3><?php echo $this->name; ?> Options</h3>

            <?php if ( !$this->params() ): ?>
            <em>This shortcode has no options.</em>
            <?php else: ?>

            <table class="form-table">
                <tbody>
                    <?php
                    foreach( $this->params() as $param ) :
                        if ( !isset( $param['wysiwyg'] ) || $param['wysiwyg'] === true ):
                    ?>
                    <tr>
                        <th>
                            <?php echo $this->get_field_label( $param, $this->command ); ?>
                        </th>
                        <td>
                            <?php if ( $param['help_text'] ): ?>
                            <p class="help"><?php echo $param['help_text']; ?></p>
                            <?php endif; ?>
                            <?php echo $this->get_field_input( $param, $this->command ); ?>
                        </td>
                    </tr>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </tbody>
            </table>

            <?php endif; ?>
        </li>
<?php
        return ob_get_clean();
    }

    /*
     * Returns the appropriate label markup for the field.
     * @since v0.1.0
     * @author Jo Dickson
     * @return string
     */
    private function get_field_label( $field, $command ) {
        $type = isset( $field['type'] ) ? $field['type'] : 'text';
        $retval = '';

        switch ( $type ) {
            case 'checkbox':
                // Labels for checkboxes are printed adjacent to the input
                $retval = $field['name'];
                break;
            default:
                $retval = '<label for="'. $command . '-' . $field['id'] .'">'. $field['name'] .'</label>';
                break;
        }

        return $retval;
    }

    /*
     * Returns the appropriate markup for the field.
     * @since v0.1.0
     * @author Jim Barnes
     * return string
     */
    private function get_field_input( $field, $command ) {
        $name      = $field['name'];
        $id        = isset( $field['id'] ) ? $field['id'] : '';
        $type      = isset( $field['type'] ) ? $field['type'] : 'text';
        $default   = isset( $field['default'] ) ? $field['default'] : '';
        $template  = isset( $field['template'] ) ? $field['template'] : '';
        $input_id  = $command . '-' . $id;
        $retval    = '';

        switch( $type ) {
            case 'text':
            case 'date':
            case 'email':
            case 'url':
            case 'number':
            case 'color':
                $retval .= '<input class="shortcode-editor-input" id="'. $input_id .'" type="'. $type .'" name="'. $input_id .'" value="'. $default .'" data-parameter="'. $id .'">';
                break;
            case 'dropdown':
                $choices = is_array( $field['choices'] ) ? $field['choices'] : array();
                $retval .= '<select class="shortcode-editor-input" id="' . $input_id . '" type="text" name="' . $input_id . '" value="" data-parameter="' . $id . '">';
                foreach ( $choices as $choice ) {
                    $selected = '';
                    if ( $default == $choice['value'] ) {
                        $selected = 'selected';
                    }

                    $retval .= '<option value="' . $choice['value'] . '" '. $selected .'>' . $choice['name'] . '</option>';
                }
                $retval .= '</select>';
                break;
            case 'checkbox':
                $checked = filter_var( $default, FILTER_VALIDATE_BOOLEAN ) ? 'checked' : '';
                $retval .= '<input class="shortcode-editor-input" id="'. $input_id .'" type="'. $type .'" name="'. $input_id .'" '. $checked .' value="'. $default .'" data-parameter="'. $id .'"><label for="'. $input_id .'">'. $name .'</label>';
                break;
        }

        return $retval;
    }
}

class UIDSearchSC extends Shortcode {
    public
        $name        = 'UIDSearch', // The name of the shortcode.
        $command     = 'uid-search', // The command used to call the shortcode.
        $description = 'Displays up and coming academic calendar entries', // The description of the shortcode.
        $callback    = 'callback',
        $wysiwyg     = True; // Whether to add it to the shortcode Wysiwyg modal.
    public static function callback( $attr, $content='' ) {
        ob_start();
?>
        <div ng-app="UIDSearch"  ng-controller="UIDSearchController as uidSearchCtrl" ng-cloak>
            <div class="uid-search-form-inner col-md-9 col-sm-9">
                <label for="uid-search" class="sr-only">Search for a unit identifier</label>
                <input id="uid-search" class="form-control input-lg"
                    ng-model="uidSearchCtrl.searchQuery.term" ng-model-options="{ debounce: 300 }"
                    placeholder="Enter a unit name such as 'College of Sciences' or 'Registars Office'">
                <button class="btn btn-link" type="submit">Search</button>
            </div>
            <div class="error uid-error" ng-show="uidSearchCtrl.error"><span class="glyphicon glyphicon-alert"></span> Error loading Unit Identifiers</div>
            <div class="loading" ng-show="uidSearchCtrl.loading"><span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Searching for Unit Identifiers</div>

            <div ng-if="uidSearchCtrl.results.length && uidSearchCtrl.searchQuery.term.length > 2" ng-cloak>
                <hr>
                <h4>Results</h4>
                <div class="row">
                    <div class="col-md-4" ng-repeat="result in uidSearchCtrl.results">
                        <h5>{{ result.title.rendered }}</h5>
                        <img ng-src="https://s3.amazonaws.com/web.ucf.edu/uid/{{ result.slug }}/{{ result.slug }}.png" width="100%">
                    </div>
                </div>
            </div>
        </div>
<?php
        return ob_get_clean();

    }
}

?>
