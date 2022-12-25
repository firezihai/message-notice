<?php

declare(strict_types=1);

namespace Firezihai\MessageNotice\Contracts;

interface AccessTokenInterface
{
    /**
     * 获取token.
     */
    public function getToken();

    /**
     * 刷新token.
     */
    public function refreshToken();
}
