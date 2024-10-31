<?php

namespace QiblaEvents\Admin\Settings;

use QiblaEvents\Functions as F;
use QiblaEvents\Admin\Panel\Navigation as AdminNavigation;
use QiblaEvents\Admin\Settings\Panel as AdminSettingsPanel;
use QiblaEvents\Admin\Notice\Notice;
use QiblaEvents\Form\Validate;
use QiblaEvents\Plugin;
use QiblaEvents\Request\Nonce;

/**
 * Controller
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Settings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Guido Scialfa <dev@guidoscialfa.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * Class Controller
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Controller
{
    /**
     * Namespace
     *
     * @since  1.0.0
     *
     * @var string The current namespace
     */
    private $namespace = 'QiblaEvents\\Admin\\Settings';

    /**
     * Page
     *
     * @since  1.0.0
     *
     * @var string The current page slug
     */
    private $page;

    /**
     * Sub Page
     *
     * @since  1.0.0
     *
     * @var string The current sub-page slug
     */
    private $subPage;

    /**
     * Main Instance
     *
     * @since  1.0.0
     *
     * @var mixed The current panel instance based on query string
     */
    private $mainInstance;

    /**
     * Default Instance Name
     *
     * @since  1.0.0
     *
     * @var string The class name for the default instance
     */
    private $defaultInstanceName;

    /**
     * The Panel
     *
     * @since  1.0.0
     *
     * @var Panel The panel to build
     */
    private $panel;

    /**
     * Build Class String
     *
     * @param string $shortClassName
     *
     * @return string The class name with namespace.
     */
    private function buildClassString($shortClassName = '')
    {
        // Generally is the same of the form name.
        $class = ucwords(preg_replace('/[^a-z0-9]/', ' ', $shortClassName));
        $class = $this->namespace . '\\' . str_replace(' ', '', $class);

        return $class;
    }

    /**
     * Set Main Instance
     *
     * Create the instance of the class that represent the main content of the panel.
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function setMainInstance()
    {
        // If no page has been provided, let fallback to the default instance.
        if (! $this->page) {
            $currPanel = $this->defaultInstanceName;
        } else {
            $currPanel = $this->subPage ? $this->subPage : $this->page;
        }

        // We need the class with namespace included.
        $currPanel = $this->buildClassString($currPanel);

        // We have not defined a rule when the current page is the main page under which the
        // panel pages are defined. So, if the page is provided but is the main parent page,
        // there will be no way to create the instance for that class because it's not exists.
        // So, for now we rebuild the class string based on the default instance name.
        if (! class_exists($currPanel)) {
            $currPanel = $this->buildClassString($this->defaultInstanceName);
        }

        // Build the instance.
        $this->mainInstance = new $currPanel;
    }

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param string $defaultInstanceName The default instance slug if no one can be retrieved.
     */
    public function __construct($defaultInstanceName)
    {
        // Retrieve the first sub-page element.
        $this->defaultInstanceName = $defaultInstanceName;
        // @codingStandardsIgnoreStart
        $this->page    = F\filterInput($_GET, 'item-slug', FILTER_SANITIZE_STRING);
        $this->subPage = F\filterInput($_GET, 'subitem', FILTER_SANITIZE_STRING);
        // @codingStandardsIgnoreEnd
    }

    /**
     * Get Current Page
     *
     * @since  1.0.0
     *
     * @return string The current page slug
     */
    public function getCurrentPage()
    {
        return $this->page;
    }

    /**
     * Get Sub-page
     *
     * @since  1.0.0
     *
     * @return string The current sub-page. Empty string if not exists.
     */
    public function getCurrentSubPage()
    {
        return $this->subPage;
    }

    /**
     * Get Main Instance
     *
     * Return the instance object of the class that represent the main content of the panel.
     *
     * @since  1.0.0
     *
     * @return mixed
     */
    public function getMainInstance()
    {
        return $this->mainInstance;
    }

    /**
     * Set Panel
     *
     * @since  1.0.0
     *
     * @param Panel $panel The panel to use with this controller.
     *
     * @return void
     */
    public function setPanel(Panel $panel)
    {
        $this->panel = $panel;
    }

    /**
     * Render the panel
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function render()
    {
        /**
         * Before Render Panel
         *
         * Fired before render the panel
         *
         * @since 1.0.0
         */
        do_action('qibla_settings_before_render_panel');

        // Render the panel.
        $this->panel->render($this->mainInstance);
    }

    /**
     * Process
     *
     * Process the Panel form to able to save the options.
     *
     * The importer does not return anything because we can't know by the update_option if the value
     * of an option nor a group of options are not updated because of an error or the value is the same.
     * Trust only in exception in this case.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function process()
    {
        // Verify nonce.
        $nonce = new Nonce('_settings_form', 'POST', true);
        $nonce->verify();

        // Check user capability.
        if (! current_user_can('edit_theme_options')) {
            wp_die('Cheatin&#8217; Uh?');
        }

        try {
            // Try to process the form only when the form has been submitted.
            // @codingStandardsIgnoreStart
            $action = F\filterInput($_POST, 'qibla_action', FILTER_SANITIZE_STRING);
            $reset  = F\filterInput($_POST, 'qibla_opt_reset', FILTER_SANITIZE_STRING);
            $export = F\filterInput($_POST, 'qibla_opt_export', FILTER_SANITIZE_STRING);
            // @codingStandardsIgnoreEnd
            $form = $this->getMainInstance();

            // Something has been submitted?
            if ((! $action || 'qibla_process_settings' !== $action) && ! $reset && ! $export) {
                return;
            }

            // Validate the request.
            $validator = new Validate();
            $response  = $validator->validate($form->getFields(), array('allow_empty' => true));

            // Imported Result.
            $importedMsg = '';

            // Which action to perform?
            switch ($form->getArg('name')) :
                // Import / Export.
                case 'import-export':
                    // @codingStandardsIgnoreLine
                    if (F\filterInput($_POST, 'qibla_opt_export', FILTER_SANITIZE_STRING)) :
                        // Export the data into a json file.
                        $exporterInstance = new Export();
                        $exporterInstance->export();
                    // @codingStandardsIgnoreLine
                    elseif (F\filterInput($_POST, 'qibla_opt_reset', FILTER_SANITIZE_STRING)) :
                        try {
                            // Try to Reset the option.
                            $filePath         = Plugin::getPluginDirPath('/assets/json/options.json');
                            $importerInstance = new Import();
                            $importerInstance->import(array(
                                'name'     => 'options',
                                'type'     => 'application/json',
                                'tmp_name' => $filePath,
                                'error'    => 0,
                                'size'     => filesize($filePath),
                            ));

                            $importedMsg = esc_html__('Options have been reset correctly.', 'qibla-events');
                        } catch (\Exception $e) {
                            $importedMsg = $e->getMessage();
                        }
                    elseif (isset($_FILES['qibla_opt_import'])) :
                        // Retrieve the data for the file.
                        $data = filter_var_array($_FILES['qibla_opt_import'], array(
                            'name'     => FILTER_SANITIZE_STRING,
                            'type'     => FILTER_SANITIZE_STRING,
                            'tmp_name' => FILTER_SANITIZE_STRING,
                            'error'    => FILTER_SANITIZE_NUMBER_INT,
                            'size'     => FILTER_SANITIZE_NUMBER_INT,
                        ));

                        // No file was uploaded or the data is tainded.
                        if (! $data || null === $data['tmp_name']) {
                            break;
                        }

                        try {
                            $importerInstance = new Import();
                            $importerInstance->import($data);
                            $importedMsg = esc_html__(
                                'Congratulation. Options have been imported correctly.',
                                'qibla-events'
                            );
                        } catch (\Exception $e) {
                            $importedMsg = $e->getMessage();
                        }
                    endif;
                    break;

                // Default to submit the form.
                default:
                    if (! $response['invalid'] && $response['valid']) {
                        // The name of the options used by the Store class is the same of the attribute of the form.
                        $store = new Store($form->getArg('name'), 'qibla_opt-');
                        $store->store($response['valid']);

                        $importedMsg = esc_html__(
                            'Congratulation. Settings have been saved correctly.',
                            'qibla-events'
                        );
                    }
                    break;
            endswitch;

            // Set the main instance for the current settings form.
            // This is because after the submit process some fields value may have changed and we need to retrieve the
            // fresh data from the db.
            $this->setMainInstance();

        } catch (\Exception $e) {
            $importedMsg = $e->getMessage();
        }//end try

        if (! wp_doing_ajax()) {
            if ($importedMsg) {
                // Don't print message here. Headers all ready sent.
                add_action('admin_notices', function () use ($importedMsg) {
                    $noticeInstance = new Notice($importedMsg, 'success');
                    $noticeInstance->notice();
                });
            }
        }
    }

    /**
     * Initialize Settings Controller
     *
     * @since  1.0.0
     *
     * @return void
     */
    public static function initializeControllerFilterCallback()
    {
        // @codingStandardsIgnoreLine
        $allowedPage = F\filterInput($_GET, 'page', FILTER_SANITIZE_STRING);

        if (in_array($allowedPage, array('qibla-events-options'), true)) :
            // The settings admin menu list.
            $settingsMenuList = include Plugin::getPluginDirPath('/inc/settingsMenuList.php');
            // Settings Panel.
            $controllerPanel = new Controller($settingsMenuList[0]['menu_slug']);

            $controllerPanel->setMainInstance();
            $controllerPanel->setPanel(new AdminSettingsPanel(
                new AdminNavigation(
                    admin_url('admin.php?page=qibla-events-options'),
                    $settingsMenuList,
                    $controllerPanel->getCurrentPage()
                )
            ));

            // Show the theme options form within the right admin page.
            add_action('qibla_events_admin_settings_page_content', array($controllerPanel, 'render'));
            add_action('admin_init', array($controllerPanel, 'process'));
        endif;
    }
}
