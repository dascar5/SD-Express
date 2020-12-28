<?php

namespace pluginVersionCheck\pluginVersionCheck;

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

use VisualComposer\Framework\Container;
use VisualComposer\Framework\Illuminate\Support\Module;
use VisualComposer\Helpers\Notice;
use VisualComposer\Helpers\Options;
use VisualComposer\Helpers\Traits\WpFiltersActions;

/**
 * Class SettingsController
 * @package pluginVersionCheck\pluginVersionCheck
 */
class SettingsController extends Container implements Module
{
    use WpFiltersActions;

    /**
     * @var string
     */
    protected $requiredVersion = '24.0';

    /**
     * SettingsController constructor.
     */
    public function __construct()
    {
        $this->wpAddAction('current_screen', 'pluginVersionWarning');
    }

    /**
     * If plugin is outdated, show an error message for that
     *
     * @param \VisualComposer\Helpers\Notice $noticeHelper
     * @param \VisualComposer\Helpers\Options $optionsHelper
     */
    protected function pluginVersionWarning(Notice $noticeHelper, Options $optionsHelper)
    {
        // if current installed version is less than required then check wp.org api for new version
        if (version_compare(VCV_VERSION, $this->requiredVersion, '<')) {
            if ((int)$optionsHelper->getTransient('addonPluginVersionCheck') < time()) {
                $response = wp_remote_get(
                    'https://api.wordpress.org/plugins/info/1.0/visualcomposer.json',
                    ['timeout' => 30]
                );
                if (!vcIsBadResponse($response)) {
                    $optionsHelper->setTransient(
                        'addonPluginVersionCheck',
                        time() + 4 * HOUR_IN_SECONDS,
                        4 * HOUR_IN_SECONDS
                    );
                    $jsonData = json_decode($response['body'], true);
                    if (is_array($jsonData)) {
                        if (version_compare($jsonData['version'], $this->requiredVersion, '>=')) {
                            // new version is available
                            $noticeHelper->addNotice(
                                'pluginVersionCheck',
                                sprintf(
                                    __(
                                        'You have an outdated Visual Composer Website Builder plugin version which does not support the new activation flow. Please update your plugin. <a href="%s">Update</a>',
                                        'visualcomposer'
                                    ),
                                    admin_url('plugins.php')
                                ),
                                'warning',
                                false
                            );
                        }
                    }
                }
            }
        } else {
            $noticeHelper->removeNotice('pluginVersionCheck');
        }
    }
}
