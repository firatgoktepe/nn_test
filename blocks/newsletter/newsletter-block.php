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


$audience_id = get_field('audience_id');
$subscribe_text = get_field('subscribe_text');
$email_placeholder_text = get_field('email_placeholder_text');

the_block_header('newsletter-block');
?>
<form id="mailchimp" action="<?php echo site_url() ?>/wp-admin/admin-ajax.php">
    <!-- <input type="text" name="fname" placeholder="First name" />
    <input type="text" name="lname" placeholder="Last name" /> -->
    <input type="email" name="email" placeholder="<?php echo $email_placeholder_text; ?>" required />
    <input type="hidden" name="audience_id" value="<?php echo $audience_id; ?>" />

    <button class="button-style"><?php echo $subscribe_text; ?></button>
    <input type="hidden" name="action" value="mailchimpsubscribe" />
</form>
<section id="mailchimp-result" style="display: none;">
    <span id="mailchimp-result-message"></span>
    <button class="button-style" onclick="mailchimp_show_hide('block', 'none', 'none');">Ok</button>
</section>
<section id="mailchimp-waiting" style="display: none;">
    <i class="fas fa-spinner"></i>
</section>

<script>
    function mailchimp_show_hide(formDisplay, resultDisplay, waitingDisplay) {
        var form = document.querySelector('#mailchimp');
        var result = document.querySelector('#mailchimp-result');
        var waiting = document.querySelector('#mailchimp-waiting');
        form.style.display = formDisplay;
        result.style.display = resultDisplay;
        waiting.style.display = waitingDisplay;
    }
    window.addEventListener("load", function() {
        function sendData() {
            const xhr = new XMLHttpRequest();

            xhr.addEventListener("load", function(event) {
                message.innerHTML = event.target.responseText;
                let email = document.querySelector('#mailchimp input[type=email]');
                email.value = '';
                mailchimp_show_hide('none', 'block', 'none');
            });
            xhr.addEventListener("error", function(event) {
                message.innerHTML = "Unable to contact Mailchimp.";
                mailchimp_show_hide('none', 'block', 'none');
            });

            let action = form.getAttribute('action');
            xhr.open('POST', action);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            xhr.send(params.toString());
            mailchimp_show_hide('none', 'none', 'block');
        }

        // Access the form element...
        let form = document.querySelector('#mailchimp');
        let result = document.querySelector('#mailchimp-result');
        let message = document.querySelector('#mailchimp-result-message');

        // ...and take over its submit event.
        form.addEventListener("submit", function(event) {
            event.preventDefault();
            sendData();
        });
    });
</script>
<?php
the_block_footer();
