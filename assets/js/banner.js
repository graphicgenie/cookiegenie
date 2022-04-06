/**
 * Plugin Template banner js.
 *
 *  @package WordPress Plugin Template/JS
 */
// import Cookies from 'js-cookie'
jQuery(document).ready(function($) {
    if(!Cookies.get("cookiegenie_consent") && !Cookies.get("cookiegenie_block"))
        $('body').append(consentBanner);
});

let readMore = ''
if(banner.cookiedeclaration !== '')
    readMore = ' <a href="' + banner.cookiedeclaration + '">' + banner.readmore + '</a>'

let consentBanner =
    '<style>' +
    '#cg-container {' +
        'background-color:' + banner.bck_color + ';' +
        'color:' + banner.scn_color + ';' +
    '}' +
    '.cg-btn {' +
        'color:' + banner.scn_color + ';' +
        'border-color:' + banner.scn_color + ';' +
    '}' +
    '#cg-image svg {' +
        'fill:' + banner.scn_color + ';' +
    '}' +
    '.cg-block-btn:hover, .cg-block-btn:focus {' +
        'background-color:' + banner.bck_color + ';' +
        'color:' + banner.scn_color + ';' +
    '}' +
    '#cg-text p, #cg-text h3 {' +
        'color:' + banner.scn_color + ';' +
    '}' +
    '.cg-unblock-btn, .cg-unblock-btn:hover, .cg-unblock-btn:focus {' +
        'background-color:' + banner.scn_color + ';' +
        'color:' + banner.bck_color + ';' +
    '}' +
    '</style>' +
    '<div id="cg-container">' +
        '<div id="cg-grid">' +
            '<div id="cg-image">' +
                '<h3>' + banner.cookietitle + '</h3>' +
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">' +
                    '<!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->' +
                    '<path d="M494.6 255.9c-65.63-.8203-118.6-54.14-118.6-119.9c-65.74 0-119.1-52.97-119.8-118.6c-25.66-3.867-51.8 .2346-74.77 12.07L116.7 62.41C93.35 74.36 74.36 93.35 62.41 116.7L29.6 181.2c-11.95 23.44-16.17 49.92-12.07 75.94l11.37 71.48c4.102 25.9 16.29 49.8 34.81 68.32l51.36 51.39C133.6 466.9 157.3 479 183.2 483.1l71.84 11.37c25.9 4.101 52.27-.1172 75.59-11.95l64.81-33.05c23.32-11.84 42.31-30.82 54.14-54.14l32.93-64.57C494.3 307.7 498.5 281.4 494.6 255.9zM176 367.1c-17.62 0-32-14.37-32-31.1s14.38-31.1 32-31.1s32 14.37 32 31.1S193.6 367.1 176 367.1zM208 208c-17.62 0-32-14.37-32-31.1s14.38-31.1 32-31.1s32 14.37 32 31.1S225.6 208 208 208zM368 335.1c-17.62 0-32-14.37-32-31.1s14.38-31.1 32-31.1s32 14.37 32 31.1S385.6 335.1 368 335.1z"/>' +
                '</svg>' +
            '</div>' +
            '<div id="cg-text">' +
                '<h3>' + banner.cookietitle + '</h3>' +
                '<p>' + banner.cookietext + readMore + '</p>' +
            '</div>' +
            '<div id="cg-buttons">' +
                '<button class="cg-btn cg-block-btn" onclick="DisallowCookies()">' + banner.btn_disallow + '</button>' +
                '<button class="cg-btn cg-unblock-btn" onclick="AllowCookies()">' + banner.btn_allow + '</button>' +
            '</div>' +
        '</div>' +
    '</div>';