## Тестовое задание на PHP - Финансовые операции

#### Общий подход

#### Task Question:
Implement a set of classes for managing the financial
operations of an account.
There are three types of transactions: deposits, withdrawals
and transfer from account to account.
The transaction contains a comment, an amount, and a due
date.

Required methods:

- get all accounts in the system.
- get the balance of a specific account
- perform an operation
- get all account transactions sorted by comment in
alphabetical order.
- get all account transactions sorted by date.

#### Общий подход

Задачу решал с применением Clean Architecture, пункты из ТЗ
реализовал как `юзкейсы`, один из которых можно использовать для двух пунктов задачи.

#### Используемые паттерны и шаблоны

- `Repository (Репозиторий)` - используется как прослойка между сущностями из доменной области и неким слоем дата мапа, поскольку слой Entity не должен зависеть от чего-либо
- `Strategy (Стратегия)` - в грубой форме реализована при написании сортировщика сущностей, и через другие паттерны при реализации `TransactionProvider`, в обоих случаях для возможности хот-свапа реализации
- `Dependency Injection (DI)` - подразумевается использование этого паттерна при выборе `OutputPort` в юзкейсах, например, поскольку его реализация будет описана во внешнем слое
- `Simple Factory` - используется для получения `TransactionProvider` поскольку для разных типов транзакций на верхнем уровне могут использоваться разные обработчики
- `Adapter` - в данной реализации используется скорее вариация из `Ports and Adapters` для входных и выходных данных юзкейсов, где входным портом по факту является сам юзкейс. Это позволяет бизнес-логике внутри юзкейсов не зависеть от внешнего слоя и используемых в нем форматов данных
- `Data Transfer Object (Value Object)` - `RequestModel` и `ResponseModel` каждого юзкейса, позволяют отделить юзкейсы от контекста их использования
- `Saga` - упрощенная реализация при обработке транзакций в `PerformTransactionUseCase`. Необходим поскольку в данном юзкейсе при обработке транзакции вызываются последовательно несколько задач, изменяющих состояние данных, в случае неуспешности любой из которых надо компенсировать изменения. Актуально конкретно для этого юзкейса, когда `TransactionProvider` может быть завязан на другом сервисе
- `Mock Object` - используется в тестах для проверки функционирования бизнес-логики без необходимости имплементировать зависимости верхнего слоя

#### Code Quality и Тесты

В проекте настроен `phpstan` на уровне 7 и `phpcs` на `PSR12`. Тесты реализованы на `Pest` с применением `Mockery`

- `PHPStan + PHPCS`: `composer quality`
- `Tests`: `composer test`