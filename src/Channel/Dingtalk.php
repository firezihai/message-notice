<?php

declare(strict_types=1);

namespace Firezihai\MessageNotice\Channel;

use Firezihai\MessageNotice\AccessToken;
use Firezihai\MessageNotice\Contracts\AccessTokenInterface;
use Firezihai\MessageNotice\Contracts\MessageInterface;
use GuzzleHttp\Client;

class Dingtalk implements MessageInterface
{
    /**
     * @var array
     */
    protected $config;

    private $api = 'https://oapi.dingtalk.com';

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
        $api = $this->api . '/topapi/message/corpconversation/asyncsend_v2?access_token=' . $this->accessToken->getToken();
        $client = new Client(['verify' => false]);
        $response = $client->post($api, [
            'form_params' => [
                'agent_id' => $this->agent_id,
                'userid_list' => $userId,
                'msg' => json_encode([
                    'msgtype' => 'markdown',
                    'markdown' => [
                        'title' => $this->config['app_name']??'应用通知',
                        'text' => $message,
                    ],
                ]),
            ],
        ]);
        $response = json_decode($response->getBody()->getContents(), true);
        $response['errcode'] = $response['errcode'] ?? 1;
        if ($response['errcode'] != 0) {
            throw new \InvalidArgumentException('You get error: ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        return $response;
    }
}
