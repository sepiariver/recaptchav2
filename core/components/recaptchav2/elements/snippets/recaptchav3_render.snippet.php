<?php
/**
 * Renders ReCaptcha V3 form
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
// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$lang = $modx->getOption('cultureKey', null, 'en', true);
// https://developers.google.com/recaptcha/docs/v3 "Actions"
$action_key = $modx->getOption('action_key', $scriptProperties, $modx->getOption('recaptchav3.action_key', null, 'recaptcha-action', true), true);

$token_key = $modx->getOption('token_key', $scriptProperties, $modx->getOption('recaptchav3.token_key', null, 'recaptcha-token', true), true);

// new 'recaptchav3_html' Chunk
$tpl = $modx->getOption('tpl', $scriptProperties, 'recaptchav3_html', true);
$form_id = $modx->getOption('form_id', $scriptProperties, $modx->resource->get('uri'));

$recaptcha_html = $modx->getChunk($tpl, [
    'site_key' => $site_key,
    'lang' => $lang,
    'form_id' => preg_replace('/[^A-Za-z\/_]/', '', $form_id),
    'action_key' => $action_key,
    'token_key' => $token_key,
]);

return $recaptcha_html;