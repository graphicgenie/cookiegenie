function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
}

YETT_BLACKLIST = []
data.blacklist.forEach(function (value) {
    if(value !== '')
        YETT_BLACKLIST.push(new RegExp(escapeRegExp(encodeURIComponent(value))))
})

YETT_WHITELIST = []
data.whitelist.forEach(function (value) {
    if(value !== '')
        YETT_WHITELIST.push(new RegExp(escapeRegExp(encodeURIComponent(value))))
})