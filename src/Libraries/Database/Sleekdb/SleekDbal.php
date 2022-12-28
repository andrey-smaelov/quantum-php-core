<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 2.9.0
 */

namespace Quantum\Libraries\Database\Sleekdb;

use Quantum\Libraries\Database\Sleekdb\Statements\Criteria;
use Quantum\Libraries\Database\Sleekdb\Statements\Reducer;
use Quantum\Libraries\Database\Sleekdb\Statements\Result;
use Quantum\Libraries\Database\Sleekdb\Statements\Model;
use Quantum\Libraries\Database\Sleekdb\Statements\Join;
use Quantum\Libraries\Database\DbalInterface;
use Quantum\Exceptions\DatabaseException;
use SleekDB\QueryBuilder;
use SleekDB\Store;

/**
 * Class SleekDbal
 * @package Quantum\Libraries\Database
 */
class SleekDbal implements DbalInterface
{

    use Model;
    use Result;
    use Criteria;
    use Reducer;
    use Join;

    /**
     * Join type join to
     */
    const JOINTO = 'joinTo';

    /**
     * Join type join through
     */
    const JOINTHROUGH = 'joinThrough';

    /**
     * @var bool
     */
    protected $isNew = false;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $modifiedFields = [];

    /**
     * @var array
     */
    protected $criterias = [];

    /**
     * @var array
     */
    protected $havings = [];

    /**
     * @var array
     */
    protected $selected = [];

    /**
     * @var array
     */
    protected $grouped = [];

    /**
     * @var array
     */
    protected $ordered = [];

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var array
     */
    protected $joins = [];

    /**
     * The database table associated with model
     * @var string
     */
    private $table;

    /**
     * ID column of table
     * @var string
     */
    private $idColumn;

    /**
     * Foreign keys
     * @var array
     */
    private $foreignKeys = [];

    /**
     * Hidden fields
     * @var array
     */
    public $hidden = [];

    /**
     * ORM Model
     * @var object|null
     */
    private $ormModel = null;

    /**
     * @var QueryBuilder|null
     */
    private $queryBuilder;

    /**
     * Active connection
     * @var array|null
     */
    private static $connection = null;

    /**
     * Operators map
     * @var string[]
     */
    private $operators = [
        '=', '!=',
        '>', '>=',
        '<', '<=',
        'IN', 'NOT IN',
        'LIKE', 'NOT LIKE',
        'BETWEEN', 'NOT BETWEEN',
    ];

    /**
     * Class constructor
     * @param string $table
     * @param string $idColumn
     * @param array $foreignKeys
     */
    public function __construct(string $table, string $idColumn = 'id', array $foreignKeys = [], array $hidden = [])
    {
        $this->table = $table;
        $this->idColumn = $idColumn;
        $this->foreignKeys = $foreignKeys;
        $this->hidden = $hidden;
    }

    /**
     * @inheritDoc
     */
    public static function connect(array $config)
    {
        self::$connection = $config;
    }

    /**
     * @inheritDoc
     */
    public static function getConnection(): ?array
    {
        return self::$connection;
    }

    /**
     * @inheritDoc
     */
    public static function disconnect()
    {
        self::$connection = null;
    }

    /**
     * @inheritDoc
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Gets the ORM model
     * @return Store
     * @throws DatabaseException
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    public function getOrmModel(): Store
    {
        if (!$this->ormModel) {
            if (!self::getConnection()) {
                throw DatabaseException::missingConfig();
            }

            $connection = self::getConnection();

            if (empty($connection['database_dir'])) {
                throw DatabaseException::incorrectConfig();
            }

            $connection['config']['primary_key'] = $this->idColumn;

            $this->ormModel = new Store($this->table, $connection['database_dir'], $connection['config']);
        }

        return $this->ormModel;
    }

    /**
     * Deletes the table and the data
     * @throws DatabaseException
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    public function deleteTable()
    {
        $this->getOrmModel()->deleteStore();
    }

    /**
     * Gets the query builder object
     * @return QueryBuilder
     * @throws DatabaseException
     * @throws \Quantum\Exceptions\ModelException
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    public function getBuilder(): QueryBuilder
    {
        if (!$this->queryBuilder) {
            $this->queryBuilder = $this->getOrmModel()->createQueryBuilder();
        }

        if (!empty($this->selected)) {
            $this->queryBuilder->select($this->selected);
        }

        if (!empty($this->joins)) {
            $this->applyJoins();
        }

        if (!empty($this->criterias)) {
            $this->queryBuilder->where($this->criterias);
        }

        if (!empty($this->havings)) {
            $this->queryBuilder->having($this->havings);
        }

        if (!empty($this->grouped)) {
            $this->queryBuilder->groupBy($this->grouped);
        }

        if (!empty($this->ordered)) {
            $this->queryBuilder->orderBy($this->ordered);
        }

        if ($this->offset) {
            $this->queryBuilder->skip($this->offset);
        }

        if ($this->limit) {
            $this->queryBuilder->limit($this->limit);
        }

        return $this->queryBuilder;
    }

}