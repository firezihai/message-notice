<?php

declare(strict_types=1);

namespace Firezihai\MessageNotice\Channel;

use Firezihai\MessageNotice\AccessToken;
use Firezihai\MessageNotice\Contracts\AccessTokenInterface;
use Firezihai\MessageNotice\Contracts\MessageInterface;
use GuzzleHttp\Client;

class QyWechat implements MessageInterface
{
    /**
     * @var array
     */
    protected $config;

    private $api = 'https://qyapi.weixin.qq.com/cgi-bin/';

    /**
     * @var AccessTokenInterface
     */
    private $accessToken;

    public function __construct(array $config)
    {
        $this->config = $config;
        $accessToken = $this->config['access_token'] ?? AccessToken::class;

        $config['token_api'] = $this->api . 'gettoken';

        $this->accessToken = new $accessToken($config);
    }

    public function send(array $userId, string $message)
    {
        $api = $this->api . 'message/send?access_token=' . $this->accessToken->getToken();
        $client = new Client(['verify' => false]);
        $response = $client->post($api, [
            'json' => [
                'agentid' => $this->config['agent_id'],
                'msgtype' => 'markdown',
                'touser' => join('|', $userId),
                'markdown' => [
                    'content' => $message,
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
