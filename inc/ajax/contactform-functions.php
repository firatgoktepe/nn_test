<?php

/**
 * contactform functions
 *
 * @package Nax_Custom
 */

function ajax_contactform_subscribe()
{
    $employees = sanitize_text_field(get_post_parameter('employees'));
    $orgnumber = sanitize_text_field(get_post_parameter('orgnumber'));
    $company = sanitize_text_field(get_post_parameter('company'));
    $name = sanitize_text_field(get_post_parameter('name'));
    $email = sanitize_email(get_post_parameter('email'));
    $email = "{$name} <{$email}>";
    $tel = sanitize_text_field(get_post_parameter('tel'));
    $body = get_post_parameter('body');
    $body = implode("\n", array_map('sanitize_text_field', explode("\n", $body)));
    $body = wpautop($body);
    $to = sanitize_email(get_post_parameter('to'));
    $thank_you_message = sanitize_text_field(get_post_parameter('thank_you_message'));
    $admin_email = get_option('admin_email');
    $admin_email = "MedHelp Kampanjsajt <{$admin_email}>";

    $subject = "MedHelp Kampanjsajt - Beställning från {$name}, {$company}";
    $message = "<fieldset>";
    $message .= "<legend>Personuppgifter</legend>";
    $message .= "<table>";
    $message .= "<tr><td>Företag: </td><td>{$company}</td></tr>";
    $message .= "<tr><td>Organisationsnummer: </td><td>{$orgnumber}</td></tr>";
    $message .= "<tr><td>Antal anställda: </td><td>{$employees}</td></tr>";
    $message .= "<tr><td>Namn: </td><td>{$name}</td></tr>";
    $message .= "<tr><td>Email: </td><td><a href='mailto:{$email}' target='_blank' rel='noopener' rel='noreferrer'>{$email} - Maila</a></td></tr>";
    $message .= "<tr><td>Telefon: </td><td><a href='tel:{$tel}' target='_blank' rel='noopener' rel='noreferrer'>{$tel} - Ring</a>, <a href='https://hitta.se/sök?vad={$tel}' target='_blank'>Sök på hitta.se</a></td></tr>";
    $message .= "</table>";
    $message .= "</fieldset>";
    $message .= "<fieldset>";
    $message .= "<legend>Meddelande</legend>";
    $message .= $body;
    $message .= "</fieldset>";
    $headers = array(
        "Content-type: text/html",
        "To: {$to}",
        "From: no-reply@fw-mail.com",
        "Reply-To: {$email}"
    );

    $sent = wp_mail($to, $subject, $message, $headers);
    if ($sent) {
        echo $thank_you_message;
    } else {
        echo "Det gick inte skicka just nu, försök igen!";
    }

    die;
}
add_action('wp_ajax_contactformsubmit', 'ajax_contactform_subscribe');
add_action('wp_ajax_nopriv_contactformsubmit', 'ajax_contactform_subscribe');
