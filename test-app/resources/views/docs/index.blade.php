<h1>Simple documentation</h1>
<ul>
    <li><h2>Database table relationships</h2>
        <img src="{{ asset('images/database.jpg') }}" alt="database.jpg" style="width: 50%;">
    </li>
    <li><b>Основные таблицы базы данных (таблицы имеют минимальный набор данных, так как задание тестовое):</b></li>
    <ul>
        <li><code><i>currencies</i></code> - доступные валюты. При выполнении сидинга базы во время миграции, данные заполняются из результатов <i>API</i> от ЦБ РФ;</li>
        <li><code><i>currency_prices</i></code> - текущее соотношение валют;</li>
        <li><code><i>payment_histories</i></code> - история изменений балансов пользователей;</li>
        <li><code><i>payment_operations</i></code> - список возможных операций для изменения кошелька пользователя;</li>
        <li><code><i>payment_reasons</i></code> - список возможных причин для изменения кошелька пользователя;</li>
        <li><code><i>users</i></code> - список пользователей;</li>
        <li><code><i>wallets</i></code> - список кошельков. К каждому кошельку принадлежит валюта. Список доступных валют для пользователя не ограничен;</li>
        <li><code><i>wallet_users</i></code> - связь кошельков и пользователей;</li>
        <li><code><i>wallet_user_balances</i></code> - список кошельков с данными валюты и баланса;</li>
    </ul>
    <li>
        <h2>Основная информация:</h2>
        <p>- тестовый проект разработан с использованием <strong>docker</strong>, для упрощенного развёртывания. Используемый стек docker-контейнеров: <strong>nginx, php, redis, composer, mariadb, phpmyadmin</strong>. В качестве фреймворка выбран <strong>Laravel</strong></p>
        <p>
            <b>Выполнены следующие требования:</b>
            <ul>
                <li>пользователь может иметь один кошелек(при желании);</li>
                <li>поддерживаются валюты, которые содержаться в таблице <code><i>currencies</i></code>. Список возможно пополнять;</li>
                <li>при пополнении кошелька пополняется необходимый валютный счет. При отображении баланса, счета конвертируются к базовой валюте и выводится итоговый баланс. Для обменных операций можно добавить метод конвертации;</li>
                <li>обновление курсов выполняется автоматически по крон комманде ежечасно. В целях тестового задания выбран <i>API</i> ЦБ РФ.</li>
                <li>обеспечена атомарность записи в базу данных с помощью транзакции;</li>
                <li>обработано состояние гонки;</li>
                <li>реализован запрос к бд для получения суммы, по определённой причине за последние 7 дней;</li>
                <li>все изменения кошелька (минимальный набор) фиксируются в БД;</li>
                <li>серверная логика реализована с помощью Laravel 9.13 и php8.0;</li>
                <li>в качеству реляционной СУБД запущен контейнер с MariaDB;</li>
                <li>оформлена инструкции по простому развёртыванию проекта;</li>
            </ul>
        </p>
    </li>
    <li>
        <h2>Тестовый роуты для проверки работы:</h2>
        <p>Тестовые роуты имеют GET методы, для возможности обращения к роутам через браузер</p>
        <div class="method-block" style="background: #eee; padding: 15px;">
            <h4>Get balance</h4>
            <code>
                <p>GET /api/get-balance?api_token={{$api_token}}&wallet_id=1</p>
                <p>Host: {{ config('app.url') }}</p>
            </code>
        </div>
        <br>
        <div class="method-block" style="background: #eee; padding: 15px;">
            <h4>Edit balance</h4>
            <code>
                <p>GET /api/edit-balance?api_token={{$api_token}}&wallet_id=1&type_operation=debit&change_amount=20&change_currency=USD&change_reason=withdrawal</p>
                <p>Host: {{ config('app.url') }}</p>
            </code>
        </div>
    </li>
    <li>
        <h2>SQL запрос. Сумма полученная по причине refund за последние 7 дней</h2>
        <span style="padding: 10px; background: #eee;">
            <code>
                SELECT SUM(amount) FROM `payment_histories` WHERE `reason_id` = (SELECT `id` FROM `payment_reasons` WHERE `name` = 'refund')
            </code>
        </span>
    </li>
</ul>


