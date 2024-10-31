<?php
namespace QiblaEvents\IconsSet;

/**
 * LineAwesome
 *
 * @since      1.0.0
 * @package    QiblaEvents\IconsSet
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
 * Class Lineawesome
 *
 * @link    https://icons8.com/articles/line-awesome/
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Lineawesome extends AbstractIconsSet
{
    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->version = '1.0.0';
        $this->prefix  = 'la';
        $this->list    = array(
            'la-500px'                  => 'f100',
            'la-adjust'                 => 'f101',
            'la-adn'                    => 'f102',
            'la-align-center'           => 'f103',
            'la-align-justify'          => 'f104',
            'la-align-left'             => 'f105',
            'la-align-right'            => 'f106',
            'la-amazon'                 => 'f107',
            'la-ambulance'              => 'f108',
            'la-anchor'                 => 'f109',
            'la-android'                => 'f10a',
            'la-angellist'              => 'f10b',
            'la-angle-double-down'      => 'f10c',
            'la-angle-double-left'      => 'f10d',
            'la-angle-double-right'     => 'f10e',
            'la-angle-double-up'        => 'f10f',
            'la-angle-down'             => 'f110',
            'la-angle-left'             => 'f111',
            'la-angle-right'            => 'f112',
            'la-angle-up'               => 'f113',
            'la-apple'                  => 'f114',
            'la-archive'                => 'f115',
            'la-area-chart'             => 'f116',
            'la-arrow-circle-down'      => 'f117',
            'la-arrow-circle-left'      => 'f118',
            'la-arrow-circle-o-down'    => 'f119',
            'la-arrow-circle-o-left'    => 'f11a',
            'la-arrow-circle-o-right'   => 'f11b',
            'la-arrow-circle-o-up'      => 'f11c',
            'la-arrow-circle-right'     => 'f11d',
            'la-arrow-circle-up'        => 'f11e',
            'la-arrow-down'             => 'f11f',
            'la-arrow-left'             => 'f120',
            'la-arrow-right'            => 'f121',
            'la-arrow-up'               => 'f122',
            'la-arrows'                 => 'f123',
            'la-arrows-alt'             => 'f124',
            'la-arrows-h'               => 'f125',
            'la-arrows-v'               => 'f126',
            'la-asterisk'               => 'f127',
            'la-at'                     => 'f128',
            'la-automobile'             => 'f129',
            'la-backward'               => 'f12a',
            'la-balance-scale'          => 'f12b',
            'la-ban'                    => 'f12c',
            'la-bank'                   => 'f12d',
            'la-bar-chart'              => 'f12e',
            'la-bar-chart-o'            => 'f12f',
            'la-barcode'                => 'f130',
            'la-bars'                   => 'f131',
            'la-battery-0'              => 'f132',
            'la-battery-1'              => 'f133',
            'la-battery-2'              => 'f134',
            'la-battery-3'              => 'f135',
            'la-battery-4'              => 'f136',
            'la-battery-empty'          => 'f137',
            'la-battery-full'           => 'f138',
            'la-battery-half'           => 'f139',
            'la-battery-quarter'        => 'f13a',
            'la-battery-three-quarters' => 'f13b',
            'la-bed'                    => 'f13c',
            'la-beer'                   => 'f13d',
            'la-behance'                => 'f13e',
            'la-behance-square'         => 'f13f',
            'la-bell'                   => 'f140',
            'la-bell-o'                 => 'f141',
            'la-bell-slash'             => 'f142',
            'la-bell-slash-o'           => 'f143',
            'la-bicycle'                => 'f144',
            'la-binoculars'             => 'f145',
            'la-birthday-cake'          => 'f146',
            'la-bitbucket'              => 'f147',
            'la-bitbucket-square'       => 'f148',
            'la-bitcoin'                => 'f149',
            'la-black-tie'              => 'f14a',
            'la-bold'                   => 'f14b',
            'la-bolt'                   => 'f14c',
            'la-bomb'                   => 'f14d',
            'la-book'                   => 'f14e',
            'la-bookmark'               => 'f14f',
            'la-bookmark-o'             => 'f150',
            'la-briefcase'              => 'f151',
            'la-btc'                    => 'f152',
            'la-bug'                    => 'f153',
            'la-building'               => 'f154',
            'la-building-o'             => 'f155',
            'la-bullhorn'               => 'f156',
            'la-bullseye'               => 'f157',
            'la-bus'                    => 'f158',
            'la-buysellads'             => 'f159',
            'la-cab'                    => 'f15a',
            'la-calculator'             => 'f15b',
            'la-calendar'               => 'f15c',
            'la-calendar-check-o'       => 'f15d',
            'la-calendar-minus-o'       => 'f15e',
            'la-calendar-o'             => 'f15f',
            'la-calendar-plus-o'        => 'f160',
            'la-calendar-times-o'       => 'f161',
            'la-camera'                 => 'f162',
            'la-camera-retro'           => 'f163',
            'la-car'                    => 'f164',
            'la-caret-down'             => 'f165',
            'la-caret-left'             => 'f166',
            'la-caret-right'            => 'f167',
            'la-caret-square-o-down'    => 'f168',
            'la-caret-square-o-left'    => 'f169',
            'la-caret-square-o-right'   => 'f16a',
            'la-caret-square-o-up'      => 'f16b',
            'la-caret-up'               => 'f16c',
            'la-cart-arrow-down'        => 'f16d',
            'la-cart-plus'              => 'f16e',
            'la-cc'                     => 'f16f',
            'la-cc-amex'                => 'f170',
            'la-cc-diners-club'         => 'f171',
            'la-cc-discover'            => 'f172',
            'la-cc-jcb'                 => 'f173',
            'la-cc-mastercard'          => 'f174',
            'la-cc-paypal'              => 'f175',
            'la-cc-stripe'              => 'f176',
            'la-cc-visa'                => 'f177',
            'la-certificate'            => 'f178',
            'la-chain'                  => 'f179',
            'la-chain-broken'           => 'f17a',
            'la-check'                  => 'f17b',
            'la-check-circle'           => 'f17c',
            'la-check-circle-o'         => 'f17d',
            'la-check-square'           => 'f17e',
            'la-check-square-o'         => 'f17f',
            'la-chevron-circle-down'    => 'f180',
            'la-chevron-circle-left'    => 'f181',
            'la-chevron-circle-right'   => 'f182',
            'la-chevron-circle-up'      => 'f183',
            'la-chevron-down'           => 'f184',
            'la-chevron-left'           => 'f185',
            'la-chevron-right'          => 'f186',
            'la-chevron-up'             => 'f187',
            'la-child'                  => 'f188',
            'la-chrome'                 => 'f189',
            'la-circle'                 => 'f18a',
            'la-circle-o'               => 'f18b',
            'la-circle-o-notch'         => 'f18c',
            'la-circle-thin'            => 'f18d',
            'la-clipboard'              => 'f18e',
            'la-clock-o'                => 'f18f',
            'la-clone'                  => 'f190',
            'la-close'                  => 'f191',
            'la-cloud'                  => 'f192',
            'la-cloud-download'         => 'f193',
            'la-cloud-upload'           => 'f194',
            'la-cny'                    => 'f195',
            'la-code'                   => 'f196',
            'la-code-fork'              => 'f197',
            'la-codepen'                => 'f198',
            'la-coffee'                 => 'f199',
            'la-cog'                    => 'f19a',
            'la-cogs'                   => 'f19b',
            'la-columns'                => 'f19c',
            'la-comment'                => 'f19d',
            'la-comment-o'              => 'f19e',
            'la-commenting'             => 'f19f',
            'la-commenting-o'           => 'f1a0',
            'la-comments'               => 'f1a1',
            'la-comments-o'             => 'f1a2',
            'la-compass'                => 'f1a3',
            'la-compress'               => 'f1a4',
            'la-connectdevelop'         => 'f1a5',
            'la-contao'                 => 'f1a6',
            'la-copy'                   => 'f1a7',
            'la-copyright'              => 'f1a8',
            'la-creative-commons'       => 'f1a9',
            'la-credit-card'            => 'f1aa',
            'la-crop'                   => 'f1ab',
            'la-crosshairs'             => 'f1ac',
            'la-css3'                   => 'f1ad',
            'la-cube'                   => 'f1ae',
            'la-cubes'                  => 'f1af',
            'la-cut'                    => 'f1b0',
            'la-cutlery'                => 'f1b1',
            'la-dashboard'              => 'f1b2',
            'la-dashcube'               => 'f1b3',
            'la-database'               => 'f1b4',
            'la-dedent'                 => 'f1b5',
            'la-delicious'              => 'f1b6',
            'la-desktop'                => 'f1b7',
            'la-deviantart'             => 'f1b8',
            'la-diamond'                => 'f1b9',
            'la-digg'                   => 'f1ba',
            'la-dollar'                 => 'f1bb',
            'la-dot-circle-o'           => 'f1bc',
            'la-download'               => 'f1bd',
            'la-dribbble'               => 'f1be',
            'la-dropbox'                => 'f1bf',
            'la-drupal'                 => 'f1c0',
            'la-edit'                   => 'f1c1',
            'la-eject'                  => 'f1c2',
            'la-ellipsis-h'             => 'f1c3',
            'la-ellipsis-v'             => 'f1c4',
            'la-empire'                 => 'f1c5',
            'la-envelope'               => 'f1c6',
            'la-envelope-o'             => 'f1c7',
            'la-envelope-square'        => 'f1c8',
            'la-eraser'                 => 'f1c9',
            'la-eur'                    => 'f1ca',
            'la-euro'                   => 'f1cb',
            'la-exchange'               => 'f1cc',
            'la-exclamation'            => 'f1cd',
            'la-exclamation-circle'     => 'f1ce',
            'la-exclamation-triangle'   => 'f1cf',
            'la-expand'                 => 'f1d0',
            'la-expeditedssl'           => 'f1d1',
            'la-external-link'          => 'f1d2',
            'la-external-link-square'   => 'f1d3',
            'la-eye'                    => 'f1d4',
            'la-eye-slash'              => 'f1d5',
            'la-eyedropper'             => 'f1d6',
            'la-facebook'               => 'f1d7',
            'la-facebook-f'             => 'f1d8',
            'la-facebook-official'      => 'f1d9',
            'la-facebook-square'        => 'f1da',
            'la-fast-backward'          => 'f1db',
            'la-fast-forward'           => 'f1dc',
            'la-fax'                    => 'f1dd',
            'la-female'                 => 'f1de',
            'la-fighter-jet'            => 'f1df',
            'la-file'                   => 'f1e0',
            'la-file-archive-o'         => 'f1e1',
            'la-file-audio-o'           => 'f1e2',
            'la-file-code-o'            => 'f1e3',
            'la-file-excel-o'           => 'f1e4',
            'la-file-image-o'           => 'f1e5',
            'la-file-movie-o'           => 'f1e6',
            'la-file-o'                 => 'f1e7',
            'la-file-pdf-o'             => 'f1e8',
            'la-file-photo-o'           => 'f1e9',
            'la-file-picture-o'         => 'f1ea',
            'la-file-powerpoint-o'      => 'f1eb',
            'la-file-sound-o'           => 'f1ec',
            'la-file-text'              => 'f1ed',
            'la-file-text-o'            => 'f1ee',
            'la-file-video-o'           => 'f1ef',
            'la-file-word-o'            => 'f1f0',
            'la-file-zip-o'             => 'f1f1',
            'la-files-o'                => 'f1f2',
            'la-film'                   => 'f1f3',
            'la-filter'                 => 'f1f4',
            'la-fire'                   => 'f1f5',
            'la-fire-extinguisher'      => 'f1f6',
            'la-firefox'                => 'f1f7',
            'la-flag'                   => 'f1f8',
            'la-flag-checkered'         => 'f1f9',
            'la-flag-o'                 => 'f1fa',
            'la-flash'                  => 'f1fb',
            'la-flask'                  => 'f1fc',
            'la-flickr'                 => 'f1fd',
            'la-floppy-o'               => 'f1fe',
            'la-folder'                 => 'f1ff',
            'la-folder-o'               => 'f200',
            'la-folder-open'            => 'f201',
            'la-folder-open-o'          => 'f202',
            'la-font'                   => 'f203',
            'la-fonticons'              => 'f204',
            'la-forumbee'               => 'f205',
            'la-forward'                => 'f206',
            'la-foursquare'             => 'f207',
            'la-frown-o'                => 'f208',
            'la-futbol-o'               => 'f209',
            'la-gamepad'                => 'f20a',
            'la-gavel'                  => 'f20b',
            'la-gbp'                    => 'f20c',
            'la-ge'                     => 'f20d',
            'la-gear'                   => 'f20e',
            'la-gears'                  => 'f20f',
            'la-genderless'             => 'f210',
            'la-get-pocket'             => 'f211',
            'la-gg'                     => 'f212',
            'la-gg-circle'              => 'f213',
            'la-gift'                   => 'f214',
            'la-git'                    => 'f215',
            'la-git-square'             => 'f216',
            'la-github'                 => 'f217',
            'la-github-alt'             => 'f218',
            'la-github-square'          => 'f219',
            'la-gittip'                 => 'f21a',
            'la-glass'                  => 'f21b',
            'la-globe'                  => 'f21c',
            'la-google'                 => 'f21d',
            'la-google-plus'            => 'f21e',
            'la-google-plus-square'     => 'f21f',
            'la-google-wallet'          => 'f220',
            'la-graduation-cap'         => 'f221',
            'la-gratipay'               => 'f222',
            'la-group'                  => 'f223',
            'la-h-square'               => 'f224',
            'la-hacker-news'            => 'f225',
            'la-hand-grab-o'            => 'f226',
            'la-hand-lizard-o'          => 'f227',
            'la-hand-o-down'            => 'f228',
            'la-hand-o-left'            => 'f229',
            'la-hand-o-right'           => 'f22a',
            'la-hand-o-up'              => 'f22b',
            'la-hand-paper-o'           => 'f22c',
            'la-hand-peace-o'           => 'f22d',
            'la-hand-pointer-o'         => 'f22e',
            'la-hand-rock-o'            => 'f22f',
            'la-hand-scissors-o'        => 'f230',
            'la-hand-spock-o'           => 'f231',
            'la-hand-stop-o'            => 'f232',
            'la-hdd-o'                  => 'f233',
            'la-header'                 => 'f234',
            'la-headphones'             => 'f235',
            'la-heart'                  => 'f236',
            'la-heart-o'                => 'f237',
            'la-heartbeat'              => 'f238',
            'la-history'                => 'f239',
            'la-home'                   => 'f23a',
            'la-hospital-o'             => 'f23b',
            'la-hotel'                  => 'f23c',
            'la-hourglass'              => 'f23d',
            'la-hourglass-1'            => 'f23e',
            'la-hourglass-2'            => 'f23f',
            'la-hourglass-3'            => 'f240',
            'la-hourglass-end'          => 'f241',
            'la-hourglass-half'         => 'f242',
            'la-hourglass-o'            => 'f243',
            'la-hourglass-start'        => 'f244',
            'la-houzz'                  => 'f245',
            'la-html5'                  => 'f246',
            'la-i-cursor'               => 'f247',
            'la-ils'                    => 'f248',
            'la-image'                  => 'f249',
            'la-inbox'                  => 'f24a',
            'la-indent'                 => 'f24b',
            'la-industry'               => 'f24c',
            'la-info'                   => 'f24d',
            'la-info-circle'            => 'f24e',
            'la-inr'                    => 'f24f',
            'la-instagram'              => 'f250',
            'la-institution'            => 'f251',
            'la-internet-explorer'      => 'f252',
            'la-ioxhost'                => 'f253',
            'la-italic'                 => 'f254',
            'la-joomla'                 => 'f255',
            'la-jpy'                    => 'f256',
            'la-jsfiddle'               => 'f257',
            'la-key'                    => 'f258',
            'la-keyboard-o'             => 'f259',
            'la-krw'                    => 'f25a',
            'la-language'               => 'f25b',
            'la-laptop'                 => 'f25c',
            'la-lastfm'                 => 'f25d',
            'la-lastfm-square'          => 'f25e',
            'la-leaf'                   => 'f25f',
            'la-leanpub'                => 'f260',
            'la-legal'                  => 'f261',
            'la-lemon-o'                => 'f262',
            'la-level-down'             => 'f263',
            'la-level-up'               => 'f264',
            'la-life-bouy'              => 'f265',
            'la-life-buoy'              => 'f266',
            'la-life-ring'              => 'f267',
            'la-life-saver'             => 'f268',
            'la-lightbulb-o'            => 'f269',
            'la-line-chart'             => 'f26a',
            'la-link'                   => 'f26b',
            'la-linkedin'               => 'f26c',
            'la-linkedin-square'        => 'f26d',
            'la-linux'                  => 'f26e',
            'la-list'                   => 'f26f',
            'la-list-alt'               => 'f270',
            'la-list-ol'                => 'f271',
            'la-list-ul'                => 'f272',
            'la-location-arrow'         => 'f273',
            'la-lock'                   => 'f274',
            'la-long-arrow-down'        => 'f275',
            'la-long-arrow-left'        => 'f276',
            'la-long-arrow-right'       => 'f277',
            'la-long-arrow-up'          => 'f278',
            'la-magic'                  => 'f279',
            'la-magnet'                 => 'f27a',
            'la-mail-forward'           => 'f27b',
            'la-mail-reply'             => 'f27c',
            'la-mail-reply-all'         => 'f27d',
            'la-male'                   => 'f27e',
            'la-map'                    => 'f27f',
            'la-map-marker'             => 'f280',
            'la-map-o'                  => 'f281',
            'la-map-pin'                => 'f282',
            'la-map-signs'              => 'f283',
            'la-mars'                   => 'f284',
            'la-mars-double'            => 'f285',
            'la-mars-stroke'            => 'f286',
            'la-mars-stroke-h'          => 'f287',
            'la-mars-stroke-v'          => 'f288',
            'la-maxcdn'                 => 'f289',
            'la-meanpath'               => 'f28a',
            'la-medium'                 => 'f28b',
            'la-medkit'                 => 'f28c',
            'la-meh-o'                  => 'f28d',
            'la-mercury'                => 'f28e',
            'la-microphone'             => 'f28f',
            'la-microphone-slash'       => 'f290',
            'la-minus'                  => 'f291',
            'la-minus-circle'           => 'f292',
            'la-minus-square'           => 'f293',
            'la-minus-square-o'         => 'f294',
            'la-mobile'                 => 'f295',
            'la-mobile-phone'           => 'f296',
            'la-money'                  => 'f297',
            'la-moon-o'                 => 'f298',
            'la-mortar-board'           => 'f299',
            'la-motorcycle'             => 'f29a',
            'la-mouse-pointer'          => 'f29b',
            'la-music'                  => 'f29c',
            'la-navicon'                => 'f29d',
            'la-neuter'                 => 'f29e',
            'la-newspaper-o'            => 'f29f',
            'la-object-group'           => 'f2a0',
            'la-object-ungroup'         => 'f2a1',
            'la-odnoklassniki'          => 'f2a2',
            'la-odnoklassniki-square'   => 'f2a3',
            'la-opencart'               => 'f2a4',
            'la-openid'                 => 'f2a5',
            'la-opera'                  => 'f2a6',
            'la-optin-monster'          => 'f2a7',
            'la-outdent'                => 'f2a8',
            'la-pagelines'              => 'f2a9',
            'la-paint-brush'            => 'f2aa',
            'la-paper-plane'            => 'f2ab',
            'la-paper-plane-o'          => 'f2ac',
            'la-paperclip'              => 'f2ad',
            'la-paragraph'              => 'f2ae',
            'la-paste'                  => 'f2af',
            'la-pause'                  => 'f2b0',
            'la-paw'                    => 'f2b1',
            'la-paypal'                 => 'f2b2',
            'la-pencil'                 => 'f2b3',
            'la-pencil-square'          => 'f2b4',
            'la-pencil-square-o'        => 'f2b5',
            'la-phone'                  => 'f2b6',
            'la-phone-square'           => 'f2b7',
            'la-photo'                  => 'f2b8',
            'la-picture-o'              => 'f2b9',
            'la-pie-chart'              => 'f2ba',
            'la-pied-piper'             => 'f2bb',
            'la-pied-piper-alt'         => 'f2bc',
            'la-pinterest'              => 'f2bd',
            'la-pinterest-p'            => 'f2be',
            'la-pinterest-square'       => 'f2bf',
            'la-plane'                  => 'f2c0',
            'la-play'                   => 'f2c1',
            'la-play-circle'            => 'f2c2',
            'la-play-circle-o'          => 'f2c3',
            'la-plug'                   => 'f2c4',
            'la-plus'                   => 'f2c5',
            'la-plus-circle'            => 'f2c6',
            'la-plus-square'            => 'f2c7',
            'la-plus-square-o'          => 'f2c8',
            'la-power-off'              => 'f2c9',
            'la-print'                  => 'f2ca',
            'la-puzzle-piece'           => 'f2cb',
            'la-qq'                     => 'f2cc',
            'la-qrcode'                 => 'f2cd',
            'la-question'               => 'f2ce',
            'la-question-circle'        => 'f2cf',
            'la-quote-left'             => 'f2d0',
            'la-quote-right'            => 'f2d1',
            'la-ra'                     => 'f2d2',
            'la-random'                 => 'f2d3',
            'la-rebel'                  => 'f2d4',
            'la-recycle'                => 'f2d5',
            'la-reddit'                 => 'f2d6',
            'la-reddit-square'          => 'f2d7',
            'la-refresh'                => 'f2d8',
            'la-registered'             => 'f2d9',
            'la-renren'                 => 'f2da',
            'la-reorder'                => 'f2db',
            'la-repeat'                 => 'f2dc',
            'la-reply'                  => 'f2dd',
            'la-reply-all'              => 'f2de',
            'la-retweet'                => 'f2df',
            'la-rmb'                    => 'f2e0',
            'la-road'                   => 'f2e1',
            'la-rocket'                 => 'f2e2',
            'la-rotate-left'            => 'f2e3',
            'la-rotate-right'           => 'f2e4',
            'la-rouble'                 => 'f2e5',
            'la-rss'                    => 'f2e6',
            'la-rss-square'             => 'f2e7',
            'la-rub'                    => 'f2e8',
            'la-ruble'                  => 'f2e9',
            'la-rupee'                  => 'f2ea',
            'la-safari'                 => 'f2eb',
            'la-save'                   => 'f2ec',
            'la-scissors'               => 'f2ed',
            'la-search'                 => 'f2ee',
            'la-search-minus'           => 'f2ef',
            'la-search-plus'            => 'f2f0',
            'la-sellsy'                 => 'f2f1',
            'la-send'                   => 'f2f2',
            'la-send-o'                 => 'f2f3',
            'la-server'                 => 'f2f4',
            'la-share'                  => 'f2f5',
            'la-share-alt'              => 'f2f6',
            'la-share-alt-square'       => 'f2f7',
            'la-share-square'           => 'f2f8',
            'la-share-square-o'         => 'f2f9',
            'la-shekel'                 => 'f2fa',
            'la-sheqel'                 => 'f2fb',
            'la-shield'                 => 'f2fc',
            'la-ship'                   => 'f2fd',
            'la-shirtsinbulk'           => 'f2fe',
            'la-shopping-cart'          => 'f2ff',
            'la-sign-in'                => 'f300',
            'la-sign-out'               => 'f301',
            'la-signal'                 => 'f302',
            'la-simplybuilt'            => 'f303',
            'la-sitemap'                => 'f304',
            'la-skyatlas'               => 'f305',
            'la-skype'                  => 'f306',
            'la-slack'                  => 'f307',
            'la-sliders'                => 'f308',
            'la-slideshare'             => 'f309',
            'la-smile-o'                => 'f30a',
            'la-soccer-ball-o'          => 'f30b',
            'la-sort'                   => 'f30c',
            'la-sort-alpha-asc'         => 'f30d',
            'la-sort-alpha-desc'        => 'f30e',
            'la-sort-amount-asc'        => 'f30f',
            'la-sort-amount-desc'       => 'f310',
            'la-sort-asc'               => 'f311',
            'la-sort-desc'              => 'f312',
            'la-sort-down'              => 'f313',
            'la-sort-numeric-asc'       => 'f314',
            'la-sort-numeric-desc'      => 'f315',
            'la-sort-up'                => 'f316',
            'la-soundcloud'             => 'f317',
            'la-space-shuttle'          => 'f318',
            'la-spinner'                => 'f319',
            'la-spoon'                  => 'f31a',
            'la-spotify'                => 'f31b',
            'la-square'                 => 'f31c',
            'la-square-o'               => 'f31d',
            'la-stack-exchange'         => 'f31e',
            'la-stack-overflow'         => 'f31f',
            'la-star'                   => 'f320',
            'la-star-half'              => 'f321',
            'la-star-half-empty'        => 'f322',
            'la-star-half-full'         => 'f323',
            'la-star-half-o'            => 'f324',
            'la-star-o'                 => 'f325',
            'la-steam'                  => 'f326',
            'la-steam-square'           => 'f327',
            'la-step-backward'          => 'f328',
            'la-step-forward'           => 'f329',
            'la-stethoscope'            => 'f32a',
            'la-sticky-note'            => 'f32b',
            'la-sticky-note-o'          => 'f32c',
            'la-stop'                   => 'f32d',
            'la-street-view'            => 'f32e',
            'la-strikethrough'          => 'f32f',
            'la-stumbleupon'            => 'f330',
            'la-stumbleupon-circle'     => 'f331',
            'la-subscript'              => 'f332',
            'la-subway'                 => 'f333',
            'la-suitcase'               => 'f334',
            'la-sun-o'                  => 'f335',
            'la-superscript'            => 'f336',
            'la-support'                => 'f337',
            'la-table'                  => 'f338',
            'la-tablet'                 => 'f339',
            'la-tachometer'             => 'f33a',
            'la-tag'                    => 'f33b',
            'la-tags'                   => 'f33c',
            'la-tasks'                  => 'f33d',
            'la-taxi'                   => 'f33e',
            'la-television'             => 'f33f',
            'la-tencent-weibo'          => 'f340',
            'la-terminal'               => 'f341',
            'la-text-height'            => 'f342',
            'la-text-width'             => 'f343',
            'la-th'                     => 'f344',
            'la-th-large'               => 'f345',
            'la-th-list'                => 'f346',
            'la-thumb-tack'             => 'f347',
            'la-thumbs-down'            => 'f348',
            'la-thumbs-o-down'          => 'f349',
            'la-thumbs-o-up'            => 'f34a',
            'la-thumbs-up'              => 'f34b',
            'la-ticket'                 => 'f34c',
            'la-times'                  => 'f34d',
            'la-times-circle'           => 'f34e',
            'la-times-circle-o'         => 'f34f',
            'la-tint'                   => 'f350',
            'la-toggle-down'            => 'f351',
            'la-toggle-left'            => 'f352',
            'la-toggle-off'             => 'f353',
            'la-toggle-on'              => 'f354',
            'la-toggle-right'           => 'f355',
            'la-toggle-up'              => 'f356',
            'la-trademark'              => 'f357',
            'la-train'                  => 'f358',
            'la-transgender'            => 'f359',
            'la-transgender-alt'        => 'f35a',
            'la-trash'                  => 'f35b',
            'la-trash-o'                => 'f35c',
            'la-tree'                   => 'f35d',
            'la-trello'                 => 'f35e',
            'la-tripadvisor'            => 'f35f',
            'la-trophy'                 => 'f360',
            'la-truck'                  => 'f361',
            'la-try'                    => 'f362',
            'la-tty'                    => 'f363',
            'la-tumblr'                 => 'f364',
            'la-tumblr-square'          => 'f365',
            'la-turkish-lira'           => 'f366',
            'la-twitch'                 => 'f367',
            'la-twitter'                => 'f368',
            'la-twitter-square'         => 'f369',
            'la-umbrella'               => 'f36a',
            'la-underline'              => 'f36b',
            'la-undo'                   => 'f36c',
            'la-university'             => 'f36d',
            'la-unlink'                 => 'f36e',
            'la-unlock'                 => 'f36f',
            'la-unlock-alt'             => 'f370',
            'la-unsorted'               => 'f371',
            'la-upload'                 => 'f372',
            'la-usd'                    => 'f373',
            'la-user'                   => 'f374',
            'la-user-md'                => 'f375',
            'la-user-plus'              => 'f376',
            'la-user-secret'            => 'f377',
            'la-user-times'             => 'f378',
            'la-users'                  => 'f379',
            'la-venus'                  => 'f37a',
            'la-venus-double'           => 'f37b',
            'la-venus-mars'             => 'f37c',
            'la-viacoin'                => 'f37d',
            'la-video-camera'           => 'f37e',
            'la-vimeo'                  => 'f37f',
            'la-vimeo-square'           => 'f380',
            'la-vine'                   => 'f381',
            'la-vk'                     => 'f382',
            'la-volume-down'            => 'f383',
            'la-volume-off'             => 'f384',
            'la-volume-up'              => 'f385',
            'la-warning'                => 'f386',
            'la-wechat'                 => 'f387',
            'la-weibo'                  => 'f388',
            'la-weixin'                 => 'f389',
            'la-whatsapp'               => 'f38a',
            'la-wheelchair'             => 'f38b',
            'la-wifi'                   => 'f38c',
            'la-wikipedia-w'            => 'f38d',
            'la-windows'                => 'f38e',
            'la-won'                    => 'f38f',
            'la-wordpress'              => 'f390',
            'la-wrench'                 => 'f391',
            'la-xing'                   => 'f392',
            'la-xing-square'            => 'f393',
            'la-y-combinator'           => 'f394',
            'la-y-combinator-square'    => 'f395',
            'la-yahoo'                  => 'f396',
            'la-yc'                     => 'f397',
            'la-yc-square'              => 'f398',
            'la-yelp'                   => 'f399',
            'la-yen'                    => 'f39a',
            'la-youtube'                => 'f39b',
            'la-youtube-play'           => 'f39c',
            'la-youtube-square'         => 'f39d',
        );

        parent::__construct();
    }
}