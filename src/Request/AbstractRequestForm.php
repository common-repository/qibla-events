<?php
/**
 * AbstractFormRequest
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   GNU General Public License, version 2
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

namespace QiblaEvents\Request;

use QiblaEvents\Functions as F;
use QiblaEvents\Form\Interfaces\Forms;
use QiblaEvents\Shortcode\Alert;

/**
 * Class AbstractFormRequest
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Request
 */
abstract class AbstractRequestForm
{
    /**
     * Form
     *
     * @since  1.0.0
     *
     * @var Forms The form to process
     */
    protected $form;

    /**
     * Action Name
     *
     * @since  1.0.0
     *
     * @var string The name of the Action to perform
     */
    protected $actionName;

    /**
     * Action Key
     *
     * @since  1.0.0
     *
     * @var string The action key value
     */
    protected static $actionKey = 'dlaction';

    /**
     * Set Alert Response
     *
     * @since  1.0.0
     *
     * @param string   $actionKey The action to hook.
     * @param Response $response  A Response instance.
     *
     * @return void
     */
    protected static function setAlertResponse($actionKey, Response $response = null)
    {
        // Set the type.
        $type = $response->isValidStatus() ? 'success' : 'error';

        // This is the function.
        $func = function () use ($type, $response) {
            $alert = new Alert();
            // @codingStandardsIgnoreLine
            echo F\ksesPost($alert->callback(array('type' => $type), $response->getMessage()));
        };

        // Remove the action every time the method is called to avoid duplicated.
        remove_action(
            $actionKey,
            _wp_filter_build_unique_id($actionKey, $func, 20),
            20
        );

        // Set the action.
        add_action($actionKey, $func, 20);
    }

    /**
     * AbstractRequestForm constructor
     *
     * @since 1.0.0
     *
     * @param Forms  $form       The form instance to use with the request.
     * @param string $actionName The name of the action to perform.
     */
    public function __construct(Forms &$form, $actionName)
    {
        $this->form       = $form;
        $this->actionName = $actionName;
    }

    /**
     * @inheritDoc
     */
    public function isValidRequest()
    {
        // @codingStandardsIgnoreLine
        $action = \QiblaEvents\Functions\filterInput($_POST, static::$actionKey, FILTER_SANITIZE_STRING);
        $isValid = $this->actionName === $action;

        // Validate Nonce. Valid or die.
        $nonce = new Nonce($this->form->getArg('name'), $this->form->getArg('method'), false);

        return $isValid && $nonce->verify();
    }
}
