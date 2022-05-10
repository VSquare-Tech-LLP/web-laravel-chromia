<?php

namespace App\Domains\Auth\Http\Controllers\Backend\User;

use App\Domains\Auth\Http\Requests\Backend\User\DeleteUserRequest;
use App\Domains\Auth\Http\Requests\Backend\User\EditUserRequest;
use App\Domains\Auth\Http\Requests\Backend\User\StoreUserRequest;
use App\Domains\Auth\Http\Requests\Backend\User\UpdateUserRequest;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\PermissionService;
use App\Domains\Auth\Services\RoleService;
use App\Domains\Auth\Services\UserService;
use App\Models\UserMeta;
use Illuminate\Http\Request;

/**
 * Class UserController.
 */
class UserController
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * UserController constructor.
     *
     * @param  UserService  $userService
     * @param  RoleService  $roleService
     * @param  PermissionService  $permissionService
     */
    public function __construct(UserService $userService, RoleService $roleService, PermissionService $permissionService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.auth.user.index');
    }

    /**
     * @return mixed
     */
    public function create()
    {
        return view('backend.auth.user.create')
            ->withRoles($this->roleService->get())
            ->withCategories($this->permissionService->getCategorizedPermissions())
            ->withGeneral($this->permissionService->getUncategorizedPermissions());
    }

    /**
     * @param  StoreUserRequest  $request
     * @return mixed
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->store($request->validated());

        return redirect()->route('admin.auth.user.show', $user)->withFlashSuccess(__('The user was successfully created.'));
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function show(User $user)
    {
        return view('backend.auth.user.show')
            ->withUser($user);
    }

    /**
     * @param  EditUserRequest  $request
     * @param  User  $user
     * @return mixed
     */
    public function edit(EditUserRequest $request, User $user)
    {
        return view('backend.auth.user.edit')
            ->withUser($user)
            ->withRoles($this->roleService->get())
            ->withCategories($this->permissionService->getCategorizedPermissions())
            ->withGeneral($this->permissionService->getUncategorizedPermissions())
            ->withUsedPermissions($user->permissions->modelKeys());
    }

    /**
     * @param  UpdateUserRequest  $request
     * @param  User  $user
     * @return mixed
     *
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->update($user, $request->validated());

        return redirect()->route('admin.auth.user.show', $user)->withFlashSuccess(__('The user was successfully updated.'));
    }

    /**
     * @param  DeleteUserRequest  $request
     * @param  User  $user
     * @return mixed
     *
     * @throws \App\Exceptions\GeneralException
     */
    public function destroy(DeleteUserRequest $request, User $user)
    {
        $this->userService->delete($user);

        return redirect()->route('admin.auth.user.deleted')->withFlashSuccess(__('The user was successfully deleted.'));
    }

    public function profile(User $user)
    {
        $userMetas = optional();
        if ($user->userMeta()->exists()) {
            $userMetas = $user->userMeta;
        }

        return view('backend.auth.user.profile', compact('user', 'userMetas'));
    }

    public function postProfile(Request $request, User $user)
    {
        if (!file_exists(public_path('storage/profile'))) {
            mkdir(public_path('storage/profile'), 0777);
        }

        $userMeta = UserMeta::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'short_bio' => $request->short_bio,
                'long_bio' => $request->long_bio,
            ]
        );

        if ($request->has('image')) {
            if ($userMeta->image && file_exists(public_path('storage/profile/' . $userMeta->image))) {
                unlink(public_path('storage/profile/' . $userMeta->image));
            }
            $extension = array_last(explode('.', $request->file('image')->getClientOriginalName()));
            $name = array_first(explode('.', $request->file('image')->getClientOriginalName()));
            $filename = str_slug($user->name) . '-' . str_slug($name) . '.' . $extension;
            $request->file('image')->move(public_path('storage/profile'), $filename);
            $userMeta->image = $filename;
            $userMeta->save();
        }

        return redirect()->back()->withFlashSuccess('Profile updated successfully');
    }
}
