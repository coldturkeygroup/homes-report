<?php
/**
 * Template file for displaying single homes report
 *
 * @package    WordPress
 * @subpackage Homes Report
 * @author     The Cold Turkey Group
 * @since      1.0.0
 */

global $pf_homes_report, $wp_query;

$id = get_the_ID();
$title = get_the_title();
$frontdesk_campaign = get_post_meta($id, 'frontdesk_campaign', true);
$broker = get_post_meta($id, 'legal_broker', true);
$page_title = get_post_meta($id, 'page_title', true);
$page_subtitle = get_post_meta($id, 'page_subtitle', true);
$cta = get_post_meta($id, 'call_to_action', true);
$retargeting = get_post_meta($id, 'retargeting', true);
$conversion = get_post_meta($id, 'conversion', true);
$photo = get_post_meta($id, 'photo', true);
$name = get_post_meta($id, 'name', true);
$quiz_link = get_post_meta($id, 'seller_quiz', true);
$phone = of_get_option('phone_number');

// Get the background image
if (has_post_thumbnail($id))
    $img = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'full');

// Get the page colors
if (function_exists('of_get_option')) {
    $primary_color = of_get_option('primary_color');
    $hover_color = of_get_option('secondary_color');
}

$color_setting = get_post_meta($id, 'primary_color', true);
$hover_setting = get_post_meta($id, 'hover_color', true);

if ($color_setting && $color_setting != '')
    $primary_color = $color_setting;

if ($hover_setting && $hover_setting != '')
    $hover_color = $hover_setting;

?>
    <!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="utf-8">
        <title><?php wp_title('&middot;', true, 'right'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php wp_head(); ?>
        <style>
            .single-pf_homes_report {
                background: url(<?= $img[0]; ?>) no-repeat scroll center center;
                background-size: cover;
                background-attachment: fixed;
            }

            <?php
            if( $primary_color != null ) {
                echo '
                .hh-page .btn-primary {
                    background-color: ' . $primary_color . ' !important;
                    border-color: ' . $primary_color . ' !important; }
                .modal-body h2,
                .hh-page .results .fa {
                    color: ' . $primary_color . ' !important; }
                ';
            }
            if( $hover_color != null ) {
                echo '
                .hh-page .btn-primary:hover,
                .hh-page .btn-primary:active {
                    background-color: ' . $hover_color . ' !important;
                    border-color: ' . $hover_color . ' !important; }
                ';
            }
            ?>
        </style>
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
    </head>

<body <?php body_class(); ?>>
<div class="hh-page">

    <div class="container-fluid">

        <div class="results" style="display:none">
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-md-offset-2">
                    <img src="<?= $photo ?>" class="img-responsive img-thumbnail">
                </div>
                <div class="col-sm-5">
                    Hey, it's <?= $name ?>. I will send you an updated list of homes that sold within the next 24 hours.
                    <br>
                    Thanks for using the homes report tool! <br>
                    <strong>I'll email you a custom link as soon as I've researched your requested price range.</strong>
                    <?php if ($quiz_link != '') { ?>
                        <br> Oh, and one more thing. If you're thinking about selling your home this year, take my 14 question
                        <strong>Seller Quiz</strong> to find out if you're ready to sell.
                        <a href="<?= $quiz_link ?>" class="btn btn-primary btn-block">Take The Quiz</a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <form id="homes-report">
            <div class="row page animated fadeIn">
                <div class="col-xs-10 col-xs-offset-1 col-sm-12 col-sm-offset-0 col-md-8 col-md-offset-2" id="landing" data-model="landing">
                    <h1 style="text-align: center;" class="landing-title"><?= $page_title ?></h1>

                    <h2 style="text-align: center;" id="subtitle"><?= $page_subtitle ?></h2>

                    <div class="form-group">
                        <label class="control-label" for="location">City</label>
                        <input type="text" class="form-control validate" id="location" name="location" placeholder="Your City">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="price">Price Range</label>
                        <select class="form-control validate" id="price" name="price">
                            <option value="$0-$100,000">$0-$100,000</option>
                            <option value="$150,000-$200,000">$150,000-$200,000</option>
                            <option value="$200,000-$250,000">$200,000-$250,000</option>
                            <option value="$250,000-$300,000">$250,000-$300,000</option>
                            <option value="$300,000-$350,000">$300,000-$350,000</option>
                            <option value="$350,000-$400,000">$350,000-$400,000</option>
                            <option value="$400,000-$450,000">$400,000-$450,000</option>
                            <option value="$450,000-$500,000">$450,000-$500,000</option>
                            <option value="$500,000-$600,000">$500,000-$600,000</option>
                            <option value="$600,000+">$600,000+</option>
                        </select>
                    </div>

                    <button class="btn btn-primary btn-lg btn-block" id="get-results"><?= $cta ?></button>
                </div>
            </div>

            <div class="modal fade" id="get-results-modal" tabindex="-1" role="dialog" aria-labelledby="get-results-label" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <h1>Where Should We Send Your List?</h1>

                            <p>Homes in <span id="location-answer"></span> that sold between
                                <span id="price-answer"></span></p>

                            <div class="form-group" style="margin-top:20px">
                                <label for="first_name" class="control-label">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required="required" placeholder="Your First Name">
                            </div>
                            <div class="form-group">
                                <label for="email" class="control-label">Email Address</label>
                                <input type="text" name="email" id="email" class="form-control" required="required" placeholder="Your Email Address">
                            </div>

                            <input name="frontdesk_campaign" type="hidden" value="<?= $frontdesk_campaign ?>">
                            <input name="action" type="hidden" id="pf_homes_report_submit_form" value="pf_homes_report_submit_form">
                            <?php wp_nonce_field('pf_homes_report_submit_form', 'pf_homes_report_nonce'); ?>
                            <input name="page_id" type="hidden" value="<?= $id ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-block" id="submit-results">Send Me The List</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <div class="footer">
        <?php echo $broker;
        if ($phone != null) {
            echo ' &middot; ' . $phone;
        } ?>
    </div>

    <?php
    if ($retargeting != null) {
        ?>
        <!-- Facebook Pixel Code -->
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq)return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq)f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window,
                document, 'script', '//connect.facebook.net/en_US/fbevents.js');

            fbq('init', '<?= $retargeting ?>');
            fbq('track', "PageView");</script>
        <noscript><img height="1" width="1" style="display:none"
                       src="https://www.facebook.com/tr?id=<?= $retargeting ?>&ev=PageView&noscript=1"
            /></noscript>
        <?php
        echo '<input type="hidden" id="retargeting" value="' . $retargeting . '">';
    }

    if ($conversion != null) {
        echo '<input type="hidden" id="conversion" value="' . $conversion . '">';
    }
    ?>
</div>

<?php wp_footer(); ?>