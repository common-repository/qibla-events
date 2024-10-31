/**
 * Custom Marker Clusterer
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

/**
 * Creates a MarkerClusterer object with the options specified in {@link MarkerClustererOptions}.
 * @constructor
 * @extends google.maps.OverlayView
 * @param {google.maps.Map} map The Google map to attach to.
 * @param {Array.<google.maps.Marker>} [opt_markers] The markers to be added to the cluster.
 * @param {MarkerClustererOptions} [opt_options] The optional parameters.
 */
function CustomMarkerClusterer(map, opt_markers, opt_options, template)
{
    MarkerClusterer.call(this, map, opt_markers, opt_options);

    this.template = template;
}

CustomMarkerClusterer.prototype   = MarkerClusterer.prototype;
CustomMarkerClusterer.constructor = CustomMarkerClusterer;

/**
 * Adds a marker to a cluster, or creates a new cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 */
CustomMarkerClusterer.prototype.addToClosestCluster_ = function (marker)
{
    var i, d, cluster, center;
    var distance       = 40000; // Some large number
    var clusterToAddTo = null;
    for (i = 0; i < this.clusters_.length; i++) {
        cluster = this.clusters_[i];
        center  = cluster.getCenter();
        if (center) {
            d = this.distanceBetweenPoints_(center, marker.getPosition());
            if (d < distance) {
                distance       = d;
                clusterToAddTo = cluster;
            }
        }
    }

    if (clusterToAddTo && clusterToAddTo.isMarkerInClusterBounds(marker)) {
        clusterToAddTo.addMarker(marker);
    } else {
        cluster = new CustomCluster(this, this.template);
        cluster.addMarker(marker);
        this.clusters_.push(cluster);
    }
};

/**
 * Creates a single cluster that manages a group of proximate markers.
 *  Used internally, do not call this constructor directly.
 * @constructor
 * @param {MarkerClusterer} mc The <code>MarkerClusterer</code> object with which this
 *  cluster is associated.
 */
function CustomCluster(mc, template)
{
    Cluster.call(this, mc);
    this.clusterIcon_ = new CustomClusterIcon(this, mc.getStyles(), template);
}

CustomCluster.prototype   = Cluster.prototype;
CustomCluster.constructor = CustomCluster;

/**
 * A cluster icon.
 *
 * @constructor
 * @extends google.maps.OverlayView
 * @param {Cluster} cluster The cluster with which the icon is to be associated.
 * @param {Array} [styles] An array of {@link ClusterIconStyle} defining the cluster icons to use for various cluster sizes.
 * @param {Function} template The template to use for the clusterer icon.
 * @private
 */
function CustomClusterIcon(cluster, styles, template)
{
    ClusterIcon.call(this, cluster, styles);
    this.template = template;
}

CustomClusterIcon.prototype   = ClusterIcon.prototype;
CustomClusterIcon.constructor = CustomClusterIcon;

/**
 * Positions and shows the icon.
 */
CustomClusterIcon.prototype.show = function ()
{
    if (this.div_) {
        // Set the Properly style for marker container.
        this.div_.style.position        = 'absolute';
        this.div_.style.webkitTransform = 'translateZ(0px) translateY(-30px)';
        this.div_.style.cursor          = 'pointer';
        this.div_.style.width           = '50px';
        this.div_.style.height          = '60px';

        this.div_.insertAdjacentHTML('beforeend', this.template({
            count: this.sums_.text
        }));

        this.div_.style.display = '';
    }

    this.visible_ = true;
};
