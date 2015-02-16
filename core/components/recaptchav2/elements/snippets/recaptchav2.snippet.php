<?php
/**
 * Based on https://github.com/google/ReCAPTCHA/tree/master/php
 *
 * @copyright Copyright (c) 2014, Google Inc.
 * @link      http://www.google.com/recaptcha
 *
 * Ported to MODX by YJ Tso @sepiariver
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

// Register API keys at https://www.google.com/recaptcha/admin
$site_key = $modx->getOption('recaptchav2.site_key', null, '');
$secret = $modx->getOption('recaptchav2.secret_key', null, '');
// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$lang = $modx->getOption('cultureKey', null, 'en');

// Options
$tech_err_msg = (!empty($hook->formit->config['technical_error_message'])) ? $hook->formit->config['technical_error_message'] : 'Sorry, there was an error submitting your form. Please use one of the contacts on this page instead.';
$recaptcha_err_msg = (!empty($hook->formit->config['recaptcha_error_message'])) ? $hook->formit->config['recaptcha_error_message'] : 'Please select the checkbox in the ReCaptcha image.';

$default_core_path = $modx->getOption('core_path') . 'components/recaptchav2/';
$recaptchav2_core_path = $modx->getOption('recaptchav2.core_path', null, $default_core_path);
$recaptchav2 = $modx->getService('recaptchav2', 'RecaptchaV2', $recaptchav2_core_path . 'model/recaptchav2/', $scriptProperties);
if (!($recaptchav2 instanceof RecaptchaV2)) {
    $hook->addError('recaptchav2_error', $tech_err_msg);
    return false;
}

// The response from reCAPTCHA
$resp = null;
// The error code from reCAPTCHA, if any
$error = null;

$reCaptcha = $recaptchav2->initReCaptcha($secret);
if (!$reCaptcha) {
    $hook->addError('recaptchav2_error', $tech_err_msg);
    return false;
}

// Was there a reCAPTCHA response?
if ($hook->getValue('g-recaptcha-response')) {
    $resp = $recaptchav2->verifyResponse($_SERVER["REMOTE_ADDR"], $hook->getValue('g-recaptcha-response'));
}

if ($resp != null && $resp->success) {
    return true;
} else {
    $hook->addError('recaptchav2_error', $recaptcha_err_msg);
    return false;
}
//$hook->addError('recaptchav2_error', __LINE__);
//return false;