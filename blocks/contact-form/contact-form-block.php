<?php

/**
 * ACF Block
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 *
 * @package Nax_Custom
 * 
 */

require_once get_template_directory() . '/blocks/blocks-functions.php';

the_block_header('contact-form-block');

$email_recipient = get_field('email_recipient');
$placeholder_select = get_field('placeholder_select');
$placeholder_company = get_field('placeholder_company');
$placeholder_orgnumber = get_field('placeholder_orgnumber');
$placeholder_name = get_field('placeholder_name');
$placeholder_email = get_field('placeholder_email');
$placeholder_phone = get_field('placeholder_phone');
$placeholder_message = get_field('placeholder_message');
$accept_gdpr_text = get_field('accept_gdpr_text');
$gdpr_link = get_field('gdpr_link');
$button_form_not_focus = get_field('button_form_not_focus');
$button_ok_to_send_text = get_field('button_ok_to_send_text');
$button_fill_from_before_sending_text = get_field('button_fill_from_before_sending_text');
$thank_you_message = get_field('thank_you_message');

$link_url = esc_url($gdpr_link['url']);
$link_title = esc_attr($gdpr_link['title']);
$link_target = esc_attr($gdpr_link['target'] ? $gdpr_link['target'] : '_self');

?>
<form id="contactform" action="<?php echo site_url(); ?>/wp-admin/admin-ajax.php">
    <select id="employees" name="employees" required="required">
        <option value="" selected="selected"><?php echo $placeholder_select; ?></option>
        <?php while (have_rows('selection_items')) : the_row();
            $item = get_sub_field('item');
        ?>
            <option value="<?php echo $item; ?>"><?php echo $item; ?></option>
        <?php endwhile; ?>
    </select>
    <input id="company" type="text" name="company" placeholder="<?php echo $placeholder_company; ?>" required="required" />
    <input id="orgnumber" type="text" name="orgnumber" placeholder="<?php echo $placeholder_orgnumber; ?>" required="required" />
    <input id="name" type="text" name="name" placeholder="<?php echo $placeholder_name; ?>" required="required" />
    <input id="email" type="email" name="email" placeholder="<?php echo $placeholder_email; ?>" required="required" />
    <input id="tel" type="tel" name="tel" placeholder="<?php echo $placeholder_phone; ?>" required="required" />
    <textarea id="body" name="body" placeholder="<?php echo $placeholder_message; ?>" rows="3" required="required"></textarea>

    <input id="gdpr_ok" type="checkbox" name="gdpr_ok" required="required" />
    <label for="gdpr_ok">
        <?php echo $accept_gdpr_text; ?>
        <a tabindex="-1" href="<?php echo $link_url; ?>" target="<?php echo $link_target; ?>" rel="noopener" rel="noreferrer" title="<?php echo $link_title; ?>">
            <?php echo $link_title; ?>
        </a>
    </label>

    <button class="button-style">
        <span class="unfocus"><?php echo $button_form_not_focus; ?></span>
        <span class="valid"><?php echo $button_ok_to_send_text; ?></span>
        <span class="invalid"><?php echo $button_fill_from_before_sending_text; ?></span>
    </button>

    <input id="to" type="hidden" name="to" value="<?php echo $email_recipient; ?>" />
    <input id="thank_you_message" type="hidden" name="thank_you_message" value="<?php echo $thank_you_message; ?>" />
    <input type="hidden" name="action" value="contactformsubmit" />
</form>
<section id="contactform-result" style="display: none;">
    <span id="contactform-result-message"></span>
    <button class="button-style" onclick="contactform_show_hide('none', 'none');">Ok</button>
</section>
<section id="contactform-waiting" style="display: none;">
    <i class="fas fa-spinner"></i>
</section>

<script>
    function contactform_show_hide(resultDisplay, waitingDisplay) {
        var result = document.querySelector('#contactform-result');
        var waiting = document.querySelector('#contactform-waiting');
        result.style.display = resultDisplay;
        waiting.style.display = waitingDisplay;
    }
    window.addEventListener("load", function() {
        function sendData() {
            const xhr = new XMLHttpRequest();

            xhr.addEventListener("load", function(event) {
                message.innerHTML = event.target.responseText;
                form.reset();
                contactform_show_hide('flex', 'none');
            });
            xhr.addEventListener("error", function(event) {
                message.innerHTML = "Det gick inte skicka just nu, försök igen!";
                contactform_show_hide('flex', 'none');
            });

            let action = form.getAttribute('action');
            xhr.open('POST', action);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            xhr.send(params.toString());
            contactform_show_hide('none', 'flex');
        }

        // Access the form element...
        let form = document.querySelector('#contactform');
        let result = document.querySelector('#contactform-result');
        let message = document.querySelector('#contactform-result-message');

        // ...and take over its submit event.
        form.addEventListener("submit", function(event) {
            event.preventDefault();
            sendData();
        });
    });
</script>


<?php
the_block_footer();
