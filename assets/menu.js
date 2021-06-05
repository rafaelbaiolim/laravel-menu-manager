$("#menu-to-edit .menu-item").mouseup(function () {
    var obj = $(this);
    if (obj.hasClass('loading')) return;
    obj.addClass('loading');
});

function settingsFunc(m_var) {
    $('.menu-item-settings').each(function () {
        var id_item = $(this).find('.edit-menu-item-id').val();
        var obj = $('#menu-item-' + id_item);
        var item_id = $('#menu-item-' + id_item).attr('id');
        if (m_var == 'beforeSend' && obj.hasClass('loading')) {
            $('#spincustomu2').show();
            $(obj).addClass('loading_beforeSend').removeClass('loading');
            $('.loading_beforeSend .spinner.spinner-custom').show();
        }
        if (m_var == 'complete' && obj.hasClass('loading_beforeSend')) {
            $('.loading_beforeSend .spinner.spinner-custom').hide();
            obj.removeClass('loading_beforeSend');
            $('#spincustomu2').hide();
        }
    });
}

var arraydata = [];

function getmenus() {
    arraydata = [];
    $('#spinsavemenu').show();
    var cont = 0;
    $('#menu-to-edit li').each(function (index, ui) {
        var dept = 0;
        for (var i = 0; i < $('#menu-to-edit li').length; i++) {
            var n = $(this)
                .attr('class')
                .indexOf('menu-item-depth-' + i);
            if (n != -1) {
                dept = i;
            }
        }
        var textoiner = $(this)
            .find('.item-edit')
            .text();
        var id = this.id.split('-');
        var textoexplotado = textoiner.split('|');
        var padre = 0;
        if (!!textoexplotado[textoexplotado.length - 2] && textoexplotado[textoexplotado.length - 2] != id[2]) {
            padre = textoexplotado[textoexplotado.length - 2];
        }
        arraydata.push({
            depth: dept,
            id: id[2],
            parent: padre,
            sort: cont
        });
        cont++;
    });
}

function addcustommenu() {
    $('#spincustomu').show();
    let dat = {
        labelmenu: $('#custom-menu-item-name').val(),
        linkmenu: $('#custom-menu-item-url').val(),
        iconmenu: $('#custom-menu-item-icon').val(),
        rolemenu: $('#custom-menu-item-role').val(),
        paginaidmenu: $('#custom-menu-item-pagina_id').val(),
        idmenu: $('#idmenu').val(),
    };
    $.ajax({
        data: dat,
        url: addcustommenur,
        type: 'POST',
        success: function (response) {
            flashMessage(response.message);
            if (!response.error) {
                console.log(dat);
                var newurl = menuwr + '?loadmenu=1&action=addcustommenu&menu=' + response.resp;
                // window.location = newurl;
            } else {
                alert(response.resp);
            }
        },
        complete: function () {
            $('#spincustomu').hide();
        }
    });
}

function updateitem(id = 0) {
    if (id) {
        var label = $('#idlabelmenu_' + id).val();
        var clases = $('#clases_menu_' + id).val();
        var url = $('#url_menu_' + id).val();
        var icon = $('#icon_menu_' + id).val();
        var pagina_id = $('#pagina_id_menu_' + id).val();
        var role_id = 0;
        if ($('#role_menu_' + id).length) {
            role_id = $('#role_menu_' + id).val();
        }
        var data = {
            label: label,
            clases: clases,
            url: url,
            icon: icon,
            role_id: role_id,
            pagina_id: pagina_id,
            id: id
        };
    } else {
        var arr_data = [];
        $('.menu-item-settings').each(function (k, v) {
            var id = $(this)
                .find('.edit-menu-item-id')
                .val();
            var label = $(this)
                .find('.edit-menu-item-title')
                .val();
            var clases = $(this)
                .find('.edit-menu-item-classes')
                .val();
            var url = $(this)
                .find('.edit-menu-item-url')
                .val();
            var icon = $(this)
                .find('.edit-menu-item-icon')
                .val();
            var role = $(this)
                .find('.edit-menu-item-role')
                .val();
            var pagina_id = $(this)
                .find('.edit-menu-item-pagina_id')
                .val();
            arr_data.push({
                id: id,
                label: label,
                class: clases,
                link: url,
                icon: icon,
                role_id: role,
                pagina_id: pagina_id
            });
        });
        var data = {arraydata: arr_data};
    }
    $.ajax({
        data: data,
        url: updateitemr,
        type: 'POST',
        beforeSend: function () {
            settingsFunc('beforeSend');
        },
        success: function (response) {
            flashMessage(response);
        },
        complete: function () {
            settingsFunc('complete');
        }
    });
}

function actualizarmenu() {
    $.ajax({
        dataType: 'json',
        data: {
            arraydata: arraydata,
            menuname: $('#menu-name').val(),
            idmenu: $('#idmenu').val()
        },
        url: generatemenucontrolr,
        type: 'POST',
        beforeSend: function () {
            settingsFunc('beforeSend');
        },
        success: function (response) {
        },
        complete: function () {
            settingsFunc('complete');
        }
    });
}

function deleteitem(id) {
    $.ajax({
        dataType: 'json',
        data: {
            id: id
        },
        url: deleteitemmenur,
        type: 'POST',
        success: function (response) {
            flashMessage(response);
        },
        complete: function () {
            $('#spincustomu2').hide();
        }
    });
}

function deletemenu() {
    var r = confirm('Do you want to delete this menu ?');
    if (r == true) {
        $.ajax({
            dataType: 'json',
            data: {
                id: $('#idmenu').val()
            },
            url: deletemenugr,
            type: 'POST',
            beforeSend: function (xhr) {
                $('#spincustomu2').show();
            },
            success: function (response) {
                if (!response.error) {
                    window.location = menuwr + '?loadmenu=1&action=deletemenu&menu=0';
                } else {
                    alert(response.resp);
                }
            },
            complete: function () {
                $('#spincustomu2').hide();
            }
        });
    } else {
        return false;
    }
}

function createnewmenu() {
    if (!!$('#menu-name').val()) {
        $.ajax({
            dataType: 'json',
            data: {
                menuname: $('#menu-name').val()
            },
            url: createnewmenur,
            type: 'POST',
            success: function (response) {
                window.location = menuwr + '?loadmenu=1&action=newmenu&menu=' + response.resp;
            }
        });
    } else {
        alert('Enter menu name!');
        $('#menu-name').focus();
        return false;
    }
}

function insertParam(key, value) {
    key = encodeURI(key);
    value = encodeURI(value);
    var kvp = document.location.search.substr(1).split('&');
    var i = kvp.length;
    var x;
    while (i--) {
        x = kvp[i].split('=');
        if (x[0] == key) {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }
    if (i < 0) {
        kvp[kvp.length] = [key, value].join('=');
    }
    document.location.search = kvp.join('&');
}

wpNavMenu.registerChange = function () {
    getmenus();
    if ($('#menu-settings-column').attr('id')) {
        updateitem();
        actualizarmenu();
    }
};
