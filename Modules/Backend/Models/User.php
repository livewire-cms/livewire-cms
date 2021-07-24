<?php namespace Modules\Backend\Models;

use Mail;
use Event;
use Backend;
use BackendAuth;
use Modules\LivewireCore\Auth\Models\User as UserBase;
use Modules\LivewireCore\Database\Model;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use InvalidArgumentException;


/**
 * Administrator user model
 *
 * @package winter\wn-backend-module
 * @author Alexey Bobkov, Samuel Georges
 */
class User extends Model implements

    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract

{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    use \Modules\LivewireCore\Database\Traits\Hashable;
    use \Modules\LivewireCore\Database\Traits\SoftDelete;
    use \Modules\LivewireCore\Database\Traits\Purgeable;
    use \Modules\LivewireCore\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    protected $table = 'backend_users';

    /**
     * Validation rules
     */
    public $rules = [
        'email' => 'required|between:6,255|email|unique:backend_users',
        'login' => 'required|between:2,255|unique:backend_users',
        'password' => 'required:create|min:4|confirmed',
        'password_confirmation' => 'required_with:password|min:4'
    ];

    /**
     * @var array Attributes that should be cast to dates
     */
    protected $dates = [
        'activated_at',
        'last_login',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'login',
        'email',
        'password',
        // 'password_confirmation'
    ];

    /**
     * Relations
     */
    public $belongsToMany = [
        'groups' => [UserGroup::class, 'table' => 'backend_users_groups']
    ];

    public $belongsTo = [
        'role' => UserRole::class
    ];

    public $attachOne = [
        'avatar' => \Modules\System\Models\File::class
    ];

    public $attachMany = [
        'avatars' => \Modules\System\Models\File::class
    ];

    protected $hidden = ['password', 'reset_password_code', 'activation_code', 'persist_code'];
    protected $guarded = ['is_superuser', 'reset_password_code', 'activation_code', 'persist_code', 'role_id'];

    protected $hashable = ['password', 'persist_code'];

    /**
     * Purge attributes from data set.
     */
    protected $purgeable = ['password_confirmation', 'send_invite'];

    /**
     * @var string Login attribute
     */
    public static $loginAttribute = 'login';

        /**
     * @var array The array of custom attribute names.
     */
    public $attributeNames = [];

    /**
     * @var array The array of custom error messages.
     */
    public $customMessages = [];

    /**
     * @var array List of attribute names which are json encoded and decoded from the database.
     */
    protected $jsonable = ['permissions'];

    /**
     * Allowed permissions values.
     *
     * Possible options:
     *   -1 => Deny (adds to array, but denies regardless of user's group).
     *    0 => Remove.
     *    1 => Add.
     *
     * @var array
     */
    protected $allowedPermissionsValues = [-1, 0, 1];


    /**
     * @var array The user merged permissions.
     */
    protected $mergedPermissions;

    /**
     * @return string Returns the user's full name.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Gets a code for when the user is persisted to a cookie or session which identifies the user.
     * @return string
     */
    public function getPersistCode()
    {
        // Option A: @todo config
        // return parent::getPersistCode();

        // Option B:
        if (!$this->persist_code) {
            return parent::getPersistCode();
        }

        return $this->persist_code;
    }

    /**
     * Returns the public image file path to this user's avatar.
     */
    public function getAvatarThumb($size = 25, $options = null)
    {
        if (is_string($options)) {
            $options = ['default' => $options];
        }
        elseif (!is_array($options)) {
            $options = [];
        }

        // Default is "mm" (Mystery man)
        $default = \Arr::get($options, 'default', 'mm');

        if ($this->avatar) {
            return $this->avatar->getThumb($size, $size, $options);
        }

        return '//www.gravatar.com/avatar/' .
            md5(strtolower(trim($this->email))) .
            '?s='. $size .
            '&d='. urlencode($default);
    }

    /**
     * After create event
     * @return void
     */
    public function afterCreate()
    {
        $this->restorePurgedValues();

        if ($this->send_invite) {
            // $this->sendInvitation();
        }
    }

    /**
     * After login event
     * @return void
     */
    public function afterLogin()
    {
        parent::afterLogin();

        /**
         * @event backend.user.login
         * Provides an opportunity to interact with the Backend User model after the user has logged in
         *
         * Example usage:
         *
         *     Event::listen('backend.user.login', function ((\Backend\Models\User) $user) {
         *         Flash::success(sprintf('Welcome %s!', $user->getFullNameAttribute()));
         *     });
         *
         */
        Event::fire('backend.user.login', [$this]);
    }

    /**
     * Sends an invitation to the user using template "backend::mail.invite".
     * @return void
     */
    public function sendInvitation()
    {
        $data = [
            'name' => $this->full_name,
            'login' => $this->login,
            'password' => $this->getOriginalHashValue('password'),
            'link' => Backend::url('backend'),
        ];

        Mail::send('backend::mail.invite', $data, function ($message) {
            $message->to($this->email, $this->full_name);
        });
    }

    public function getGroupsOptions()
    {
        $result = [];

        foreach (UserGroup::all() as $group) {
            $result[$group->id] = [$group->name, $group->description];
        }

        return $result;
    }

    public function getRoleOptions()
    {
        $result = [];

        foreach (UserRole::all() as $role) {
            $result[$role->id] = [$role->name, $role->description];
        }

        return $result;
    }

    /**
     * Check if the user is suspended.
     * @return bool
     */
    public function isSuspended()
    {
        return BackendAuth::findThrottleByUserId($this->id)->checkSuspended();
    }

    /**
     * Remove the suspension on this user.
     * @return void
     */
    public function unsuspend()
    {
        BackendAuth::findThrottleByUserId($this->id)->unsuspend();
    }

        /**
     * Protects the password from being reset to null.
     */
    public function setPasswordAttribute($value)
    {
        if ($this->exists && empty($value)) {
            unset($this->attributes['password']);
        }
        else {
            $this->attributes['password'] = $value;

            // Password has changed, log out all users
            $this->attributes['persist_code'] = null;
        }
    }

        //
    // Permissions, Groups & Role
    //

    /**
     * Returns an array of groups which the given user belongs to.
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Returns the role assigned to this user.
     * @return Modules\LivewireCore\Auth\Models\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Adds the user to the given group.
     * @param Group $group
     * @return bool
     */
    public function addGroup($group)
    {
        if (!$this->inGroup($group)) {
            $this->groups()->attach($group);
            $this->reloadRelations('groups');
        }

        return true;
    }

    /**
     * Removes the user from the given group.
     * @param Group $group
     * @return bool
     */
    public function removeGroup($group)
    {
        if ($this->inGroup($group)) {
            $this->groups()->detach($group);
            $this->reloadRelations('groups');
        }

        return true;
    }

    /**
     * See if the user is in the given group.
     * @param Group $group
     * @return bool
     */
    public function inGroup($group)
    {
        foreach ($this->getGroups() as $_group) {
            if ($_group->getKey() === $group->getKey()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns an array of merged permissions for each group the user is in.
     * @return array
     */
    public function getMergedPermissions()
    {
        if (!$this->mergedPermissions) {
            $permissions = [];

            if (($role = $this->getRole()) && is_array($role->permissions)) {
                $permissions = array_merge($permissions, $role->permissions);
            }

            if (is_array($this->permissions)) {
                $permissions = array_merge($permissions, $this->permissions);
            }

            $this->mergedPermissions = $permissions;
        }

        return $this->mergedPermissions;
    }

    /**
     * See if a user has access to the passed permission(s).
     * Permissions are merged from all groups the user belongs to
     * and then are checked against the passed permission(s).
     *
     * If multiple permissions are passed, the user must
     * have access to all permissions passed through, unless the
     * "all" flag is set to false.
     *
     * Super users have access no matter what.
     *
     * @param  string|array  $permissions
     * @param  bool  $all
     * @return bool
     */
    public function hasAccess($permissions, $all = true)
    {
        if ($this->isSuperUser()) {
            return true;
        }

        return $this->hasPermission($permissions, $all);
    }

    /**
     * See if a user has access to the passed permission(s).
     * Permissions are merged from all groups the user belongs to
     * and then are checked against the passed permission(s).
     *
     * If multiple permissions are passed, the user must
     * have access to all permissions passed through, unless the
     * "all" flag is set to false.
     *
     * Super users DON'T have access no matter what.
     *
     * @param  string|array  $permissions
     * @param  bool  $all
     * @return bool
     */
    public function hasPermission($permissions, $all = true)
    {
        $mergedPermissions = $this->getMergedPermissions();

        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            // We will set a flag now for whether this permission was
            // matched at all.
            $matched = true;

            // Now, let's check if the permission ends in a wildcard "*" symbol.
            // If it does, we'll check through all the merged permissions to see
            // if a permission exists which matches the wildcard.
            if ((strlen($permission) > 1) && \Str::endsWith($permission, '*')) {
                $matched = false;

                foreach ($mergedPermissions as $mergedPermission => $value) {
                    // Strip the '*' off the end of the permission.
                    $checkPermission = substr($permission, 0, -1);

                    // We will make sure that the merged permission does not
                    // exactly match our permission, but starts with it.
                    if ($checkPermission !== $mergedPermission && \Str::startsWith($mergedPermission, $checkPermission) && (int) $value === 1) {
                        $matched = true;
                        break;
                    }
                }
            }
            elseif ((strlen($permission) > 1) && \Str::startsWith($permission, '*')) {
                $matched = false;

                foreach ($mergedPermissions as $mergedPermission => $value) {
                    // Strip the '*' off the beginning of the permission.
                    $checkPermission = substr($permission, 1);

                    // We will make sure that the merged permission does not
                    // exactly match our permission, but ends with it.
                    if ($checkPermission !== $mergedPermission && \Str::endsWith($mergedPermission, $checkPermission) && (int) $value === 1) {
                        $matched = true;
                        break;
                    }
                }
            }
            else {
                $matched = false;

                foreach ($mergedPermissions as $mergedPermission => $value) {
                    // This time check if the mergedPermission ends in wildcard "*" symbol.
                    if ((strlen($mergedPermission) > 1) && \Str::endsWith($mergedPermission, '*')) {
                        $matched = false;

                        // Strip the '*' off the end of the permission.
                        $checkMergedPermission = substr($mergedPermission, 0, -1);

                        // We will make sure that the merged permission does not
                        // exactly match our permission, but starts with it.
                        if ($checkMergedPermission !== $permission && \Str::startsWith($permission, $checkMergedPermission) && (int) $value === 1) {
                            $matched = true;
                            break;
                        }
                    }

                    // Otherwise, we'll fallback to standard permissions checking where
                    // we match that permissions explicitly exist.
                    elseif ($permission === $mergedPermission && (int) $mergedPermissions[$permission] === 1) {
                        $matched = true;
                        break;
                    }
                }
            }

            // Now, we will check if we have to match all
            // permissions or any permission and return
            // accordingly.
            if ($all === true && $matched === false) {
                return false;
            }
            elseif ($all === false && $matched === true) {
                return true;
            }
        }

        return !($all === false);
    }

    /**
     * Returns if the user has access to any of the given permissions.
     * @param  array  $permissions
     * @return bool
     */
    public function hasAnyAccess(array $permissions)
    {
        return $this->hasAccess($permissions, false);
    }

    /**
     * Validate any set permissions.
     * @param array $permissions
     * @return void
     */
    public function setPermissionsAttribute($permissions)
    {

        $permissions = json_decode($permissions, true) ?: [];
        foreach ($permissions as $permission => &$value) {
            if (!in_array($value = (int) $value, $this->allowedPermissionsValues)) {
                throw new InvalidArgumentException(sprintf(
                    'Invalid value "%s" for permission "%s" given.',
                    $value,
                    $permission
                ));
            }

            if ($value === 0) {
                unset($permissions[$permission]);
            }
        }

        $this->attributes['permissions'] = !empty($permissions) ? json_encode($permissions) : '';
    }

        /**
     * Checks if the user is a super user - has access to everything regardless of permissions.
     * @return bool
     */
    public function isSuperUser()
    {
        return (bool) $this->is_superuser;
    }
}
