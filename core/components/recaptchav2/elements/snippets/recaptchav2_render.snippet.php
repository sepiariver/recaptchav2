<?php
/**
 * Renders ReCaptcha V2 form
 *
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

$assets_url = $modx->getOption('recaptchav2.assets_url', null, $modx->getOption('assets_url') . 'components/recaptchav2/');
$renderTo = $modx->getOption('renderTo', $scriptProperties, 'recaptcha');
// Register API keys at https://www.google.com/recaptcha/admin
$site_key = $modx->getOption('recaptchav2.site_key', null, '');
// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$lang = $modx->getOption('cultureKey', null, 'en');
$callback = $modx->getOption('callback', null, 'recaptchav2Callback');

$recaptcha_html = $modx->getChunk('recaptchav2_html', array(
    'id' => $renderTo,
    'site_key' => $site_key,
    'lang' => $lang,
    ));

if ($hook) { 
    $hook->setValue('recaptchav2_html', $recaptcha_html); // This won't re-render on page reload there's validation errors
    return true;
} else { // This works at least
    $modx->regClientScript($assets_url.'js/'.'recaptchav2.callback.js');
    $modx->regClientScript('https://www.google.com/recaptcha/api.js?hl='.$lang.'&onload='.$callback.'&render=explicit');
    return $recaptcha_html;
}
