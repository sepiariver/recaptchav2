<?php
/**
 * Based on https://github.com/google/recaptcha
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
if ($hook->formit) {
    $properties = array_merge(array(), $hook->formit->config);
}

$tech_err_msg = $modx->getOption('technical_error_message', $properties, 'Sorry, there was an error submitting your form. Please use one of the contacts on this page instead.');
$recaptcha_err_msg = $modx->getOption('recaptcha_error_message', $properties, 'Please select the checkbox in the ReCaptcha image.');

// Get the class
$recaptchav2Path = $modx->getOption('recaptchav2.core_path', null, $modx->getOption('core_path') . 'components/recaptchav2/');
$recaptchav2Path .= 'model/recaptchav2/';
if (!file_exists($recaptchav2Path . 'autoload.php')) {
    $modx->log(modX::LOG_LEVEL_WARN, 'Cannot find required RecaptchaV2 autoload.php file.'); 
    return false;
}
require_once($recaptchav2Path . 'autoload.php');
$recaptchav2 = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\CurlPost());
if (!($recaptchav2 instanceof \ReCaptcha\ReCaptcha)) {
    $hook->addError('recaptchav2_error', $tech_err_msg);
    $modx->log(modX::LOG_LEVEL_WARN, 'Failed to load recaptchav2 class.'); 
    return false;
}

// The response from reCAPTCHA
$resp = null;
// The error code from reCAPTCHA, if any
$error = null;

// Was there a reCAPTCHA response?
if ($hook->getValue('g-recaptcha-response')) {
    $resp = $recaptchav2->verify($hook->getValue('g-recaptcha-response'), $_SERVER["REMOTE_ADDR"]);
}

// Hook pass/fail
if ($resp != null && $resp->isSuccess()) {
    return true;
} else {
    $hook->addError('recaptchav2_error', $recaptcha_err_msg);
    //DEBUG INFO: $modx->log(modX::LOG_LEVEL_ERROR, print_r($resp, true));
    return false;
}