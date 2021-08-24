<?php

class Walker_Taxonomy extends Walker
{
    /*
     * Tell Walker where to inherit it's parent and id values
     */
    public $db_fields = array(
        'parent' => 'parent',
        'id'     => 'term_id'
    );

    /**
     * Start the element output.
     */
    public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 )
    {
        $output .= $object->name . ', ';
    }

    /**
     * Ends the element output
     */
    public function end_el(&$output, $object, $depth = 0, $args = array())
    {
        $output = rtrim($output, ', ');
    }
}