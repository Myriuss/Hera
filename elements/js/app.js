let setCookie = (cname, cvalue, exdays) => {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}






$(document).ready(() => {

    $('header div label').attr('for', getCookie("OtherColor"))
    let changColor = ($color, $OtherColor) => {
        $('.bg-color-primary').css('background-color', 'var(--' + $color + ')')
        $('.text-color-primary').css('color', ('var(--' + $OtherColor + ')'))
        $('  input, select , .bg-color-secondary ').css('background-color', 'var(--' + $color + 'Secondary )')

    }

    let themes_colors = ['light', 'dark']
    if (themes_colors.includes(getCookie('color')) && themes_colors.includes(getCookie('OtherColor'))) {
        changColor(getCookie('color'), getCookie('OtherColor'))
    }





    $('.radio input').click(e => {
        let $btn = $(e.target)
        let $color = $btn.attr('id')
        $('.radio').siblings('label').attr('for', $($btn).siblings().attr('id'))
        let $OtherColor = $('.radio').siblings('label').attr('for')

        changColor($color, $OtherColor)

        setCookie('color', $color, 365)
        setCookie('OtherColor', $OtherColor, 365)
        $(e.target).parent().toggleClass('bg-success')
    })




})