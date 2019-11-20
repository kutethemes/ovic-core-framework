<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        case 'add':
            $key = 0;
            break;
        case 'edit':
            $key = 1;
            break;
        case 'delete':
            $key = 2;
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
        return [ 0, 0, 0 ];
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
            $key     = 0;
            $class   = ' add-post';
            $default = [
                'text'  => 'Thêm',
                'type'  => 'button',
                'icon'  => 'fa fa-upload',
                'class' => 'btn-primary',
            ];
            break;
        case 'edit':
            $key     = 1;
            $class   = ' edit-post';
            $default = [
                'text'  => 'Sửa',
                'type'  => 'button',
                'icon'  => 'fa fa-save',
                'class' => 'btn-primary',
            ];
            break;
        case 'delete':
            $key     = 2;
            $class   = ' delete-post';
            $default = [
                'text'  => 'Xóa',
                'type'  => 'button',
                'icon'  => 'fa fa-trash-o',
                'class' => 'btn-danger',
            ];
            break;
    }
    $attr = ovic_parse_args($attr, $default);

    if ( !empty($permission[$key]) && $permission[$key] == true ) {

        return view(
            name_blade('Components.button'))
            ->with([
                'text'  => $attr['text'],
                'type'  => $attr['type'],
                'icon'  => $attr['icon'],
                'class' => $attr['class'] . $class,
            ]);
    }
}

