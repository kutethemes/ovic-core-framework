<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Ovic\Framework\Roles;
use Ovic\Framework\Posts;

/**
 * Get the evaluated view contents for the given view.
 *
 * @param  string|null  $view
 *
 * @return string
 */
function name_blade( $view = null )
{
    if ( !view()->exists($view) ) {
        $view = "ovic::{$view}";
    }

    return $view;
}

function name_cache( $name )
{
    $auth = Auth::check() ? Auth::user()->id : 'public';

    return "_ovic_{$auth}_{$name}";
}

function user_can( $can, $data = null )
{
    switch ( $can ) {
        case 'view':
            $key = 0;
            break;
        case 'add':
            $key = 1;
            break;
        case 'edit':
            $key = 2;
            break;
        case 'delete':
            $key = 3;
            break;
    }
    if ( $data == null ) {
        $route      = Route::currentRouteName();
        $route      = explode('.', $route, 2);
        $route      = $route[0];
        $permission = Roles::Permission($route);
    } elseif ( is_array($data) ) {
        $permission = $data;
    } elseif ( is_string($data) ) {
        $permission = Roles::Permission($data);
    }

    if ( $can == 'all' ) {
        if ( !empty($permission) ) {
            return $permission;
        }
        return [ 0, 0, 0, 0 ];
    }

    if ( !empty($permission[$key]) && $permission[$key] == 1 ) {
        return true;
    }

    return false;
}

function button_set( $button, $permission, $attr = [] )
{
    switch ( $button ) {
        case 'add':
            $key     = 1;
            $class   = ' add-post';
            $default = [
                'text'  => 'Lưu',
                'type'  => 'button',
                'icon'  => 'fa fa-upload',
                'class' => 'btn-primary',
            ];
            break;
        case 'edit':
            $key     = 2;
            $class   = ' edit-post';
            $default = [
                'text'  => 'Sửa',
                'type'  => 'button',
                'icon'  => 'fa fa-save',
                'class' => 'btn-primary',
            ];
            break;
        case 'delete':
            $key     = 3;
            $class   = ' delete-post';
            $default = [
                'text'  => 'Xóa',
                'type'  => 'button',
                'icon'  => 'fa fa-trash-o',
                'class' => 'btn-danger',
            ];
            break;
        default:
            $key     = 9;
            $class   = ' custom-post';
            $default = [
                'text'  => 'Button',
                'type'  => 'button',
                'icon'  => 'fa fa-save',
                'class' => 'btn-primary',
            ];
            break;
    }
    $attr = ovic_parse_args($attr, $default);

    if ( $key == 9 || !empty($permission[$key]) && $permission[$key] == true ) {

        return view(
            name_blade('Components.button'))
            ->with([
                'text'  => $attr['text'],
                'type'  => $attr['type'],
                'icon'  => $attr['icon'],
                'class' => $attr['class'] . $class,
            ]);
    }

    return false;
}

function get_attachment_url( $id, $is_path = false )
{
    $path = !$is_path ? Posts::where('id', '=', $id)->value('name') : $id;

    return route('images.build', explode('/', $path));
}


/**
 * @uses : https://laravel.com/docs/master/collections#method-groupby
 * @atts['type']: dropdown, list, group
 * @example:
 *  _menu_tree( $donvi, ['title' => 'tendonvi', 'type' => 'group','groupBy' => 1] )
 *
 * */
function _menu_tree( $resource, $atts )
{
    $resource = remove_level($resource);
    $atts     = ovic_parse_args($atts, [
        'id'      => 'id',
        'title'   => 'title',
        'type'    => 'list',
        'groupBy' => 0,
    ]);
    $html     = '';
    $before   = '';
    $after    = '';
    foreach ( $resource as $parent ) {
        $class = '';
        $level = $parent['level'];
        $title = $parent[$atts['title']];
        switch ( $atts['type'] ) {

            case 'list':

                if ( $parent['haschild'] == 1 ) {
                    $class = ' has-children';
                }
                $before = "<li id='menu-{$parent[$atts['id']]}' class='menu-item{$class}'>";
                $after  = "</li>";

                break;

            case 'dropdown':

                $title = str_repeat('-', $level) . " {$title}";
                $title = "<option value='{$parent[$atts['id']]}'>{$title}</option>";

                break;

            case 'group':

                if ( $level <= $atts['groupBy'] ) {
                    $before = "<optgroup label='{$title}'>";
                    $after  = "</optgroup>";
                }

                $title = "<option value='{$parent[$atts['id']]}'>{$title}</option>";

                break;
        }
        $html .= $before;
        $html .= $title;
        $html .= $after;
    }

    return $html;
}

