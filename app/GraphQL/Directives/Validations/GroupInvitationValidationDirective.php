<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class GroupInvitationValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'GroupInvitationValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'invitation_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "deleteGroupInvitations" || $this->resolveInfo->fieldName == "resendGroupInvitation"),
                Rule::exists('group_invitations', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ],
            'population_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createGroupInvitations"),
                Rule::exists('group_populations', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ],
            'emails.*' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createGroupInvitations"),
                'email:rfc,dns',
                Rule::unique('group_invitations', 'email')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal)->whereNull('leaved_at');
                })
            ]
        ];
    }
}
