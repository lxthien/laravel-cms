/**
 * Frontend Entry Point – Laravel CMS
 *
 * Imports jQuery and frontend-specific modules.
 * Admin JS is loaded separately via resources/js/admin.js.
 */

import jQuery from 'jquery';
window.jQuery = window.jQuery || jQuery;
window.$ = window.jQuery;

import './modules/search-suggestions';
import './modules/comment-reply';
import './modules/mobile-menu';
import './modules/youtube-modal';
import './modules/back-to-top';
