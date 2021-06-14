<?php
$currentUrl = \Request::getRequestUri();
$allMenus = \Harimayco\Menu\Models\Menus::where('clientes_id', auth()->user()->cliente->id)->limit(2)->get();
$paginas = \App\Models\Pagina::all();

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@include('wmenu::custom')

<div id="hwpwrap">
    <div class="custom-wp-admin wp-admin wp-core-ui js menu-max-depth-0 nav-menus-php auto-fold admin-bar">
        <div id="wpwrap">
            <div id="wpcontent">
                <div id="wpbody">
                    <div id="wpbody-content">

                        <div class="wrap">
                            <div id="nav-menus-frame">
                                @if(request()->has('menu') && !empty(request()->input("menu")))
                                    <div id="menu-settings-column" class="metabox-holder">

                                        <div class="clear"></div>

                                        <form id="nav-menu-meta" action="" class="nav-menu-meta" method="post"
                                              enctype="multipart/form-data">
                                            <div id="side-sortables" class="accordion-container">
                                                <ul class="outer-border">
                                                    <li class="control-section accordion-section  open add-page"
                                                        id="add-page">
                                                        <h3 class="accordion-section-title hndle"
                                                            tabindex="0"> @lang('Novo Item') <span
                                                                    class="screen-reader-text">@lang('Press return or enter to expand')</span>
                                                        </h3>
                                                        <div class="accordion-section-content ">
                                                            <div class="inside">
                                                                <div class="customlinkdiv" id="customlinkdiv">

                                                                    <p id="menu-item-name-wrap form-group">
                                                                        <label class="howto"
                                                                               for="custom-menu-item-name">
                                                                            <span>@lang('Nome')</span>
                                                                            <input id="custom-menu-item-name"
                                                                                   name="label" type="text"
                                                                                   class="form-control regular-text menu-item-textbox input-with-default-title"
                                                                                   title="@lang('Nome do menu')"
                                                                                   required>
                                                                        </label>
                                                                    </p>
                                                                    <p id="menu-item-url-wrap form-group">
                                                                        <label class="howto" for="custom-menu-item-url">
                                                                            <span>Link para Página Personalizada</span>
                                                                            <select id="custom-menu-item-pagina_id"
                                                                                    class="form-control regular-text menu-item-textbox input-with-default-title"
                                                                                    name="pagina_id">
                                                                                <option value="{{null}}">Selecione uma
                                                                                    página
                                                                                </option>
                                                                                @foreach($paginas as $pag)
                                                                                    <option
                                                                                            value="{{ $pag->id }}">{{ ucfirst($pag->nome) }}</option>
                                                                                @endforeach
                                                                            </select>


                                                                        </label>
                                                                    </p>
                                                                    <p id="menu-item-url-wrap form-group">
                                                                        <label class="howto" for="custom-menu-item-url">
                                                                            <span>@lang('URL')</span>
                                                                            <input id="custom-menu-item-url" name="url"
                                                                                   type="text"
                                                                                   class="form-control regular-text menu-item-textbox input-with-default-title "
                                                                                   placeholder="Url">
                                                                        </label>
                                                                        <br>
                                                                        <small class="text-danger">Não é necessário
                                                                            informar
                                                                            uma URL se uma página for
                                                                            selecionada.</small>
                                                                    </p>
                                                                    <hr>
                                                                    <p id="menu-item-icon-wrap form-group">
                                                                        <span>Ícone</span>
                                                                        <br>
                                                                        <span id="iconSelected" style="display: none">

                                                                             <label class="howto"
                                                                                    for="custom-menu-item-icon">
                                                                                 Selecionado:
                                                                             </label>
                                                                              <input type="hidden"
                                                                                     name="icon" value=""
                                                                                     id="custom-menu-item-icon"
                                                                                     class="regular-text menu-item-textbox input-with-default-title"
                                                                                     title="Icon"
                                                                              >
                                                                            <i id="iconSelectedCls" class=""></i>
                                                                            <br>
                                                                        </span>
                                                                        <button id="btnSelIcon"
                                                                                type="button"
                                                                                class="btn btn-info btn-sm">
                                                                            Selecionar Ícone
                                                                        </button>
                                                                        <label id="listIcons" style="display: none">
                                                                            <span>@lang('Click em um ícone para selecionar')</span><br>
                                                                            @php $faIcons = app(\App\Classes\FontAwesome::class)->getIcons(); @endphp
                                                                            <span class="row">
                                                                                 @foreach($faIcons as $icon => $icode)
                                                                                    <span class="md-1 mr-1 iconToSelect"
                                                                                          data-ico="fa {{$icon}}">
                                                                                    <i class="fa {{$icon}}"></i>
                                                                                </span>
                                                                                @endforeach
                                                                            </span>
                                                                        </label>
                                                                    </p>

                                                                    @if(!empty($roles))
                                                                        <p id="menu-item-role_id-wrap">
                                                                            <label class="howto"
                                                                                   for="custom-menu-item-name">
                                                                                <span>@lang('Role')</span>
                                                                                <select id="custom-menu-item-role"
                                                                                        name="role">
                                                                                    <option
                                                                                            value="0">@lang('Select Role')</option>
                                                                                    @foreach($roles as $role)
                                                                                        <option
                                                                                                value="{{ $role->$role_pk }}">{{ ucfirst($role->$role_title_field) }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </label>
                                                                        </p>
                                                                    @endif

                                                                    <hr>

                                                                    <p class="button-controls">
                                                                        <a href="#" onclick="addcustommenu()"
                                                                           class="btn btn-success btn-sm submit-add-to-menu right"
                                                                           style="color:white">
                                                                            @lang('Adicionar Item') <i
                                                                                    class="fa fa-plus"></i>
                                                                        </a>
                                                                        <span class="spinner" id="spincustomu"></span>
                                                                    </p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </form>

                                    </div>
                                @endif
                                <div id="menu-management-liquid">
                                    <div id="menu-management">
                                        <form id="update-nav-menu" class="update-nav-menu" action="" method="post"
                                              enctype="multipart/form-data">
                                            <div class="menu-edit ">
                                                <div id="nav-menu-header">
                                                    <div class="major-publishing-actions">
                                                        <label class="menu-name-label howto open-label" for="menu-name">
                                                            <span><b>Selecione o menu para Edição: </b></span>
                                                            <select name="menu-name" id="_menu-name"
                                                                    class="form-control menu-name regular-text menu-item-textbox">
                                                                @foreach($allMenus as $men)
                                                                    <option
                                                                            value="{{@$men->id}}" {{@$men->id == @$indmenu->id ? 'selected' : ''}}>
                                                                        {{$men->name}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" id="idmenu"
                                                                   value="@if(isset($indmenu)){{$indmenu->id}}@endif"/>
                                                        </label>
                                                        @if(request()->has('action'))
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu"
                                                                   id="save_menu_header"
                                                                   class="button button-primary menu-save">@lang('Create menu')</a>
                                                            </div>
                                                        @elseif(request()->has("menu"))
                                                            {{--                                                        <div class="publishing-action">--}}
                                                            {{--                                                            <a onclick="getmenus()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">@lang('Save menu')</a>--}}
                                                            {{--                                                            <span class="spinner" id="spincustomu2"></span>--}}
                                                            {{--                                                        </div>--}}
                                                        @else
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu"
                                                                   id="save_menu_header"
                                                                   class="button button-primary menu-save">@lang('Create menu')</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div id="post-body">
                                                    <div id="post-body-content">

                                                        @if(request()->has("menu"))
                                                            <h3>@lang('Estrutura do Menu')</h3>
                                                            <div class="drag-instructions post-body-plain" style="">
                                                                <p>
                                                                    @lang('Edite a ordem no menu clicando e arrastando. Para editar, clique na seta na lateral do link.')
                                                                </p>
                                                            </div>

                                                        @else
                                                            <h3>@lang('Menu Creation')</h3>
                                                            <div class="drag-instructions post-body-plain" style="">
                                                                <p>
                                                                    @lang('Please enter the name and select "Create menu" button')
                                                                </p>
                                                            </div>
                                                        @endif

                                                        <ul class="menu ui-sortable" id="menu-to-edit">
                                                            @if(isset($menus))
                                                                @foreach($menus as $m)
                                                                    <li id="menu-item-{{$m->id}}"
                                                                        class="menu-item menu-item-depth-{{$m->depth}} menu-item-page menu-item-edit-inactive"
                                                                        style="display: list-item;">
                                                                        <dl class="menu-item-bar">
                                                                            <dt class="menu-item-handle">
                                                                        <span class="item-title">
                                                                            <span class="menu-item-title">
                                                                                <span id="spinner-item-{{$m->id}}"
                                                                                      class="spinner spinner-custom"
                                                                                      data-teste="teste-{{$m->id}}"
                                                                                      style="display:none;"></span>
                                                                                <span id="menutitletemp_{{$m->id}}">
                                                                                <i class="{{$m->icon}}"></i> {{$m->label}}
                                                                                </span>
                                                                                <span style="color: transparent;">|{{$m->id}}|</span>
                                                                                <span class="is-submenu"
                                                                                      style="@if($m->depth==0)display:none;@endif">@lang('Submenu')</span>
                                                                            </span>
                                                                        </span>
                                                                                <span class="item-controls">
                                                                            <span class="item-type">Link</span>

                                                                            <a class="item-edit" id="edit-{{$m->id}}"
                                                                               title="{{$m->name}}"
                                                                               href="{{ $currentUrl }}?edit-menu-item={{$m->id}}#menu-item-settings-{{$m->id}}">
                                                                                <i class="fa fa-arrow-circle-o-down"></i>
                                                                            </a>
                                                                        </span>

                                                                        </dl>

                                                                        <div class="menu-item-settings"
                                                                             id="menu-item-settings-{{$m->id}}">
                                                                            <input type="hidden"
                                                                                   class="edit-menu-item-id"
                                                                                   name="menuid_{{$m->id}}"
                                                                                   value="{{$m->id}}"/>
                                                                            <p class="form-group description description-thin">
                                                                                <label
                                                                                        for="edit-menu-item-title-{{$m->id}}"> @lang('Nome')
                                                                                    <br>
                                                                                    <input type="text"
                                                                                           id="idlabelmenu_{{$m->id}}"
                                                                                           class="form-control widefat edit-menu-item-title"
                                                                                           name="idlabelmenu_{{$m->id}}"
                                                                                           value="{{$m->label}}">
                                                                                </label>
                                                                            </p>
                                                                            <p class="mt-4 form-group field-css-url description description-wide">
                                                                                <label
                                                                                        for="edit-menu-item-pagina-{{@$m->pagina->id}}">
                                                                                    Link Para Página Personalizada
                                                                                    <br>
                                                                                    <select
                                                                                            id="edit-menu-item-pagina_id-{{$m->id}}"
                                                                                            class="form-control widefat code edit-menu-item-pagina_id"
                                                                                    >
                                                                                        <option value="{{null}}">
                                                                                            Selecione uma página
                                                                                        </option>
                                                                                        @foreach($paginas as $pag)
                                                                                            <option
                                                                                                    @if(@$m->pagina->id == $pag->id) {{'selected'}} @endif
                                                                                                    value="{{ $pag->id }}">{{ ucfirst($pag->nome) }}</option>
                                                                                        @endforeach
                                                                                    </select>

                                                                                </label>
                                                                            </p>
                                                                            <p class="form-group field-css-url description description-wide">
                                                                                <label
                                                                                        for="edit-menu-item-url-{{$m->id}}">
                                                                                    Url
                                                                                    <br>
                                                                                    <input type="text"
                                                                                           id="url_menu_{{$m->id}}"
                                                                                           class="form-control widefat code edit-menu-item-url"
                                                                                           value="{{$m->link}}">
                                                                                </label>
                                                                            </p>

                                                                            <p class="form-group field-css-classes description description-thin">
                                                                                <label
                                                                                        for="edit-menu-item-classes-{{$m->id}}"> @lang('Ícone')
                                                                                    - <i class="{{$m->icon}}"></i>
                                                                                    <br>
                                                                                    <input type="text"
                                                                                           id="icon_menu_{{$m->id}}"
                                                                                           class="form-control widefat code edit-menu-item-icon"
                                                                                           name="icon_menu_{{$m->id}}"
                                                                                           value="{{$m->icon}}">
                                                                                </label>
                                                                            </p>

                                                                            @if(!empty($roles))
                                                                                <p class="field-css-role description description-wide">
                                                                                    <label
                                                                                            for="edit-menu-item-role-{{$m->id}}"> @lang('Role')
                                                                                        <br>
                                                                                        <select
                                                                                                id="role_menu_{{$m->id}}"
                                                                                                class="widefat code edit-menu-item-role"
                                                                                                name="role_menu_[{{$m->id}}]">
                                                                                            <option
                                                                                                    value="0">@lang('Select Role')</option>
                                                                                            @foreach($roles as $role)
                                                                                                <option
                                                                                                        @if($role->id == $m->role_id) selected
                                                                                                        @endif value="{{ $role->$role_pk }}">{{ ucwords($role->$role_title_field) }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </label>
                                                                                </p>
                                                                            @endif

                                                                            <div
                                                                                    class="menu-item-actions description-wide submitbox">

                                                                                <a class="btn btn-danger btn-sm item-delete"
                                                                                   id="delete-{{$m->id}}"
                                                                                   href="{{ $currentUrl }}">@lang('Deletar')</a>

                                                                                <a onclick="getmenus()"
                                                                                   class="btn btn-info btn-sm updatemenu"
                                                                                   id="update-{{$m->id}}"
                                                                                   href="javascript:void(0)">@lang('Atualizar')</a>

                                                                            </div>

                                                                        </div>
                                                                        <ul class="menu-item-transport"></ul>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                        <div class="menu-settings">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="nav-menu-footer">
                                                    <div class="major-publishing-actions">

                                                        @if(request()->has('action'))
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu"
                                                                   id="save_menu_header"
                                                                   class="button button-primary menu-save">@lang('Create menu')</a>
                                                            </div>
                                                        @elseif(request()->has("menu"))

                                                        @else
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu"
                                                                   id="save_menu_header"
                                                                   class="button button-primary menu-save">@lang('Create menu')</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
    </div>
</div>
