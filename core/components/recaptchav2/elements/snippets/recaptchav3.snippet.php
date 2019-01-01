<?php
/**
 * recaptchav3 hook for use with MODX form processors
 *
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
$site_key = $modx->getOption('recaptchav3.site_key', null, '');
$secret = $modx->getOption('recaptchav3.secret_key', null, '');
// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$lang = $modx->getOption('cultureKey', null, 'en');
// https://developers.google.com/recaptcha/docs/v3 "Actions"
$action_key = $modx->getOption('recaptchav3.action_key', null, 'recaptcha-action', true);
$token_key = $modx->getOption('recaptchav3.token_key', null, 'recaptcha-token', true);

// Options
if ($hook->formit) {
    $properties = array_merge(array(), $hook->formit->config);
}
$threshold = floatval($modx->getOption('recaptchaThreshold', $properties, 0.7, true));
$ip = $modx->getOption('HTTP_CF_CONNECTING_IP', $_SERVER, $_SERVER['REMOTE_ADDR'], true);

// make sure the modLexicon class is loaded by instantiating
$modx->getService('lexicon','modLexicon');
// load lexicon
$modx->lexicon->load('recaptchav2:default');
// get the message from default.inc.php from the correct lang
$tech_err_msg = $modx->lexicon('recaptchav2.technical_error_message');
$recaptcha_err_msg = $modx->lexicon('recaptchav2.recaptcha_error_message');

// Get the class
$recaptchaPath = $modx->getOption('recaptchav2.core_path', null, $modx->getOption('core_path') . 'components/recaptchav2/');
$recaptchaPath .= 'model/recaptchav2/';
if (!file_exists($recaptchaPath . 'autoload.php')) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Cannot find required Recaptcha autoload.php file.');
    return false;
}
require_once($recaptchaPath . 'autoload.php');
$recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\CurlPost());
if (!($recaptcha instanceof \ReCaptcha\ReCaptcha)) {
    $hook->addError('recaptchav3_error', $tech_err_msg);
    $modx->log(modX::LOG_LEVEL_ERROR, 'Failed to load Recaptcha class.');
    return false;
}

// The response from reCAPTCHA
$resp = null;
// The error code from reCAPTCHA, if any
$error = null;
// Check if being used as hook
if (isset($hook)){
// Was there a reCAPTCHA response?
    if ($hook->getValue($token_key)) {
        $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME']) // MODX-y way?
                  ->setExpectedAction($hook->getValue($action_key))
                  ->setScoreThreshold($threshold)
                  ->verify($hook->getValue($token_key), $ip);
    }

// Hook pass/fail
    if ($resp != null && $resp->isSuccess()) {
        return true;
    } else {
        $hook->addError('recaptchav3_error', $recaptcha_err_msg);
        $modx->log(modX::LOG_LEVEL_DEBUG, print_r($resp, true));
        return false;
    }
}

// Checks failed
return false;
