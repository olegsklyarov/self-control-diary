<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\MenchoMantra;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiMenchoMantraGetCest
{
    public function testSuccessPost(ApiTester $I): void
    {
        $user = $I->createUser();
        $I->haveInRepository($user);

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1, 'Ом Муни Муни Маха Муни Соха');
        $I->haveInRepository($mantraBuddhaShakyamuni);
        $mantraPadmasambava = new MenchoMantra('Падмасамбава/Гуру Ринпоче', 2);
        $I->haveInRepository($mantraPadmasambava);
        $mantraRitroma = new MenchoMantra('Ритрома', 3);
        $I->haveInRepository($mantraRitroma);
        $mantraMandzushri = new MenchoMantra('Белый Манджушри', 4);
        $I->haveInRepository($mantraMandzushri);

        $I->wantToTest('GET /api/mencho/mantra (success)');
        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/mencho/mantra');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            [
                'uuid' => $mantraBuddhaShakyamuni->getUuid()->toString(),
                'name' => 'Будда Шакьямуни',
                'level' => 1,
                'description' => 'Ом Муни Муни Маха Муни Соха',
            ],
            [
                'uuid' => $mantraPadmasambava->getUuid()->toString(),
                'name' => 'Падмасамбава/Гуру Ринпоче',
                'level' => 2,
                'description' => null,
            ],
            [
                'uuid' => $mantraRitroma->getUuid()->toString(),
                'name' => 'Ритрома',
                'level' => 3,
                'description' => null,
            ],
            [
                'uuid' => $mantraMandzushri->getUuid()->toString(),
                'name' => 'Белый Манджушри',
                'level' => 4,
                'description' => null,
            ],
        ]);
    }
}
