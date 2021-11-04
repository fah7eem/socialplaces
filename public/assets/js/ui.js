(function(window, document) {

    // we fetch the elements each time because docusaurus removes the previous
    // element references on page navigation
    function getElements() {
        return {
            layout: document.getElementById('layout'),
            menu: document.getElementById('menu'),
            menuLink: document.getElementById('menuLink')
        };
    }

    function toggleClass(element, className) {
        var classes = element.className.split(/\s+/);
        var length = classes.length;
        var i = 0;

        for (; i < length; i++) {
            if (classes[i] === className) {
                classes.splice(i, 1);
                break;
            }
        }
        // The className is not found
        if (length === classes.length) {
            classes.push(className);
        }

        element.className = classes.join(' ');
    }

    function toggleAll() {
        var active = 'active';
        var elements = getElements();

        toggleClass(elements.layout, active);
        toggleClass(elements.menu, active);
        toggleClass(elements.menuLink, active);
    }

    function handleEvent(e) {
        var elements = getElements();

        if (e.target.id === elements.menuLink.id) {
            toggleAll();
            e.preventDefault();
        } else if (elements.menu.className.indexOf('active') !== -1) {
            toggleAll();
        }
    }

    document.addEventListener('click', handleEvent);

}(this, this.document));


function exitHandler() {
    if (document.webkitIsFullScreen || document.mozFullScreen || document.msFullscreenElement !== null) {
        ["menugroup", "header", "footer"].forEach(function(id) {
            var x = document.getElementById(id);
            if (x.style.display == 'none') {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        });
    }
}

var elem = document.documentElement;

function openFullscreen() {
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.webkitRequestFullscreen) { /* Safari */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE11 */
        elem.msRequestFullscreen();
    }
    if (document.addEventListener) {
        document.addEventListener('fullscreenchange', exitHandler, false);
        document.addEventListener('mozfullscreenchange', exitHandler, false);
        document.addEventListener('MSFullscreenChange', exitHandler, false);
        document.addEventListener('webkitfullscreenchange', exitHandler, false);
    }
}

function menuHighlight() {
    $(".pure-menu-link ").each(function() {
        if (this.href == window.location.href) {
            $target = $(this);
            $target.addClass('underline bold');
        }
    });
}

function openTab(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
        tablinks[i].className = tablinks[i].className.replace(" black", "");

    }
    document.getElementById(cityName).style.display = "block";
    try {
        evt.currentTarget.className += " active";
        evt.currentTarget.className += " black";
    } catch (err) {
        $('#dashboardLink').addClass("active");
        $('#dashboardLink').addClass("black");
    }
}