function _menu_tree_old( $resource, $atts, $parent_id = 0, $level = 0 )
{
    $atts       = ovic_parse_args($atts, [
        'id'      => 'id',
        'title'   => 'title',
        'type'    => 'list',
        'groupBy' => 0,
    ]);
    $html       = '';
    $before     = '';
    $after      = '';
    $sub_before = '';
    $sub_after  = '';
    foreach ( $resource[$parent_id] as $parent ) {
        $class = '';
        $title = $parent[$atts['title']];
        switch ( $atts['type'] ) {

            case 'list':

                if ( isset($resource[$parent[$atts['id']]]) ) {
                    $class = ' has-children';
                }
                $before     = "<li id='menu-{$parent[$atts['id']]}' class='menu-item{$class}'>";
                $after      = "</li>";
                $sub_before = "<ul class='sub-menu'>";
                $sub_after  = "</ul>";

                break;

            case 'dropdown':

                $title = str_repeat('-', $level) . " {$title}";
                $title = "<option value='{$parent[$atts['id']]}'>{$title}</option>";

                break;

            case 'group':

                if ( $level <= $atts['groupBy'] ) {
                    $before = "<optgroup label='{$title}'>";
                    $after  = "</optgroup>";
                }

                $title = "<option value='{$parent[$atts['id']]}'>{$title}</option>";

                break;
        }
        $html .= $before;
        $html .= $title;
        if ( isset($resource[$parent[$atts['id']]]) ) {
            $html .= $sub_before;
            $html .= _menu_tree($resource, $atts, $parent[$atts['id']], $level + 1);
            $html .= $sub_after;
        }
        $html .= $after;
    }

    return $html;
}

function _menu_tree_arr( $resource, $parent_id = 0, $level = false )
{
    $data = [];

    foreach ( $resource[$parent_id] as $parent ) {
        if ( $level == false ) {
            $data[] = $parent;
            if ( isset($resource[$parent['id']]) ) {
                $childrens = _menu_tree_arr($resource, $parent['id']);
                if ( !empty($childrens) ) {
                    foreach ( $childrens as $children ) {
                        $data[] = $children;
                    }
                }
            }
        } else {
            if ( isset($resource[$parent['id']]) ) {
                $parent['child'] = _menu_tree_arr($resource, $parent['id']);
            }
            $data[] = $parent;
        }
    }

    return $data;
}

function remove_level( $resource, $data = [], $level = 0 )
{
    if ( !empty($resource) ) {
        foreach ( $resource as $key => $parent ) {
            $key                = $parent['id'];
            $parent['level']    = $level;
            $parent['haschild'] = !empty($parent['children']) ? 1 : 0;
            $data[$key]         = $parent;
            if ( !empty($parent['children']) ) {
                $data = remove_level($parent['children'], $data, $level + 1);
            }
            unset($data[$key]['children']);
        }
    }

    return $data;
}

/**
 * Parses a string into variables to be stored in an array.
 *
 * Uses {@link https://secure.php.net/parse_str parse_str()} and stripslashes if
 * {@link https://secure.php.net/magic_quotes magic_quotes_gpc} is on.
 *
 * @param  string  $string  The string to be parsed.
 * @param  array  $array  Variables will be stored in this array.
 *
 * @since 2.2.1
 *
 */
function ovic_parse_str( $string, &$array )
{
    ovic_parse_str($string, $array);
    if ( get_magic_quotes_gpc() ) {
        $array = stripslashes_deep($array);
    }
}

/**
 * Merge user defined arguments into defaults array.
 *
 * This function is used throughout WordPress to allow for both string or array
 * to be merged into another array.
 *
 * @param  string|array|object  $args  Value to merge with $defaults.
 * @param  array  $defaults  Optional. Array that serves as the defaults. Default empty.
 *
 * @return array Merged user defined values with defaults.
 * @since 2.3.0 `$args` can now also be an object.
 *
 * @since 2.2.0
 */
function ovic_parse_args( $args, $defaults = '' )
{
    if ( is_object($args) ) {
        $r = get_object_vars($args);
    } elseif ( is_array($args) ) {
        $r =& $args;
    } else {
        ovic_parse_str($args, $r);
    }

    if ( is_array($defaults) ) {
        return array_merge($defaults, $r);
    }

    return $r;
}

/**
 * Unserialize value only if it was serialized.
 *
 * @param  string  $original  Maybe unserialized original, if is needed.
 *
 * @return mixed Unserialized data can be any type.
 * @since 2.0.0
 *
 */
function maybe_unserialize( $original )
{
    if ( is_serialized($original) ) { // don't attempt to unserialize data that wasn't serialized going in
        return @unserialize($original);
    }

    return $original;
}

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @param  string  $data  Value to check to see if was serialized.
 * @param  bool  $strict  Optional. Whether to be strict about the end of the string. Default true.
 *
 * @return bool False if not serialized and true if it was.
 * @since 2.0.5
 *
 */
