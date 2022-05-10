<?php

$shortcode_tags = [];

if (!function_exists('do_shortcode')) {
    function do_shortcode($content)
    {
        global $shortcode_tags;
        preg_match_all('@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $shortcodename);
        if (empty($shortcodename)) {
            return $content;
        }
        $content = strtr($content, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));

        if (empty($shortcodename)) {
            return $content;
        }
        foreach ($shortcodename[1] as $tagname) {
            $pattern = get_shortcode_regex($tagname);

            preg_match_all("/$pattern/", $content, $matches);

            $content = do_shortcodes_in_html_tags($content, $matches);
        }
        return $content;
    }
}

if (!function_exists('get_shortcode_regex')) {
    /**
     * Retrieve the shortcode pattern regex.
     * @return string The shortcode attribute regular expression
     */
    function get_shortcode_regex($tagname = null)
    {
        return
            '\\['                                // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagname)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            . '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            . '(?:'
            . '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            . '[^\\]\\/]*'               // Not a closing bracket or forward slash
            . ')*?'
            . ')'
            . '(?:'
            . '(\\/)'                        // 4: Self closing tag ...
            . '\\]'                          // ... and closing bracket
            . '|'
            . '\\]'                          // Closing bracket
            . '(?:'
            . '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            . '[^\\[]*+'             // Not an opening bracket
            . '(?:'
            . '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            . '[^\\[]*+'         // Not an opening bracket
            . ')*+'
            . ')'
            . '\\[\\/\\2\\]'             // Closing shortcode tag
            . ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
        // phpcs:enable
    }
}

if (!function_exists('get_shortcode_atts_regex')) {
    /**
     * Retrieve the shortcode attributes regex.
     * @return string The shortcode attribute regular expression
     */
    function get_shortcode_atts_regex()
    {
        return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|\'([^\']*)\'(?:\s|$)|(\S+)(?:\s|$)/';
    }
}

if (!function_exists('shortcode_parse_atts')) {
    /**
     * Retrieve all attributes from the shortcodes tag.
     */
    function shortcode_parse_atts($text)
    {
        $atts = array();
        $pattern = get_shortcode_atts_regex();
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", ' ', $text);
        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                } elseif (!empty($m[5])) {
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                } elseif (isset($m[7]) && strlen($m[7])) {
                    $atts[] = stripcslashes($m[7]);
                } elseif (isset($m[8]) && strlen($m[8])) {
                    $atts[] = stripcslashes($m[8]);
                } elseif (isset($m[9])) {
                    $atts[] = stripcslashes($m[9]);
                }
            }

            // Reject any unclosed HTML elements.
            foreach ($atts as &$value) {
                if (false !== strpos($value, '<')) {
                    if (1 !== preg_match('/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value)) {
                        $value = '';
                    }
                }
            }
        } else {
            $atts = ltrim($text);
        }

        return $atts;
    }
}

if (!function_exists('do_shortcodes_in_html_tags')) {
    /**
     * Convert shorcode to html
     * @return string The shortcode attribute regular expression
     */
    function do_shortcodes_in_html_tags($content, $matches)
    {
        $tag = $matches[2][0];
        $attr = shortcode_parse_atts($matches[3][0]);
        $html = find_shortcode_html($tag, $attr);
        $content = str_replace($matches[0][0], $html, $content);
        return $content;
    }
}

if (!function_exists('find_shortcode_html')) {
    /**
     * Render the shortcode view
     * @return string The shortcode attribute regular expression
     */
    function find_shortcode_html($tag, $attr)
    {
        return view('shortcodes.' . $tag)->with($attr)->render();

    }
}
