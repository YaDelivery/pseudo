<?php
namespace Pseudo;

class ResultCollection implements \Countable
{
    private $queries = [];

    public function count()
    {
        return count($this->queries);
    }

    public function addQuery($sql, $results)
    {
        $query = new ParsedQuery($sql);

        if (is_array($results)) {
            $storedResults = new Result($results);
        } else if ($results instanceof Result) {
            $storedResults = $results;
        } else {
            $storedResults = new Result;
        }

        $this->queries[$query->getHash()] = $storedResults;
    }

    public function exists($sql)
    {
        $query = new ParsedQuery($sql);
        return isset($this->queries[$query->getHash()]);
    }

    public function getResult($query)
    {
        if (!($query instanceof ParsedQuery)) {
            $query = new ParsedQuery($query);
        }
        if (!isset($this->queries[$query->getHash()])) {
            throw new \Exception('Mock is not specified for query: ' . $query->getRawQuery());
        }
        $result = $this->queries[$query->getHash()];
        if ($result instanceof Result) {
            return $result;
        } else {
            throw new Exception("Attempting an operation on an un-mocked query is not allowed");
        }
    }
}