function is_serialized( $data, $strict = true )
{
    // if it isn't a string, it isn't serialized.
    if ( !is_string($data) ) {
        return false;
    }
    $data = trim($data);
    if ( 'N;' == $data ) {
        return true;
    }
    if ( strlen($data) < 4 ) {
        return false;
    }
    if ( ':' !== $data[1] ) {
        return false;
    }
    if ( $strict ) {
        $lastc = substr($data, -1);
        if ( ';' !== $lastc && '}' !== $lastc ) {
            return false;
        }
    } else {
        $semicolon = strpos($data, ';');
        $brace     = strpos($data, '}');
        // Either ; or } must exist.
        if ( false === $semicolon && false === $brace ) {
            return false;
        }
        // But neither must be in the first X characters.
        if ( false !== $semicolon && $semicolon < 3 ) {
            return false;
        }
        if ( false !== $brace && $brace < 4 ) {
            return false;
        }
    }
    $token = $data[0];
    switch ( $token ) {
        case 's':
            if ( $strict ) {
                if ( '"' !== substr($data, -2, 1) ) {
                    return false;
                }
            } elseif ( false === strpos($data, '"') ) {
                return false;
            }
        // or else fall through
        case 'a':
        case 'O':
            return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
        case 'b':
        case 'i':
        case 'd':
            $end = $strict ? '$' : '';

            return (bool) preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
    }

    return false;
}

/**
 * Check whether serialized data is of string type.
 *
 * @param  string  $data  Serialized data.
 *
 * @return bool False if not a serialized string, true if it is.
 * @since 2.0.5
 *
 */
function is_serialized_string( $data )
{
    // if it isn't a string, it isn't a serialized string.
    if ( !is_string($data) ) {
        return false;
    }
    $data = trim($data);
    if ( strlen($data) < 4 ) {
        return false;
    } elseif ( ':' !== $data[1] ) {
        return false;
    } elseif ( ';' !== substr($data, -1) ) {
        return false;
    } elseif ( $data[0] !== 's' ) {
        return false;
    } elseif ( '"' !== substr($data, -2, 1) ) {
        return false;
    } else {
        return true;
    }
}

/**
 * Serialize data, if needed.
 *
 * @param  string|array|object  $data  Data that might be serialized.
 *
 * @return mixed A scalar data
 * @since 2.0.5
 *
 */
function maybe_serialize( $data )
{
    if ( is_array($data) || is_object($data) ) {
        return serialize($data);
    }

    // Double serialization is required for backward compatibility.
    // See https://core.trac.wordpress.org/ticket/12930
    // Also the world will end. See WP 3.6.1.
    if ( is_serialized($data, false) ) {
        return serialize($data);
    }

    return $data;
}

/**
 * Convert a value to non-negative integer.
 *
 * @param  mixed  $maybeint  Data you wish to have converted to a non-negative integer.
 *
 * @return int A non-negative integer.
 * @since 2.5.0
 *
 */
function absint( $maybeint )
{
    return abs(intval($maybeint));
}

/**
 * Convert float number to format based on the locale.
 *
 * @param  float  $number  The number to convert based on locale.
 * @param  int  $decimals  Optional. Precision of the number of decimal places. Default 0.
 *
 * @return string Converted number in string format.
 * @global WP_Locale $wp_locale
 *
 * @since 2.3.0
 *
 */
function number_format_i18n( $number, $decimals = 0 )
{
    $formatted = number_format($number, absint($decimals));

    /**
     * Filters the number formatted based on the locale.
     *
     * @param  string  $formatted  Converted number in string format.
     * @param  float  $number  The number to convert based on locale.
     * @param  int  $decimals  Precision of the number of decimal places.
     *
     * @since 4.9.0 The `$number` and `$decimals` parameters were added.
     *
     * @since 2.8.0
     */
    return $formatted;
}

/**
 * Convert number of bytes largest unit bytes will fit into.
 *
 * It is easier to read 1 KB than 1024 bytes and 1 MB than 1048576 bytes. Converts
 * number of bytes to human readable number by taking the number of that unit
 * that the bytes will go into it. Supports TB value.
 *
 * Please note that integers in PHP are limited to 32 bits, unless they are on
 * 64 bit architecture, then they have 64 bit size. If you need to place the
 * larger size then what PHP integer type will hold, then use a string. It will
 * be converted to a double, which should always have 64 bit length.
 *
 * Technically the correct unit names for powers of 1024 are KiB, MiB etc.
 *
 * @param  int|string  $bytes  Number of bytes. Note max integer size for integers.
 * @param  int  $decimals  Optional. Precision of number of decimal places. Default 0.
 *
 * @return string|false False on failure. Number string on success.
 * @since 2.3.0
 *
 */
function size_format( $bytes, $decimals = 0 )
{
    $KB_IN_BYTES = 1024;
    $MB_IN_BYTES = 1024 * $KB_IN_BYTES;
    $GB_IN_BYTES = 1024 * $MB_IN_BYTES;
    $TB_IN_BYTES = 1024 * $GB_IN_BYTES;

    $quant = array(
        'TB' => $TB_IN_BYTES,
        'GB' => $GB_IN_BYTES,
        'MB' => $MB_IN_BYTES,
        'KB' => $KB_IN_BYTES,
        'B'  => 1,
    );

    if ( 0 === $bytes ) {
        return number_format_i18n(0, $decimals) . ' B';
    }

    foreach ( $quant as $unit => $mag ) {
        if ( doubleval($bytes) >= $mag ) {
            return number_format_i18n($bytes / $mag, $decimals) . ' ' . $unit;
        }
    }

    return false;
}
