// Default cookie prefixes for cache bypassing
const DEFAULT_BYPASS_COOKIES = [
    "wp-",
    "wordpress_logged_in_",
    "comment_",
    "woocommerce_",
    "wordpress_sec_",
    "yith_wcwl_products",
    "edd_items_in_cart",
    "it_exchange_session_",
    "wordpresspass_",
    "comment_author",
    "dshack_level",
    "wordpressuser_",
    "auth",
    "noaffiliate_",
    "mp_session",
    "mp_globalcart_",
];

async function handleRequest(event) {

    let request = event.request;
    let bypassCache = false;
    let response = false;

    // Bypass cache if are present specific cookies
    const cookieHeader = request.headers.get('cookie');
    let bypassCookies = DEFAULT_BYPASS_COOKIES;

    if( cookieHeader && cookieHeader.length && bypassCookies.length ) {

        const cookies = cookieHeader.split(';');

        for( let cookie of cookies ) {

            for( let prefix of bypassCookies ) {

                if( cookie.trim().startsWith(prefix) ) {
                    bypassCache = true;
                    break;
                }

            }

            if (bypassCache) {
                break;
            }

        }

    }


    // Bypass cache for non-html requests
    let accept = request.headers.get('Accept');
    // Lets handle the request URL nicely
    const requestURL = new URL(request.url)

    // Check if accept has value and the type is not text/html || */* || the user is accessing WP-ADMIN
    // Then only we set bypassCache as true else we will bypass that request and won't modify anything 
    if(
        (
            !accept &&
            !( accept.includes('text/html') || accept.includes('*/*') )
        ) ||
      requestURL.pathname.match(/^(?:\/wp-admin\/)/g)
    ) {
      bypassCache = true;
    }

    // When bypassCache is false that means we should intercept that request and add our headers
    if( !bypassCache ) {

        // Get the cacheKey from by passing our requestURL & request
        let cacheKey = new Request(requestURL, request);
        let cache = caches.default;

        // Get this request from this zone's cache
        response = await cache.match(cacheKey);

        if (response) {

            response = new Response(response.body, response);
            response.headers.set('SG-Optimizer-Worker-Status', 'hit');

        } else {

            response = await fetch(request);
            response = new Response(response.body, response);

            if (response.headers.has('X-Cache-Enabled')) {

                response.headers.set('Cache-Control', response.headers.get('SG-Optimizer-Cache-Control'));
                response.headers.set('SG-Optimizer-Worker-Status', 'miss');

                // add page in cache
                event.waitUntil(cache.put(cacheKey, response.clone()));

            } else {
                response.headers.set('SG-Optimizer-Worker-Status', 'bypass');
            }

        }

    }
    // Get response from the origin
    else {

        response = await fetch(request);
        response = new Response(response.body, response);
        response.headers.set('SG-Optimizer-Worker-Status', 'bypass');
        response.headers.set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

    }

    return response;

}

addEventListener('fetch', event => {

    try {
        return event.respondWith(handleRequest(event));
    } catch (e) {
        return event.respondWith(new Response('Error thrown ' + e.message));
    }

})