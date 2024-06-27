<?php

declare(strict_types=1);

namespace Firezihai\MessageNotice\Channel;

use Firezihai\MessageNotice\AccessToken;
use Firezihai\MessageNotice\Contracts\MessageInterface;
use GuzzleHttp\Client;

/**
 * 钉钉自定义机器人消息.
 */
class DingTalkRobot implements MessageInterface
{
    /**
     * @var array
     */
    protected $config;

    private $api = 'https://oapi.dingtalk.com/robot/send';


    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function send(array $userId, string $message)
    {
        $url = $this->api . '?access_token=' . $this->config['access_token'];
        // 需要签名
        if (isset($this->config['app_secret']) && ! empty($this->config['app_secret'])) {
            $timestamp = time() * 1000;
            $signString = $timestamp . "\n" . $this->config['app_secret'];

            $sign = hash_hmac('sha256', $signString, $this->config['app_secret'], true);
            $sign = urlencode(base64_encode($sign));
            $url .= '&timestamp=' . $timestamp . '&sign=' . $sign;
        }
        $client = new Client(['verify' => false]);
        $response = $client->post($url, [
            'json' => [
                'msgtype' => 'markdown',
                'markdown' => [
                    'title' => $this->config['app_name'],
                    'text' =>!empty($userId) ? '@' . join('@', $userId) . $message : $message,
                ],
                'at' => [
                    'atUserIds' => $userId,
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
