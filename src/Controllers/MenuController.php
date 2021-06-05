<?php

namespace Harimayco\Menu\Controllers;

use Illuminate\Http\Request;
use Harimayco\Menu\Models\Menus;
use Illuminate\Routing\Controller;
use Harimayco\Menu\Models\MenuItems;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('loadmenu') == 1 && $request->get('menu') == 0) {
            $message = 'Select a menu or create a new one';
            if ($request->get('action') == 'deletemenu') {
                $message = 'Successfully deleted!';
                msg_toastr($message, 'success');
            } else {
                msg($message, 'info');
            }
            return redirect($request->path() . '?action=edit&menu=0');
        } elseif ($request->get('loadmenu') == 1 && $request->get('menu') > 0) {
            $mennn = Menus::find($request->get('menu'))->toArray()['name'];
            $message = 'Menu: ( ' . $mennn . ' ) successfully loaded!';
            if ($request->get('action') == 'newmenu') {
                $message = 'Menu: ( ' . $mennn . ' ) successfully created!';
            } elseif ($request->get('action') == 'addcustommenu') {
                $message = 'Menu: ( ' . $mennn . ' ) successfully created!';
            }
            msg_toastr($message, 'success');
            return redirect($request->path() . '?menu=' . $request->get('menu'));
        }
        return view(config('menu.blade_view'));
    }

    public function createnewmenu()
    {
        $menu = new Menus();
        $menu->name = request()->input("menuname");
        $menu->save();
        return json_encode(array("resp" => $menu->id));
    }

    public function deleteitemmenu()
    {
        $menuitem = MenuItems::find(request()->input("id"));
        $menuitem->delete();
        $message = msg_toastr('Item successfully deleted!');
        return response()->json($message);
    }

    public function deletemenug()
    {
        $menus = new MenuItems();
        $getall = $menus->getall(request()->input("id"));
        if (count($getall) == 0) {
            $menudelete = Menus::find(request()->input("id"));
            $menudelete->delete();
            return json_encode(array("resp" => "you delete this item"));
        } else {
            return json_encode(array("resp" => "You have to delete all items first", "error" => 1));
        }
    }

    public function updateitem()
    {
        $arraydata = request()->input("arraydata");
        if (is_array($arraydata)) {
            foreach ($arraydata as $value) {
                $menuitem = MenuItems::find($value['id']);
                $menuitem->label = $value['label'];
                $menuitem->link = $value['link'];
                $menuitem->class = $value['class'];
                $menuitem->icon = $value['icon'];
                $menuitem->pagina_id = @$value['pagina_id'];
                if (config('menu.use_roles')) {
                    $menuitem->role_id = $value['role_id'] ? $value['role_id'] : 0;
                }
                $menuitem->save();
            }
        } else {
            $menuitem = MenuItems::find(request()->input("id"));
            $menuitem->label = request()->input("label");
            $menuitem->link = request()->input("url");
            $menuitem->class = request()->input("clases");
            $menuitem->icon = request()->input("icon");
            $menuitem->pagina_id = request()->input("pagina_id");
            if (config('menu.use_roles')) {
                $menuitem->role_id = request()->input("role_id") ? request()->input("role_id") : 0;
            }
            $menuitem->save();
        }
        $message = msg_toastr('Menu updated success!');
        return response()->json($message);
    }

    public function addcustommenu()
    {
        $menuitem = new MenuItems();
        $menuitem->label = request()->input("labelmenu");
        $menuitem->link = request()->input("linkmenu");
        $menuitem->icon = request()->input("iconmenu");
        $menuitem->pagina_id = request()->input("paginaidmenu");
        if (config('menu.use_roles')) {
            $menuitem->role_id = request()->input("rolemenu") ? request()->input("rolemenu") : 0;
        }
        $menuitem->menu = request()->input("idmenu");
        $menuitem->sort = MenuItems::getNextSortRoot(request()->input("idmenu"));
        $menuitem->save();
        $message = msg_toastr('Updating settings!', 'info');
        return response()->json([
            'resp' => request()->input("idmenu"),
            'message' => $message
        ]);
    }

    public function generatemenucontrol()
    {
        $menu = Menus::find(request()->input("idmenu"));
        $menu->name = request()->input("menuname");
        $menu->save();
        if (is_array(request()->input("arraydata"))) {
            foreach (request()->input("arraydata") as $value) {
                $menuitem = MenuItems::find($value["id"]);
                $menuitem->parent = $value["parent"];
                $menuitem->sort = $value["sort"];
                $menuitem->depth = $value["depth"];
                if (config('menu.use_roles')) {
                    if (request()->input("role_id") !== null) {
                        $menuitem->role_id = request()->input("role_id");
                    }
                }
                $menuitem->save();
            }
        }
    }

}
