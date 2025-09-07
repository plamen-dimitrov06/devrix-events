<?php
/**
 * The template for displaying Event archives.
 *
 * @package WordPress
 * @subpackage basic-events
 * @since 1.0.0
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <style>
        body {
            font-family:Roboto, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #222;
        }
        .event-article {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }
        .event-article:last-of-type {
            border-bottom: none;
        }
        .event-title a {
            color: #0073aa;
            text-decoration: none;
            font-size: 1.5em;
        }
        .event-details {
            margin-top: 10px;
            line-height: 1.6;
        }
        .event-details strong {
            color: #555;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0073aa;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #005177;
        }
        .google-button {
            background-color: #4285F4;
        }
        .google-button:hover {
            background-color: #357ae8;
        }
        .other-button {
            background-color: #333;
        }
        .other-button:hover {
            background-color: #111;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1 class="page-title">
                <?php post_type_archive_title(); ?>
            </h1>
        </header>

        <main id="main" class="site-main" role="main">
            <?php if (have_posts()) : ?>
                <?php
                while (have_posts()) :
                    the_post();

                    $event_date     = get_post_meta(get_the_ID(), '_event_date', true);
                    $event_location = get_post_meta(get_the_ID(), '_event_location', true);
                    $event_url      = get_post_meta(get_the_ID(), '_event_url', true);
                    $event_type     = get_post_meta(get_the_ID(), '_event_type', true);
                    
                    $event_title = urlencode(get_the_title());
                    $event_description = urlencode(get_the_excerpt());
                    $event_date_formatted = date('Ymd\THis', strtotime($event_date));
                    $event_location_encoded = urlencode($event_location);

                    $google_calendar_url = 'https://www.google.com/calendar/render?action=TEMPLATE&text=' . $event_title . '&dates=' . $event_date_formatted . '/' . $event_date_formatted . '&details=' . $event_description . '&location=' . $event_location_encoded;
                    $ics_url = 'data:text/calendar;charset=utf8,BEGIN:VCALENDAR%0AVERSION:2.0%0APRODID:-//WordPress//EN%0ABEGIN:VEVENT%0AUID:' . uniqid() . '%0ADTSTART:' . $event_date_formatted . '%0ASUMMARY:' . $event_title . '%0ADESCRIPTION:' . $event_description . '%0ALOCATION:' . $event_location_encoded . '%0AEND:VEVENT%0AEND:VCALENDAR';
                    ?>

                    <article id="post-<?php the_ID(); ?>" class="event-article">
                        <header class="event-title">
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        </header>

                        <div class="event-details">
                            <?php if (!empty($event_date)) : ?>
                                <p><strong>Event Date:</strong> <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($event_date))); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($event_location)) : ?>
                                <p><strong>Location:</strong> <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($event_location); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($event_location); ?></a></p>
                            <?php endif; ?>

                            <?php if (!empty($event_url)) : ?>
                                <p><a href="<?php echo esc_url($event_url); ?>" target="_blank" rel="noopener noreferrer">External Link</a></p>
                            <?php endif; ?>

                            <p>
                                <a href="<?php echo esc_url($google_calendar_url); ?>" target="_blank" rel="noopener noreferrer" class="button google-button">Add to Google Calendar</a>
                                <a href="<?php echo esc_url($ics_url); ?>" download="event.ics" class="button other-button">Add to Other Calendar</a>
                            </p>

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    </article>

                <?php endwhile; ?>

            <?php else : ?>
                <p><?php esc_html_e('No events found.', 'basic-events'); ?></p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
