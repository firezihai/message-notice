<?php

declare(strict_types=1);

use Firezihai\MessageNotice\Channel\QyWechat;
use Firezihai\MessageNotice\MesasgeFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ExampleTest extends TestCase
{
    public function testSend()
    {
        $config = [
            'default' => [
                'drvier' => QyWechat::class,
                'app_name' => '测试应用',
                'app_key' => 'ww54cf3560c8dd3d90',
                'app_secret' => '8VLt3BGms3lsyAr26Z2NiPg4ReZdQ3D9cNV1Ow4ROq8',
                'agent_id' => '1000002',
            ],
        ];

        $factory = MesasgeFactory::create($config['default']);
        $factory->send(['LiLongHai'], '测试内容');
    }
}
