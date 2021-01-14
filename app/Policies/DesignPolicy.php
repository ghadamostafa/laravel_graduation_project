<?php

namespace App\Policies;

use App\Design;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DesignPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return Response::allow() ;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Design  $design
     * @return mixed
     */
    public function view(User $user, Design $design)
    {
        return ( ($design->is_verified == 'accepted' )|| $user->id == $design->designer_id)?
         Response::allow()
         :Response::deny('Unauthorized User');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return $user->role === "designer" && $user->profile->is_verified === "accepted"
                ? Response::allow()
                : Response::deny('Unauthorized User');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Design  $design
     * @return mixed
     */
    public function update(User $user, Design $design)
    {
        //
         return ( ($user->id === $design->designer_id) && ($user->role == "designer") )?
                 Response::allow()
                : Response::deny('Unauthorized User');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Design  $design
     * @return mixed
     */
    public function create_company_design(User $user, Design $design)
    {
        //
        return $user->id === $design->company_id
                ? Response::allow()
                : Response::deny('U dont own this design');
    }

    

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Design  $design
     * @return mixed
     */
    public function delete(User $user, Design $design)
    {
        //
        return ( ($user->id === $design->designer_id) && ($user->role == "designer") ) ?
                 Response::allow()
                : Response::deny('Unauthorized User');
    }

    public function buy(User $user)
    {
        return $user->role == "company" ?
        Response::allow()
        : Response::deny('Unauthorized User');
    }
    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Design  $design
     * @return mixed
     */
    public function restore(User $user, Design $design)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Design  $design
     * @return mixed
     */
    public function forceDelete(User $user, Design $design)
    {
        //
    }
}
