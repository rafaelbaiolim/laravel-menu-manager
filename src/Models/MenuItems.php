<?php

namespace Harimayco\Menu\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItems extends Model
{

    protected $table = null;
    protected $fillable = ['label', 'link', 'icon', 'parent', 'sort', 'class', 'menu',
        'depth', 'role_id', 'pagina_id'];

    public function __construct(array $attributes = [])
    {
        //parent::construct( $attributes );
        $this->table = config('menu.table_prefix') . config('menu.table_name_items');
    }

    public function getsons($id)
    {
        return $this->where("parent", $id)->get();
    }

    public function getall($id)
    {
        return $this->where("menu", $id)->orderBy("sort", "asc")->get();
    }

    public static function getNextSortRoot($menu)
    {
        return self::where('menu', $menu)->max('sort') + 1;
    }

    public function parent_menu()
    {
        return $this->belongsTo('Harimayco\Menu\Models\Menus', 'menu');
    }

    public function child()
    {
        return $this->hasMany('Harimayco\Menu\Models\MenuItems', 'parent')->orderBy('sort', 'ASC');
    }

    public function pagina()
    {
        return $this->belongsTo('App\Models\Pagina', 'pagina_id');
    }

    public function getLinkAttribute($val)
    {
        if (empty($this->pagina_id)) {
            return $val;
        }
        return \App\Models\Pagina::URL . $this->pagina->slug;
    }

}
