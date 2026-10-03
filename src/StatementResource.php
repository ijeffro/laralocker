<?php

namespace Ijeffro\Laralocker;

use LogicException;

/**
 * Stored statements under /api/v2/statement.
 *
 * Learning Locker answers 405 to creating or changing statements here;
 * statements are immutable and go in through the xAPI endpoint, so
 * send() posts to /data/xAPI/statements instead.
 */
class StatementResource extends Resource
{
    /**
     * Send one statement or a list of statements; returns their ids.
     */
    public function send(array $statements): array
    {
        return $this->connection->send($this->connection->xapi(), 'POST', 'statements', [], $statements);
    }

    public function create(array $data): array
    {
        throw new LogicException('Learning Locker statements are created through xAPI: use LearningLocker::statements()->send() or the xAPI facade.');
    }

    public function update(array $data): array
    {
        throw new LogicException('Learning Locker statements cannot be changed once stored.');
    }
}
