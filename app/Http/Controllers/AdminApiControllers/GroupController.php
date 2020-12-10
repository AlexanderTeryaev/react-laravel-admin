<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Group;
use App\Quizz;
use App\Question;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminApi\GroupResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Http\Requests\Web\ManagerRequest;

use App\Exports\UsersExport;
use App\Http\Requests\Web\GroupConfigRequest;
use App\Http\Requests\Web\GroupEmailDomainRequest;
use App\Http\Requests\Web\GroupEmailWhitelistRequest;
use App\Http\Requests\Web\GroupRequest;
use App\Mail\PortalLoginCredentials;
use App\Notifications\Account\PasswordGenerated;
use App\User;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view group')->only('index', 'data', 'show');
        $this->middleware('permission:create group')->only('create', 'store');
        $this->middleware('permission:edit group')->only('edit', 'update');
        $this->middleware('permission:delete group')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $limit = $request->rowsPerPage;
        $offset = $request->currentPage;
        $searchText = $request->searchText;
        $groups = Group::where('name', 'LIKE', "%{$searchText}%")->offset($offset*$limit)->take($limit)->get();
        $total_count = Group::where('name', 'LIKE', "%{$searchText}%")->get()->count();
        
        for($i=0; $i<$groups->count(); $i++) {
            $group = $groups[$i];
            if ($group->onTrial()) {
                $group->Tier = 'On Trial';
            } elseif ($group->subscribed('main')) {
                $group->Tier = $ubscribed;
            } else {
                $group->Tier = 'Closed';
            }
            if ($group->users_limit == 0) {
                $group->userCount = $group->users->count() . '/'. '∞';
            } else {
                $group->userCount = $group->users->count() . '/'. $group->users_limit;
            }
            $group->quizzCount = Quizz::withoutGlobalScopes(['admin_current_group', 'status'])->where('group_id', $group->id)->count();
            $group->questionCount = Question::withoutGlobalScopes(['admin_current_group', 'status'])->where('group_id', $group->id)->count();
            $group->populationCount = $group->populations()->count();
            $group->createdAt = $group->created_at->diffForHumans();
        }

        return response()->json([
            "data" =>compact('groups', 'total_count')
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function allGroups(Request $request)
    {
        $groups = Group::all();

        return response()->json([
            "data" => $groups
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\GroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        //
        // $name = $request->get('name');
        // $coins = $request->get('coins');
        // $users_limit = $request->get('users_limit');
        // $trial_ends_at = $request->get('trial_ends_at');
        // $group = new Group;
        // $group->name = $name;
        // $group->coins = $coins;
        // $group->users_limit = $users_limit;
        // $group->trial_ends_at = $trial_ends_at;
        // $group->logo_url = 'None';
        // $group->save();
        $group = Group::create([
            'name' => $request->input('name'),
            'description' => null,
            'coins' => $request->input('coins'),
            'logo_url' => 'None',
            'users_limit' => intval($request->input('users_limit')),
            'trial_ends_at' => $request->input('trial_ends_at'),
            'status' => true,
        ]);
        if($request->hasFile('logo_url')){
            $group->storePicture($request);
        }       

        return response()->json($group, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return GroupResource
     */
    public function show($id)
    {
        $group = Group::findOrFail($id);
        $group->users_count = $group->users->count();
        $group->users_limit = ($group->users_limit == 0) ? 'Unlimited' : $group->users_limit;
        $group->quizzes_count = $group->quizzes->count();
        $group->questions_count = $group->questions->count();
        $group->trial_ends_at_temp = ($group->trial_ends_at) ? $group->trial_ends_at->diffForHumans() : 'No trial';
        $group->default_card = $group->card_brand - ($group->card_last_four) ? 'Ends by ' . $group->card_last_four : '';
        $group->managers = $group->managers;
        $group->configs = $group->configs;
        $group->populations = $group->populations;
        $group->allowed_domains = $group->allowed_domains;
        return response()->json($group, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\GroupRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupRequest $request, $id)
    {
        //
        $name = $request->get('name');
        $coins = $request->get('coins');
        $users_limit = $request->get('users_limit');
        $trial_ends_at = $request->get('trial_ends_at');

        $group = Group::find($id);
        $group->name = $name;
        $group->coins = $coins;
        $group->users_limit = $users_limit;
        $group->trial_ends_at = $trial_ends_at;
        $group->logo_url = 'None';

        $group->save();

        if($request->hasFile('logo_url')){
            $group->storePicture($request);
        }       

        return response()->json($group, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $group = Group::find($id);

        $group->delete();

        return response()->json($group, 200);
    }

    /**
     * Add manager to group
     *
     * @param int $group_id
     * @param ManagerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store_manager($group_id, ManagerRequest $request){
        $password = Str::random(6);
        $user = User::where('email', $request->input('email'))->first();
        if ($user != null) {
            if ($user->hasRole('admin'))
                return response()->json($user, 200);
            if ($user->password) {
                $user->manageable_groups()->syncWithoutDetaching($group_id);
                return response()->json($user, 200);
            } else {
                $user->update([
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('first_name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($password),
                    'current_group_portal' => $group_id,
                    'email_verified_at' => now()
                ]);
            }
        } else if ($request->input('username') && $request->input('username') !="undefined") {
            $user = User::where('username', $request->input('username'))->first();
            if ($user)
                $user->update([
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('first_name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($password),
                    'current_group_portal' => $group_id,
                    'email_verified_at' => now()
                ]);
        } else {
            $user = User::create([
                'device_id' => Str::random(20),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('first_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($password),
                'current_group' => 1,
                'current_group_portal' => $group_id,
                'last_ip' => '127.0.0.1',
                'curr_os' => 'portal',
                'email_verified_at' => now()
            ]);
            
            $user->usernameGenerator();
            $user->addInGroup(1, 'default');
            $user->save();
        }
        $user->manageable_groups()->sync($group_id);
        $user->notify(new PasswordGenerated($user->email, $password));
        return response()->json($user, 200);
    }

    /**
     * Remove manager to group
     *
     * @param int $group_id
     * @param Int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_manager($group_id, $id)
    {
        $user = User::find($id);

        $user->manageable_groups()->detach($group_id);
        if ($user->manageable_groups->count() > 0)
            $user->current_group_portal = $user->manageable_groups()->first()->id;
        else
            $user->current_group_portal = null;
        $user->save();
        return response()->json($id, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $group_id
     * @param  GroupConfigRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store_config($group_id, GroupConfigRequest $request)
    {
        $group = Group::findOrFail($group_id);
        $config = $group->configs()->create([
            'key' => Str::snake($request->input('key')),
            'value' => $request->input('value')
        ]);
        return response()->json($config, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $group_id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_config($group_id, $id)
    {
        $group = Group::findOrFail($group_id);
        $group->configs()->findOrFail($id)->delete();
        return response()->json($group_id, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $group_id
     * @param  GroupEmailDomainRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store_email_domain($group_id, GroupEmailDomainRequest $request)
    {
        $group = Group::findOrFail($group_id);
        $domain = $group->allowed_domains()->create([
            'population_id' => $request->input('population_id'),
            'domain' => strtolower($request->input('domain'))
        ]);
        return response()->json($domain, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $group_id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_email_domain($group_id, $id)
    {
        $group = Group::findOrFail($group_id);
        $group->allowed_domains()->findOrFail($id)->delete();
        return response()->json($group_id, 200);
    }

    /**
     * Download users excel
     *
     * @param int $group_id
     * @return
     */
    public function download_users($group_id)
    {
        $group = Group::findOrFail($group_id);
        return (new UsersExport($group->id))->download("users_{$group->name}_#{$group->id}.xlsx");
    }
}
