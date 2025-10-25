<?php

declare(strict_types=1);

final class Database
{
    public static function pdo(): \PDO
    {
        static $pdo = null;

        if ($pdo === null) {
            $pdo = new \PDO(
                'mysql:host=127.0.0.1;port=3306;dbname=app;charset=utf8mb4',
                'user',
                'pass',
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        }

        return $pdo;
    }
}

abstract class ActiveRecord
{
    protected static string $table;

    protected static string $primaryKey = 'id';

    // Whitelist of mass-assignable columns
    protected array $fillable = [];

    // Auto-manage created_at / updated_at if present
    protected bool $timestamps = true;

    // Current and original state for dirty checking
    protected array $attributes = [];

    protected array $original = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->original = $this->attributes;
    }

    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, mixed $value): void
    {
        if (\in_array($name, $this->fillable, true) || $name === static::$primaryKey) {
            $this->attributes[$name] = $value;
        }
    }

    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            if (\in_array($key, $this->fillable, true)) {
                $this->attributes[$key] = $value;
            }
        }
    }

    public static function all(): array
    {
        $pdo = Database::pdo();
        $table = static::$table;

        $stmt = $pdo->query("SELECT * FROM {$table}");
        $rows = $stmt->fetchAll() ?: [];

        return array_map(function (array $row) {
            $model = new static;
            // Hydrate all columns, not only fillable
            $model->attributes = $row;
            $model->original = $row;

            return $model;
        }, $rows);
    }

    public static function find(int $id): ?static
    {
        $pdo = Database::pdo();
        $table = static::$table;
        $pk = static::$primaryKey;

        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$pk} = :id LIMIT 1");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        if (! $row) {
            return null;
        }

        $model = new static;
        // Hydrate all columns, not only fillable
        $model->attributes = $row;
        $model->original = $row;

        return $model;
    }

    public function save(): void
    {
        $pk = static::$primaryKey;
        $isNew = empty($this->attributes[$pk]);

        if ($this->timestamps) {
            $now = $this->now();
            $this->attributes['updated_at'] = $now;

            if ($isNew) {
                $this->attributes['created_at'] = $now;
            }
        }

        $isNew ? $this->insert() : $this->updateRow();

        // Sync original after successful persistence
        $this->original = $this->attributes;
    }

    public function delete(): void
    {
        $pk = static::$primaryKey;
        if (empty($this->attributes[$pk])) {
            return;
        }

        $pdo = Database::pdo();
        $table = static::$table;

        $stmt = $pdo->prepare("DELETE FROM {$table} WHERE {$pk} = :id");
        $stmt->execute(['id' => $this->attributes[$pk]]);
    }

    protected function insert(): void
    {
        $pdo = Database::pdo();
        $table = static::$table;
        $pk = static::$primaryKey;

        // Insert only fillable columns (and any explicitly set primary key)
        $insertable = array_values(array_unique(array_merge($this->fillable, [$pk])));
        $data = array_intersect_key($this->attributes, array_flip($insertable));
        unset($data[$pk]); // let DB autogenerate if auto-increment

        if ($data === []) {
            throw new \RuntimeException('No attributes to insert.');
        }

        $columns = array_keys($data);
        $placeholders = array_map(fn ($c) => ':'.$c, $columns);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        // If PK is auto-increment, capture it
        if (empty($this->attributes[$pk])) {
            $this->attributes[$pk] = (int) $pdo->lastInsertId();
        }
    }

    protected function updateRow(): void
    {
        $pdo = Database::pdo();
        $table = static::$table;
        $pk = static::$primaryKey;

        $changes = $this->changedAttributes();

        // Only persist fillable changes (never overwrite PK here)
        $changes = array_intersect_key($changes, array_flip($this->fillable));

        if ($changes === []) {
            return; // nothing to do
        }

        $sets = [];
        $params = [];
        foreach ($changes as $col => $val) {
            $sets[] = "{$col} = :{$col}";
            $params[$col] = $val;
        }
        $params['__id'] = $this->attributes[$pk];

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s = :__id',
            $table,
            implode(', ', $sets),
            $pk
        );

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    protected function changedAttributes(): array
    {
        $changes = [];
        foreach ($this->attributes as $key => $value) {
            $orig = $this->original[$key] ?? null;
            if ($value !== $orig) {
                $changes[$key] = $value;
            }
        }

        return $changes;
    }

    protected function now(): string
    {
        return (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
    }
}

final class Post extends ActiveRecord
{
    protected static string $table = 'posts';

    protected array $fillable = [
        'title',
        'content',
        'published_at',
    ];
}

// Create a post
$post = new Post([
    'title' => 'Hello Active Record',
    'content' => 'A small example in plain PHP.',
    'published_at' => null,
]);

// Read it back
$found = Post::find($post->id);

// Update
$found->title = 'The Active Record Pattern';
$found->save();

// Delete
$found->delete();
