<?php

namespace App\Http\Response\Api\Invitation;

use App\Http\Requests\Api\Auth\InvitationRequest;
use App\Http\Response\Response;
use App\Models\Family\Family;
use App\Models\Invitation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AcceptResponse extends Response
{
    /**
     * @var Invitation
     */
    protected Invitation $invitation;

    /**
     * @var InvitationRequest
     */
    protected InvitationRequest $request;

    /**
     * @param InvitationRequest $request
     */
    public function __construct(InvitationRequest $request)
    {
        $this->request = $request;
        $this->invitation = Invitation::where('identifier', $request->input('identifier'))->first();
    }

    /**
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get(): array
    {
        if ($this->request->isCodeStep()) {
            if (!Hash::check($this->request->getCode(), $this->invitation->code)) {
                $this->throwFormError(['code' => trans('validation.code')]);
            }
        }

        if (!$this->request->completed()) {
            return [];
        }

        $this->invitation->accept();

        $user = User::create([
            'name' => $this->invitation->name,
            'password' => $this->request->input('password'),
            $this->invitation->type => $this->invitation->identifier
        ]);

        $user->verification()->create([
            'type' => $this->invitation->type,
            'verified_at' => Carbon::now()
        ]);

        $this->invitation->family->users()->attach($user->id);

        return $this->login($user);
    }
}
