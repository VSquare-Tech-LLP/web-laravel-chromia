<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Efectn\Menu\Models\MenuItems;
use Efectn\Menu\Models\Menus;
use Gate;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class MenuController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index(Request $request)
    {
        if (!Gate::allows('menu_manager_access')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $menu = $menu_data = optional();
        if ($request->menu) {
            $menu = Menus::find($request->menu);
            $menu_data = json_decode($menu->value);
        }

        $categories = Category::get();

        $posts = Post::where('published_status', 1)->where('parent_id', 0)->get();

        $pages = Post::withoutGlobalScope('post')->page()->where('published_status', 1)->get();

        $menu_list = Menus::get();

//        dd($menu, $menu_data, $menu_list);

        return view('backend.menu-manager.index', compact('menu', 'menu_data', 'menu_list', 'categories', 'posts', 'pages'));
    }

    // =============== harimayco/laravel-menu =============== //

    /**
     * Create new menu
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createNewMenu()
    {
        $menu = new Menus();
        $menu->name = request()->input("menuname");
        $menu->save();

        return json_encode(["resp" => $menu->id]);
    }

    /**
     * Save custom link item in menu
     *
     * @param \Illuminate\Http\Request
     */
    public function addCustomMenu()
    {
        $menuitem = new MenuItems();
        $menuitem->label = request()->input("labelmenu");
        $menuitem->link = request()->input("linkmenu");
        $menuitem->menu = request()->input("idmenu");
        $menuitem->type = __('strings.backend.menu_manager.link');
        $menuitem->sort = MenuItems::getNextSortRoot(request()->input("idmenu"));
        $menuitem->save();
    }

    /**
     * Save menu item link storage(like as categories, page, post)
     *
     * @param \Illuminate\Http\Request $request
     */
    public function saveCustomItem(Request $request)
    {
        foreach ($request->data as $item) {
            $type = "";
            if ($item['type'] == 'category') {
                $type = __('strings.backend.menu_manager.category');
                $object = Category::find((int)$item['item_id']);
            } else {
                if ($item['type'] == 'page') {
                    $type = __('strings.backend.menu_manager.page');
                } else {
                    $type = __('strings.backend.menu_manager.post');
                }
                $object = Post::withoutGlobalScope('post')->find((int)$item['item_id']);
            }

            $menuitem = new MenuItems();
            $menuitem->label = $item['labelmenu'];
            $menuitem->link = $object->slug;
            $menuitem->item_id = $object->id;
            $menuitem->menu_id = $item['idmenu'];
            $menuitem->type = $type;
            $menuitem->sort = MenuItems::getNextSortRoot($item['idmenu']);
            $menuitem->save();
        }
    }

    /**
     * Remove menu item storage
     *
     * @param \Illuminate\Http\Request
     */
    public function deleteItemMenu()
    {
        $menuitem = MenuItems::find(request()->input("id"));
        $menuitem->delete();
    }

    /**
     * Remove menu storage
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMenu()
    {
        if (in_array(request()->input("id"), [1, 2])) {
            return json_encode(["resp" => "You can't delete this menu.", "error" => 1]);
        }
        $menus = new MenuItems();
        $getall = $menus->getall(request()->input("id"));
        if (count($getall) == 0) {
            $menudelete = Menus::find(request()->input("id"));
            $menudelete->delete();

            return json_encode(["resp" => "you delete this item"]);
        } else {
            return json_encode(["resp" => "You have to delete all items first", "error" => 1]);
        }
    }

    public function deletemenug()
    {
        if (in_array(request()->input("id"), [1, 2])) {
            return json_encode(["resp" => "You can't delete this menu.", "error" => 1]);
        }
        $menus = new MenuItems();
        $getall = $menus->getall(request()->input("id"));
        if (count($getall) == 0) {
            $menudelete = Menus::find(request()->input("id"));
            $menudelete->delete();

            return json_encode(["resp" => "you delete this item"]);
        } else {
            return json_encode(["resp" => "You have to delete all items first", "error" => 1]);
        }
    }

    /**
     * Update menu item
     *
     * @param Illuminate\Http\Request
     */
    public function updateItem()
    {
        $arraydata = request()->input("arraydata");
        if (is_array($arraydata)) {
            foreach ($arraydata as $value) {
                $menuitem = MenuItems::find($value['id']);
                $menuitem->label = $value['label'];
                $menuitem->link = $value['link'];
                $menuitem->class = $value['class'];
                $menuitem->save();
            }
        } else {
            $menuitem = MenuItems::find(request()->input("id"));
            $menuitem->label = request()->input("label");
            $menuitem->link = request()->input("url");
            $menuitem->class = request()->input("clases");
            $menuitem->save();
        }
    }



    /**
     * Update menu item position
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function generateMenuControl()
    {
        $menu = Menus::find(request()->input("idmenu"));
        $menu->name = request()->input("menuname");

        $menu->save();
        if (is_array(request()->input("arraydata"))) {
            foreach (request()->input("arraydata") as $value) {
                $menuitem = MenuItems::find($value["id"]);
                $menuitem->parent_id = $value["parent"];
                $menuitem->sort = $value["sort"];
                $menuitem->depth = $value["depth"];
                if (config('menu.use_roles')) {
                    $menuitem->role_id = request()->input("role_id");
                }
                $menuitem->save();
            }
        }
        echo json_encode(["resp" => 1]);
    }

    /**
     * Update menu locations
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMenuOrder(Request $request)
    {
        $themesMenu = Config::where('key', 'LIKE', '%theme_mods_%')->get();
        foreach ($themesMenu as $themeMods) {
            $mods = json_decode($themeMods->value, true);
            unset($request['_token']);
            foreach ($request->all() as $key => $value) {
                if (Arr::has($mods, 'nav_menu_location.' . $key)) {
                    Arr::set($mods, 'nav_menu_location.' . $key, $value);
                }
            }
            $themeMods->value = json_encode($mods);
            $themeMods->save();
        }

        return back();
    }
}
