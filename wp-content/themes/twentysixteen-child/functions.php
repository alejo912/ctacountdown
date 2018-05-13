<?php

// Link child theme to his parent
function my_theme_enqueue_styles()
{
    $parent_style = 'twentysixteen';

    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('twentysixteen-child',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}

add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
// ***************************************************************************************

// CTA Countdown by Oscar Alejandro López Paz

// cta countdown needs two basic functions on javascript to work correctly
function basic_cta_functions()
{
    echo '
    <script>
    // General function to update every countdown by id and remaining time
    function updateTime(cta_id) {
        var cta = jQuery("#" + cta_id);
        var now = new Date().getTime();
        var remaining = new Date(cta.data("remaining")).getTime();

        // Set to 0 negative values
        var difference = (remaining - now < 0) ? 0 : remaining - now;

        var days = Math.floor(difference / (1000 * 60 * 60 * 24));
        var hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((difference % (1000 * 60)) / 1000);

        cta.find(".cta-days").html(days);
        cta.find(".cta-hours").html(hours);
        cta.find(".cta-min").html(minutes);
        cta.find(".cta-sec").html(seconds);
    }

    // Update the number of bets
    function addBet(cta_id) {
        var number_bets = jQuery("#" + cta_id).find(".cta-title-description b");
        number_bets.html((parseInt(number_bets.html()) + 1).toString());
    }
    </script>
    ';
}

// Add hook for front-end <head></head>
add_action('wp_head', 'basic_cta_functions');
// *************************************************************************************


// CTA Countdown Shortcode
//[cta title="Our NFL Pick: Patriots +5" remaining="2017/04/20 14:25:00" id="countdown1"]
// id attribute is optional. Use this attribute if you need more than one CTA Countdown
function cta_countdown_func($atts)
{
    $attrs = shortcode_atts(array(
        'title' => 'Our NFL Pick: Vikings +3',
        'remaining' => '2018/07/02 14:25:00 GMT-5',
        'id' => 'countdown1'
    ), $atts);

    return '<div class="cta-countdown" data-remaining ="' . $attrs['remaining'] . '" id="' . $attrs['id'] . '">
        <div class="cta-body">
            <div class="cta-counter-row">
                <div class="cta-counter">
                    <div>
                        <small>DAYS</small>
                        <br>
                        <span class="cta-days fs1"></span>
                    </div>
                    <div>
                        <small>HOURS</small>
                        <br>
                        <span class="cta-hours fs1"></span>
                    </div>
                    <div>
                        <small>MIN</small>
                        <br>
                        <span class="cta-min fs1"></span>
                    </div>
                    <div>
                        <small>SEC</small>
                        <br>
                        <span class="cta-sec fs1"></span>
                    </div>
                </div>
                <div class="cta-counter-description fs4">
                    Remaining Time <br class="mob-hide">  To Place Bet
                </div>
            </div>
            <div class="cta-column">
                <div class="cta-title fs2">
                    ' . $attrs['title'] . '
                </div>
                <div class="cta-title-description fs3">
                    Hurry up! <strong><b>25</b></strong> people have placed this bet
                </div>
            </div>
        </div>
        <div class="cta-button-container">
            <div class="cta-button fs3">
                <button>BET & WIN</button>
            </div>
            <div class="cta-sponsor fs4">
                Trusted<br>
                Sportsbetting.ag

            </div>
        </div>
    </div>

    <script>
        jQuery("#' . $attrs['id'] . '").find(".cta-button button").on("click", function () {
            addBet("' . $attrs['id'] . '");
        });

        jQuery(document) . ready(function () {
            setInterval(function () {
                updateTime("' . $attrs['id'] . '");
                    }, 1000);
        });
    </script>';

}

add_shortcode('cta', 'cta_countdown_func');