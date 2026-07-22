<?php
/**
 * PropertyRubix — Front Controller
 * All public requests route through here via .htaccess
 */

// Bootstrap
define('APP_START', microtime(true));

require_once __DIR__ . '/config/db.php';
require_once APP_PATH . 'helpers/slug.php';
require_once APP_PATH . 'helpers/auth.php';
require_once APP_PATH . 'helpers/csrf.php';
require_once APP_PATH . 'helpers/upload.php';
require_once APP_PATH . 'helpers/pagination.php';
require_once APP_PATH . 'helpers/settings.php';
require_once APP_PATH . 'helpers/api_auth.php';
require_once APP_PATH . 'core/View.php';
require_once APP_PATH . 'core/Controller.php';
require_once APP_PATH . 'core/Router.php';

// Autoload controllers
foreach (glob(APP_PATH . 'controllers/*.php') as $controllerFile) {
    require_once $controllerFile;
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialise router and define routes
$router = new Router();

// Home
$router->get('/', 'HomeController', 'index');

// Projects
$router->get('/projects', 'ProjectController', 'listing');
$router->get('/project/{slug}', 'ProjectController', 'detail');

// Properties
$router->get('/properties', 'PropertyController', 'listing');
$router->get('/property/{slug}', 'PropertyController', 'detail');

// Location drill-down
$router->get('/location', 'LocationController', 'index');
$router->get('/location/{country}', 'LocationController', 'country');
$router->get('/location/{country}/{state}', 'LocationController', 'state');
$router->get('/location/{country}/{state}/{city}', 'LocationController', 'city');
$router->get('/location/{country}/{state}/{city}/{locality}', 'LocationController', 'locality');

// Developer
$router->get('/developer', 'DeveloperController', 'index');
$router->get('/developer/{slug}', 'DeveloperController', 'profile');

// Blog
$router->get('/blog', 'BlogController', 'listing');
$router->get('/blog/{slug}', 'BlogController', 'detail');

// Static / CMS pages
$router->get('/about-us', 'PageController', 'show', ['slug' => 'about-us']);
$router->get('/contact-us', 'PageController', 'contact');
$router->get('/privacy-policy', 'PageController', 'show', ['slug' => 'privacy-policy']);
$router->get('/terms-conditions', 'PageController', 'show', ['slug' => 'terms-conditions']);
$router->get('/advertise-with-us', 'PageController', 'show', ['slug' => 'advertise-with-us']);

// AJAX endpoints
$router->get('/ajax/search', 'AjaxController', 'search');
$router->post('/ajax/submit-enquiry', 'AjaxController', 'submitEnquiry');
$router->post('/ajax/submit-site-visit', 'AjaxController', 'submitSiteVisit');
$router->post('/ajax/subscribe', 'AjaxController', 'subscribe');

// ============================================================
// API v1 REST Endpoints
// ============================================================
$router->get('/api/v1/projects', 'ApiProjectController', 'index');
$router->get('/api/v1/projects/{id}', 'ApiProjectController', 'show');
$router->get('/api/v1/properties', 'ApiPropertyController', 'index');
$router->get('/api/v1/properties/{id}', 'ApiPropertyController', 'show');
$router->get('/api/v1/builders', 'ApiBuilderController', 'index');
$router->get('/api/v1/locations', 'ApiLocationController', 'index');
$router->post('/api/v1/leads', 'ApiLeadController', 'store');
$router->get('/api/v1/feeds/trovit', 'ApiFeedController', 'trovitXml');
$router->get('/api/v1/feeds/propertyfinder', 'ApiFeedController', 'propertyfinderXml');

// Sitemap
$router->get('/sitemap.xml', 'SitemapController', 'xml');

// Dispatch
$router->dispatch();
