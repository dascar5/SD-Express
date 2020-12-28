<?php

namespace pluginVersionCheck\pluginVersionCheck;

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

use VisualComposer\Framework\Container;
use VisualComposer\Framework\Illuminate\Support\Module;
use VisualComposer\Helpers\Traits\WpFiltersActions;
use VisualComposer\Helpers\Hub\Addons;

class EnqueueController extends Container implements Module
{
    use WpFiltersActions;

    protected $isHFSorBlankTemplateType = false;

    public function __construct()
    {
        $this->wpAddAction('wp_enqueue_scripts', 'enqueueRowBcAssets');
        $this->wpAddFilter('template_include', 'checkForTemplate', 1000);
    }

    protected function checkForTemplate($template)
    {
        if (
            strpos($template, 'themeEditor/themeEditor') !== false
            || strpos($template, 'visualcomposer/resources/views/editor/templates/blank-template.php') !== false
        ) {
            // some of HFS used
            $this->isHFSorBlankTemplateType = true;
        }

        return $template;
    }

    protected function enqueueRowBcAssets(Addons $hubAddonsHelper)
    {
        $shouldEnqueue = $this->isHFSorBlankTemplateType;
        if (is_singular()) {
            $post = get_post();
            //@codingStandardsIgnoreLine
            $modifiedDate = mysql2date('U', $post->post_modified_gmt, false);
            // 30.1x + hotfix release date
            $releaseDate = mktime(10, 0, 0, 9, 17, 2020);
            if ($modifiedDate > $releaseDate) {
                // Post updated, so row migrated
                $shouldEnqueue = false;
            }
        }

        if ($shouldEnqueue) {
            $addonBundleUrl = $hubAddonsHelper->getAddonUrl('pluginVersionCheck/public/dist/element.bundle.js');
            wp_enqueue_script(
                'vcv:addon:pvc:rowBc30x',
                $addonBundleUrl,
                ['vcv:assets:front:script'],
                VCV_VERSION,
                true
            );
        }
    }
}
