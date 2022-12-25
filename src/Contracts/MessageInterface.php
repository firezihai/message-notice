<?php

declare(strict_types=1);

namespace Firezihai\MessageNotice\Contracts;

interface MessageInterface
{
    /**
     * 发送消息.
     */
    public function send(array $userId, string $message);

}
