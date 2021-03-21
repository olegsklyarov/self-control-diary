<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiDiaryPathCest
{
    private ?Diary $diary = null;

    public function _before(ApiTester $I): void
    {
        $user = $I->createUser();
        $I->haveInRepository($user);

        $this->diary = new Diary($user, new \DateTimeImmutable('2021-03-18'));
        $I->haveInRepository($this->diary);
    }

    public function testSuccess(ApiTester $I): void
    {
        $I->wantToTest('PATCH /api/diary (success)');

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);
        $I->sendPatch('/api/diary', [
            'notedAt' => '2021-03-18',
            'notes' => 'My diary note',
        ]);

        //  $I->sendGet('/api/diary/2021-03-18');
        $I->seeResponseCodeIs(HttpCode::OK);
        // $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'uuid' => $this->diary->getUuid()->toString(),
            'notes' => 'My diary note',
            'notedAt' => $this->diary->getNotedAt()->format(\DateTime::ATOM),
        ]);
    }

    public function testSuccessPath(ApiTester $I): void
    {
        $I->wantToTest('PATCH /api/diary/{noted_at} (success)');
        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendPatch('/api/diary', [
            'notedAt' => '2021-03-18',
            'notes' => 'New note in my diary',
        ]);
        // $I->sendGet('/api/dairy/2021-03-18');
        $I->seeResponseCodeIs(HttpCode::OK);
        //   $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'uuid' => $this->diary->getUuid()->toString(),
            'notes' => 'New note in my diary',
            'notedAt' => $this->diary->getNotedAt()->format(\DateTime::ATOM),
        ]);
        //  $this->checkSuccessResponse($I, 'New note in my diary', new \DateTimeImmutable('2021-03-18'));
    }
}

    /*   $I->sendGet('/api/diary/2021-02-04');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'uuid' => $this->diary->getUuid()->toString(),
            'notes' => $this->diary->getNotes(),
            'notedAt' => $this->diary->getNotedAt()->format(\DateTime::ATOM),
        ]);
    }

    public function testInvalidNotedAtValue(ApiTester $I): void
    {
        $I->wantToTest('GET /api/diary/{noted_at} (invalid noted_at value)');
        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/diary/20210201');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Invalid noted_at value.',
        ]);
    }

    public function testDiaryNotFound(ApiTester $I): void
    {
        $I->wantToTest('GET /api/diary/{noted_at} (diary not found)');
        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/diary/2021-02-01');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => HttpCode::NOT_FOUND,
            'message' => 'Diary not found.',
        ]);
    }

    public function testUnauthorised(ApiTester $I): void
    {
        $I->wantToTest('GET /api/diary/{noted_at} (unauthorised)');
        $I->sendGet('/api/diary/2021-02-04');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}*/
