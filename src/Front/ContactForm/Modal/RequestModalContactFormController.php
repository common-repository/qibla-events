<?php
/**
 * Request Modal for Contact Form Controller
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

namespace QiblaEvents\Front\ContactForm\Modal;

use QiblaEvents\Functions as F;
use QiblaEvents\Modal\ModalTemplate;
use QiblaEvents\Request\AbstractRequestController;
use QiblaEvents\Request\Response;

/**
 * Class RequestModalContactFormController
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestModalContactFormController extends AbstractRequestController
{
    /**
     * @inheritDoc
     */
    public function handle()
    {
        $self  = $this;
        $modal = new ModalTemplate(function () use ($self) {
            echo F\ksesPost($self->data['form']->getHtml());
        }, array(
            'class_container' => F\getScopeClass('modal', '', 'contact-form'),
            'context'         => 'script',
            'title'           => esc_html__('Contact the owner', 'qibla-events'),
            'subtitle'        => esc_html__('We\'ll reply you as soon as possible', 'qibla-events'),
        ));

        ob_start();
        $modal->tmpl($modal->getData());
        $markup = ob_get_clean();

        return new Response(200, '', array(
            'html'          => $markup,
            'openByDefault' => false,
        ));
    }
}
