/**
 * CookGenie frontend js.
 *
 *  @package CookGenie Plugin Template/JS
 */

function consentAdStorage () {
    gtag('consent', 'update', {
        'ad_storage': 'granted',
    });
}

function consentAdUserData () {
    gtag('consent', 'update', {
        'ad_user_data': 'granted',
    });
}

function consentAdPersonalization () {
    gtag('consent', 'update', {
        'ad_personalization': 'granted',
    });
}

function consentAnalyticsStorage () {
    gtag('consent', 'update', {
        'analytics_storage': 'granted',
    });
}

function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/; domain=." + location.hostname;
}

function AllowCookies() {
    createCookie('cookiegenie_consent', fdata.version + '.' + Date.now(), fdata.expire);
    window.yett.unblock();

    if(fdata.ad_storage === 'on')
        consentAdStorage();

    if(fdata.ad_user_data === 'on')
        consentAdUserData();

    if(fdata.ad_personalization === 'on')
        consentAdPersonalization();

    if(fdata.analytics_storage === 'on')
        consentAnalyticsStorage();

    const element = document.getElementById("cg-container");
    element.remove();
}

function DisallowCookies() {
    createCookie('cookiegenie_block', fdata.version + '.' + Date.now(), fdata.expire);

    const element = document.getElementById("cg-container");
    element.remove();
}