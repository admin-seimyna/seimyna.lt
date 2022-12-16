<?php

namespace App\Http\Response;

use App\Models\Scopes\VerifiedScope;
use App\Models\User;

class Response
{
    use ResponseTrait;

    /**
     * @return $this
     */
    public function create(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return [];
    }

    /**
     * @param User|null $user
     * @return array
     */
    protected function login(?User $user = null): array
    {
        $user = $user ?? $this->auth()->user();
        return [
            'auth/user' => $user->load([
                'verification' => static function ($query) {
                    $query->withoutGlobalScope(new VerifiedScope());
                }
            ]),
            'auth/token' => $this->auth()->setTTl(config('jwt.long_term_ttl'))->login($user),
        ];
    }
}
