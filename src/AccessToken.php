<?php

declare(strict_types=1);

namespace Firezihai\MessageNotice;

use Firezihai\MessageNotice\Contracts\AccessTokenInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use GuzzleHttp\Client;

class AccessToken implements AccessTokenInterface
{
    
    protected $api;
    
    protected CacheInterface $cache;
    
    protected  $config;

    public function __construct($config)
    {
        $this->cache = new Psr16Cache(new FilesystemAdapter('firezihai',1500));
        $this->config = $config;
    }
    
    public function getToken()
    {
        $token = $this->cache->get($this->getKey());
        
        if ((bool) $token && is_string($token)) {
            return $token;
        }
        
        return $this->refreshToken();
    }

    public function refreshToken()
    {

        $client = new Client(['verify' => false]);
       
        $response = $client->get($this->config['token_api'] , ['query' => [
            'corpid' => $this->config['app_key'],
            'corpsecret' => $this->config['app_secret'],
        ]]);
        $response = json_decode($response->getBody()->getContents(), true);

        if (! isset($response['errcode']) || $response['errcode'] != 0) {
            throw new \InvalidArgumentException('You get error : ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        $this->cache->set($this->getKey(), $response['access_token'], intval($response['expires_in']));
            
        return $response['access_token'];
    }

    public function getKey()
    {
        $this->key = sprintf('access_token.%s.%s', $this->config['app_key'], $this->config['app_secret']);
        return $this->key;
    }
}
