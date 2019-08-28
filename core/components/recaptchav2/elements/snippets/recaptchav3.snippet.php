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

// Require hook object
if (!$hook) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'RecaptchaV3 requires hook object.');
    return;
}

// Register API keys at https://www.google.com/recaptcha/admin
$props['site_key'] = $modx->getOption('recaptchav3.site_key', null, '');
$props['secret_key'] = $modx->getOption('recaptchav3.secret_key', null, '');
// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$props['lang'] = $modx->getOption('cultureKey', null, 'en');
// https://developers.google.com/recaptcha/docs/v3 "Actions"
$props['action_key'] = $modx->getOption('recaptchav3.action_key', null, 'recaptcha-action', true);
$props['token_key'] = $modx->getOption('recaptchav3.token_key', null, 'recaptcha-token', true);

// Options
$hookConfig = [];
if ($hook->formit) {
    $hookConfig = $hook->formit->config;
} elseif ($hook->login) {
    $hookConfig = $hook->login->controller->config;
}
foreach ($hookConfig as $k => $v) {
    if (strpos($k, 'recaptchav3.') === 0) {
        $k = substr($k, 12);
        $props[$k] = $v;
    }
}

// Defaults
$props['threshold'] = floatval($modx->getOption('threshold', $props, 0.5, true));
$props['display_resp_errors'] = $modx->getOption('display_resp_errors', $props, true);
$props['ip'] = $modx->getOption('HTTP_CF_CONNECTING_IP', $_SERVER, $_SERVER['REMOTE_ADDR'], true);

// make sure the modLexicon class is loaded by instantiating
$modx->getService('lexicon','modLexicon');
// load lexicon
$modx->lexicon->load('recaptchav2:default');
// get the message from default.inc.php from the correct lang
$tech_err_msg = $modx->lexicon('recaptchav2.technical_error_message');
$recaptcha_err_msg = $modx->lexicon('recaptchav2.recaptchav3_error_message');

// Get the class
$recaptchaPath = $modx->getOption('recaptchav2.core_path', null, $modx->getOption('core_path') . 'components/recaptchav2/');
$recaptchaPath .= 'model/recaptchav2/';
if (!file_exists($recaptchaPath . 'autoload.php')) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Cannot find required Recaptcha autoload.php file.');
    return false;
}
require_once($recaptchaPath . 'autoload.php');
try {
    $recaptcha = new \ReCaptcha\ReCaptcha($props['secret_key'], new \ReCaptcha\RequestMethod\CurlPost());
} catch (Exception $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Failed to load Recaptcha class.');
    return false;
}

if (!($recaptcha instanceof \ReCaptcha\ReCaptcha)) {
    $hook->addError('recaptchav3_error', $tech_err_msg);
    $modx->log(modX::LOG_LEVEL_ERROR, 'Failed to load Recaptcha class.');
    return false;
}

// The response from reCAPTCHA
$resp = null;
// The error code from reCAPTCHA, if any
$error = null;

// Was there a reCAPTCHA response?
if ($hook->getValue($props['token_key'])) {
    $resp = $recaptcha->setExpectedHostname(parse_url($modx->getOption('site_url'), PHP_URL_HOST))
              ->setExpectedAction($hook->getValue($props['action_key']))
              ->setScoreThreshold($props['threshold'])
              ->verify($hook->getValue($props['token_key']), $props['ip']);

}

// Hook pass/fail
if ($resp != null && $resp->isSuccess()) {
    return true;
} else {
    $msg = '';
    if ($resp != null && $props['display_resp_errors']) {
        foreach ($resp->getErrorCodes() as $error) {
            $msg .= $error . "\n";
        }
    }
    if (empty($msg)) $msg = $recaptcha_err_msg;
    $hook->addError('recaptchav3_error', $msg);
    $modx->log(modX::LOG_LEVEL_DEBUG, print_r($resp, true));
    return false;
}


// Checks failed
return false;