function get_attachment_url( $id, $is_path = false )
{
    $path = !$is_path ? Posts::where('id', '=', $id)->value('name') : $id;

    return route('images.build', explode('/', $path));
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

/**
 * Determines the difference between two timestamps.
 *
 * The difference is returned in a human readable format such as "1 hour",
 * "5 mins", "2 days".
 *
 * @param  int  $from  Unix timestamp from which the difference begins.
 * @param  int  $to  Optional. Unix timestamp to end the time difference. Default becomes time() if not set.
 *
 * @return string Human readable time difference.
 * @since 1.5.0
 *
 */
function human_time_diff( $from, $to = '' )
{
    $MINUTE_IN_SECONDS = 60;
    $HOUR_IN_SECONDS   = 60 * $MINUTE_IN_SECONDS;
    $DAY_IN_SECONDS    = 24 * $HOUR_IN_SECONDS;
    $WEEK_IN_SECONDS   = 7 * $DAY_IN_SECONDS;
    $MONTH_IN_SECONDS  = 30 * $DAY_IN_SECONDS;
    $YEAR_IN_SECONDS   = 365 * $DAY_IN_SECONDS;

    if ( empty($to) ) {
        $to = time();
    }

    $diff = (int) abs($to - $from);

    if ( $diff < $HOUR_IN_SECONDS ) {
        $mins = round($diff / $MINUTE_IN_SECONDS);
        if ( $mins <= 1 ) {
            $mins = 1;
        }
        /* translators: Time difference between two dates, in minutes (min=minute). %s: Number of minutes */
        $since = sprintf(_n('%s min', '%s mins', $mins), $mins);
    } elseif ( $diff < $DAY_IN_SECONDS && $diff >= $HOUR_IN_SECONDS ) {
        $hours = round($diff / $HOUR_IN_SECONDS);
        if ( $hours <= 1 ) {
            $hours = 1;
        }
        /* translators: Time difference between two dates, in hours. %s: Number of hours */
        $since = sprintf(_n('%s hour', '%s hours', $hours), $hours);
    } elseif ( $diff < $WEEK_IN_SECONDS && $diff >= $DAY_IN_SECONDS ) {
        $days = round($diff / $DAY_IN_SECONDS);
        if ( $days <= 1 ) {
            $days = 1;
        }
        /* translators: Time difference between two dates, in days. %s: Number of days */
        $since = sprintf(_n('%s day', '%s days', $days), $days);
    } elseif ( $diff < $MONTH_IN_SECONDS && $diff >= $WEEK_IN_SECONDS ) {
        $weeks = round($diff / $WEEK_IN_SECONDS);
        if ( $weeks <= 1 ) {
            $weeks = 1;
        }
        /* translators: Time difference between two dates, in weeks. %s: Number of weeks */
        $since = sprintf(_n('%s week', '%s weeks', $weeks), $weeks);
    } elseif ( $diff < $YEAR_IN_SECONDS && $diff >= $MONTH_IN_SECONDS ) {
        $months = round($diff / $MONTH_IN_SECONDS);
        if ( $months <= 1 ) {
            $months = 1;
        }
        /* translators: Time difference between two dates, in months. %s: Number of months */
        $since = sprintf(_n('%s month', '%s months', $months), $months);
    } elseif ( $diff >= $YEAR_IN_SECONDS ) {
        $years = round($diff / $YEAR_IN_SECONDS);
        if ( $years <= 1 ) {
            $years = 1;
        }
        /* translators: Time difference between two dates, in years. %s: Number of years */
        $since = sprintf(_n('%s year', '%s years', $years), $years);
    }

    /**
     * Filters the human readable difference between two timestamps.
     *
     * @param  string  $since  The difference in human readable text.
     * @param  int  $diff  The difference in seconds.
     * @param  int  $from  Unix timestamp from which the difference begins.
     * @param  int  $to  Unix timestamp to end the time difference.
     *
     * @since 4.0.0
     *
     */
    return $since;
}

/**
 * Sort-helper for timezones.
 *
 * @param  array  $a
 * @param  array  $b
 * @return int
 * @since 2.9.0
 * @access private
 *
 */
function _ovic_timezone_choice_usort_callback( $a, $b )
{
    // Don't use translated versions of Etc
    if ( 'Etc' === $a['continent'] && 'Etc' === $b['continent'] ) {
        // Make the order of these more like the old dropdown
        if ( 'GMT+' === substr($a['city'], 0, 4) && 'GMT+' === substr($b['city'], 0, 4) ) {
            return -1 * ( strnatcasecmp($a['city'], $b['city']) );
        }
        if ( 'UTC' === $a['city'] ) {
            if ( 'GMT+' === substr($b['city'], 0, 4) ) {
                return 1;
            }
            return -1;
        }
        if ( 'UTC' === $b['city'] ) {
            if ( 'GMT+' === substr($a['city'], 0, 4) ) {
                return -1;
            }
            return 1;
        }
        return strnatcasecmp($a['city'], $b['city']);
    }
    if ( $a['t_continent'] == $b['t_continent'] ) {
        if ( $a['t_city'] == $b['t_city'] ) {
            return strnatcasecmp($a['t_subcity'], $b['t_subcity']);
        }
        return strnatcasecmp($a['t_city'], $b['t_city']);
    } else {
        // Force Etc to the bottom of the list
        if ( 'Etc' === $a['continent'] ) {
            return 1;
        }
        if ( 'Etc' === $b['continent'] ) {
            return -1;
        }
        return strnatcasecmp($a['t_continent'], $b['t_continent']);
    }
}

/**
 * Gives a nicely-formatted list of timezone strings.
 *
 * @param  string  $selected_zone  Selected timezone.
 * @param  string  $locale  Optional. Locale to load the timezones in. Default current site locale.
 * @return string
 * @since 4.7.0 Added the `$locale` parameter.
 *
 * @since 2.9.0
 */
function ovic_timezone_choice( $selected_zone, $locale = null )
{
    $continents = array(
        'Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific'
    );

    $zonen = array();
    foreach ( timezone_identifiers_list() as $zone ) {
        $zone = explode('/', $zone);
        if ( !in_array($zone[0], $continents) ) {
            continue;
        }

        // This determines what gets set and translated - we don't translate Etc/* strings here, they are done later
        $exists    = array(
            0 => ( isset($zone[0]) && $zone[0] ),
            1 => ( isset($zone[1]) && $zone[1] ),
            2 => ( isset($zone[2]) && $zone[2] ),
        );
        $exists[3] = ( $exists[0] && 'Etc' !== $zone[0] );
        $exists[4] = ( $exists[1] && $exists[3] );
        $exists[5] = ( $exists[2] && $exists[3] );

        // phpcs:disable WordPress.WP.I18n.LowLevelTranslationFunction,WordPress.WP.I18n.NonSingularStringLiteralText
        $zonen[] = array(
            'continent'   => ( $exists[0] ? $zone[0] : '' ),
            'city'        => ( $exists[1] ? $zone[1] : '' ),
            'subcity'     => ( $exists[2] ? $zone[2] : '' ),
            't_continent' => ( $exists[3] ? str_replace('_', ' ', $zone[0]) : '' ),
            't_city'      => ( $exists[4] ? str_replace('_', ' ', $zone[1]) : '' ),
            't_subcity'   => ( $exists[5] ? str_replace('_', ' ', $zone[2]) : '' ),
        );
        // phpcs:enable
    }
    usort($zonen, '_ovic_timezone_choice_usort_callback');

    $structure = array();

    if ( empty($selected_zone) ) {
        $structure[] = '<option selected="selected" value="">Select a city</option>';
    }

    foreach ( $zonen as $key => $zone ) {
        // Build value in an array to join later
        $value = array( $zone['continent'] );

        if ( empty($zone['city']) ) {
            // It's at the continent level (generally won't happen)
            $display = $zone['t_continent'];
        } else {
            // It's inside a continent group

            // Continent optgroup
            if ( !isset($zonen[$key - 1]) || $zonen[$key - 1]['continent'] !== $zone['continent'] ) {
                $label       = $zone['t_continent'];
                $structure[] = '<optgroup label="' . $label . '">';
            }

            // Add the city to the value
            $value[] = $zone['city'];

            $display = $zone['t_city'];
            if ( !empty($zone['subcity']) ) {
                // Add the subcity to the value
                $value[] = $zone['subcity'];
                $display .= ' - ' . $zone['t_subcity'];
            }
        }

        // Build the value
        $value    = join('/', $value);
        $selected = '';
        if ( $value === $selected_zone ) {
            $selected = 'selected="selected" ';
        }
        $structure[] = '<option ' . $selected . 'value="' . $value . '">' . $display . '</option>';

        // Close continent optgroup
        if ( !empty($zone['city']) && ( !isset($zonen[$key + 1]) || ( isset($zonen[$key + 1]) && $zonen[$key + 1]['continent'] !== $zone['continent'] ) ) ) {
            $structure[] = '</optgroup>';
        }
    }

    // Do UTC
    $structure[] = '<optgroup label="UTC">';
    $selected    = '';
    if ( 'UTC' === $selected_zone ) {
        $selected = 'selected="selected" ';
    }
    $structure[] = '<option ' . $selected . 'value="UTC">UTC</option>';
    $structure[] = '</optgroup>';

    // Do manual UTC offsets
    $structure[]  = '<optgroup label="Manual Offsets">';
    $offset_range = array(
        -12,
        -11.5,
        -11,
        -10.5,
        -10,
        -9.5,
        -9,
        -8.5,
        -8,
        -7.5,
        -7,
        -6.5,
        -6,
        -5.5,
        -5,
        -4.5,
        -4,
        -3.5,
        -3,
        -2.5,
        -2,
        -1.5,
        -1,
        -0.5,
        0,
        0.5,
        1,
        1.5,
        2,
        2.5,
        3,
        3.5,
        4,
        4.5,
        5,
        5.5,
        5.75,
        6,
        6.5,
        7,
        7.5,
        8,
        8.5,
        8.75,
        9,
        9.5,
        10,
        10.5,
        11,
        11.5,
        12,
        12.75,
        13,
        13.75,
        14,
    );
    foreach ( $offset_range as $offset ) {
        if ( 0 <= $offset ) {
            $offset_name = '+' . $offset;
        } else {
            $offset_name = (string) $offset;
        }

        $offset_value = $offset_name;
        $offset_name  = str_replace(array( '.25', '.5', '.75' ), array( ':15', ':30', ':45' ), $offset_name);
        $offset_name  = 'UTC' . $offset_name;
        $offset_value = 'UTC' . $offset_value;
        $selected     = '';
        if ( $offset_value === $selected_zone ) {
            $selected = 'selected="selected" ';
        }
        $structure[] = '<option ' . $selected . 'value="' . $offset_value . '">' . $offset_name . '</option>';

    }
    $structure[] = '</optgroup>';

    echo join("\n", $structure);
}

/**
 * @uses : https://laravel.com/docs/master/collections#method-groupby
 * @atts['type']: dropdown, list, group
 * @example:
 *  _menu_tree( $donvi, ['title' => 'tendonvi', 'type' => 'group','groupBy' => 1] )
 *
 * */
function _menu_tree( $resource, $atts, $parent_id = 0, $level = 0 )
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
