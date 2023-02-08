<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAllAccounts;

use Finance\Application\Repository\AccountRepositoryInterface;
use Finance\Domain\ViewModel;

class GetAllAccountsInteractor implements GetAllAccountsInputPort
{
    public function __construct(
        private GetAllAccountsOutputPort $outputPort,
        private AccountRepositoryInterface $accountRepository
    ) {
    }

    public function getAccounts(): ViewModel
    {
        try {
            $accounts = $this->accountRepository->findAll();

            if (empty($accounts)) {
                return $this->outputPort->noAccounts(new GetAllAccountsResponseModel($accounts));
            }

            return $this->outputPort->allAccounts(new GetAllAccountsResponseModel($accounts));
        } catch (\Exception $e) {
            return $this->outputPort->unableToGetAccounts($e);
        }
    }
}
