/**
 * Custom Marker
 *
 * @since      1.0.0
 * @author     Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, Alfio Piccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Alfio Piccione <alfio.piccione@gmail.com>
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

'use strict';

function CustomMarker(latLng, options, template)
{
    /**
     * Set Latitude Longitude
     *
     * @since 1.0.0
     *
     * @var {Object} Lat
     */
    this.latLng = latLng;

    /**
     * Set Marker Options
     *
     * @since 1.0.0
     *
     * @var {Object} A list of options for this marker
     */
    this.options = options;

    /**
     * Set The template for the marker
     *
     * @since 1.0.0
     *
     * @var {Function} the underscore/loadash template function
     */
    this.template = template;
}

// Initialize Prototype.
CustomMarker.prototype = new google.maps.OverlayView();

/**
 * On Add
 *
 * This method is called once after setMap() is called with a valid map.
 * At this point, panes and projection will have been initialized.
 *
 * @since 1.0.0
 *
 * @return void
 */
CustomMarker.prototype.onAdd = function ()
{
    var panes = this.getPanes(),
        point = this.getProjection().fromLatLngToDivPixel(this.latLng);

    if (!point || this.div) {
        return;
    }

    this.div = document.createElement('div');

    // Set the Properly style for marker container.
    this.div.classList.add('dlmap-marker');
    this.div.style.position        = 'absolute';
    this.div.style.webkitTransform = 'translateZ(0px)';
    this.div.style.cursor          = 'pointer';
    this.div.style.width           = '50px';
    this.div.style.height          = '60px';

    // Add an identifier for this marker related with the listing element.
    // In this way we can perform something on listings element events.
    // For more info about the relation see the listings.js backbone view.
    this.div.classList.add('data-marker-slug-' + this.options.dataMarkerSlug);

    // Insert the marker markup within the container.
    this.div.insertAdjacentHTML('beforeend', this.template(this.options.templateData));
    panes.overlayMouseTarget.appendChild(this.div);

    // Add the event listener to the newly marker.
    this.addListeners();
};

/**
 * Draw
 *
 * To draw or update the overlay.
 * This method is called after onAdd() and when the position from projection.fromLatLngToPixel()
 * would return a new value for a given LatLng. This can happen on change of zoom, center, or map type.
 * It is not necessarily called on drag or resize.
 *
 * @since 1.0.0
 *
 * @return void
 */
CustomMarker.prototype.draw = function ()
{
    var point = this.getProjection().fromLatLngToDivPixel(this.latLng);

    if (!point) {
        return;
    }

    // The position of the marker must be calculated every time there is an update.
    // Remove the half of width and the total height of the marker to position it.
    this.div.style.left = (point.x - 25) + 'px';
    this.div.style.top  = (point.y - 60) + 'px';
};

/**
 * On Click Listener
 *
 * @since 1.0.0
 */
CustomMarker.prototype.addListeners = function ()
{
    // Add Click event to marker.
    google.maps.event.addDomListener(this.div, 'click', function (e)
    {
        google.maps.event.trigger(this, 'click', e);
    }.bind(this));

    // Add Double Click event to marker.
    google.maps.event.addDomListener(this.div, 'dblclick', function (e)
    {
        google.maps.event.trigger(this, 'dblclick', e);
    }.bind(this));
};

/**
 * Set Position
 *
 * @since 1.0.0
 */
CustomMarker.prototype.setPosition = function (latLng)
{
    this.latLng = latLng;
};

/**
 * Get Position
 *
 * @since 1.0.0
 */
CustomMarker.prototype.getPosition = function ()
{
    return this.latLng;
};

/**
 * Set Icon
 *
 * @since 1.0.0
 *
 * @param {Function} template The underscore template function
 *
 * @return void
 */
CustomMarker.prototype.setIcon = function (template)
{
    this.template = template;
};

/**
 * Get Icon
 *
 * @since 1.0.0
 *
 * @return {String} The markup of the icon template.
 */
CustomMarker.prototype.getIcon = function ()
{
    return this.template(this.options.templateData);
};

/**
 * Remove
 *
 * @since 1.0.0
 *
 * @return void
 */
CustomMarker.prototype.remove = function ()
{
    if (this.div) {
        this.div.parentNode.removeChild(this.div);
        this.div = null;
    }
};

/**
 * Get Draggable
 *
 * @since 1.0.0
 *
 * @returns {boolean} Always false
 */
CustomMarker.prototype.getDraggable = function ()
{
    return false;
};

/**
 * Get Visible
 *
 * @since 1.0.0
 *
 * @returns {boolean} Always true
 */
CustomMarker.prototype.getVisible = function ()
{
    return true;
};
