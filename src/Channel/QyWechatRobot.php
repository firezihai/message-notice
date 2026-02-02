<?php

declare(strict_types=1);

namespace Firezihai\MessageNotice\Channel;

use Firezihai\MessageNotice\AccessToken;
use Firezihai\MessageNotice\Contracts\MessageInterface;
use GuzzleHttp\Client;

class QyWechatRobot implements MessageInterface
{
	/**
     * @var array
     */
    protected $config;
	
    private $api = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send';

    public function __construct(array $config)
    {
        $this->config = $config;
    }
	
    /**
     * {@inheritDoc}
     * @see \MessageNotification\Driver\PlatformInterface::send()
     */
    public function send(array $userId, string $message)
    {
        $url = $this->api . '?key=' . $this->config['access_token'];
        $message = mb_strcut($message,0,4000,'UTF-8');
        $client = new Client(['verify' => false]);
        $response = $client->post($url, [
            'json' => [
                'msgtype' => 'markdown',
                'markdown' => [
                    'content' => !empty($userId) ? '@' . join('@', $userId) . $message : $message,
                ],
            ],
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        if (! isset($response['errcode']) || $response['errcode'] != 0) {
            throw new \InvalidArgumentException('You get error: ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        return $response;
    }
}