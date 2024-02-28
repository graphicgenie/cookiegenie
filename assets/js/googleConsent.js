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

if(fdata.ad_storage === 'on')
    consentAdStorage();

if(fdata.ad_user_data === 'on')
    consentAdUserData();

if(fdata.ad_personalization === 'on')
    consentAdPersonalization();

if(fdata.analytics_storage === 'on')
    consentAnalyticsStorage();