<?php

/**
 * media_manager Addon
 *
 * @author markus.staab[at]redaxo[dot]de Markus Staab
 * @author jan.kristinus[at]yakmara[dot]de Jan Kristinus
 *
 * @package redaxo5
 *
 * @var rex_addon $this
 */

// rex_request();

$content = '';

$func = rex_request('func', 'string');
$jpg_quality = rex_request('jpg_quality', 'int');

if ($func == 'update') {
    if ($jpg_quality > 100) {
        $jpg_quality = 100;
    } elseif ($jpg_quality < 0) {
        $jpg_quality = 0;
    }

    $this->setConfig('jpg_quality', $jpg_quality);
    $content = rex_view::info($this->i18n('config_saved'));

}

$content .= '
<div class="rex-form">

    <h2>' . $this->i18n('subpage_config') . '</h2>

    <form action="' . rex_url::currentBackendPage() . '" method="post">
    <fieldset class="rex-form-col-1">
            <div class="rex-form-wrapper">
            <input type="hidden" name="func" value="update" />

            <div class="rex-form-row rex-form-element-v2">
                <p class="rex-form-text">
                    <label for="jpg_quality">' . $this->i18n('jpg_quality') . ' [0-100]</label>
                    <input class="rex-form-text" type="text" id="jpg_quality" name="jpg_quality" value="' . htmlspecialchars($this->getConfig('jpg_quality')) . '" />
                </p>
            </div>

            <div class="rex-form-row rex-form-element-v2">
                <p class="rex-form-submit">
                    <input type="submit" class="rex-form-submit" name="sendit" value="' . rex_i18n::msg('update') . '" />
                </p>
            </div>
        </div>
    </fieldset>
    </form>
</div>';

echo rex_view::contentBlock($content);
