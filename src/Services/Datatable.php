<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class Datatable
{
    private array $columns = [];
    private $orderColumn;
    private $queryBuilder;
    private $counterBy;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function create(): array
    {
        $start  = $this->request->query->getInt('start', 0);
        $length = $this->request->query->getInt('length', 10);

        // Search
        $search = $this->request->query->all('search');
        $searchValue = $search['value'] ?? '';

        // Sort
        $order = $this->request->query->all('order');
        $orderColumnIndex = $order[0]['column'] ?? 0;
        $orderDir = $order[0]['dir'] ?? 'asc';
        $orderColumn = $columns[$orderColumnIndex] ?? $this->orderColumn;

        $db = $this->queryBuilder;
        if (!empty($searchValue)) {
            $db->where(implode(' OR ', array_map(fn($col) => "$col LIKE :search", $this->columns)))
               ->setParameter('search', '%'.$searchValue.'%');
        }

        $totalRecords = (clone $db)
            ->select('COUNT('.$this->counterBy.')')
            ->getQuery()
            ->getSingleScalarResult();

        $db->orderBy($orderColumn, $orderDir)
            ->setFirstResult($start)
            ->setMaxResults($length);
        
        $results = $db->getQuery()->getArrayResult();

        return [
            'draw' => $this->request->query->getInt('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'results' => $results,
            'start' => $start
        ];
    }

    /**
     * Set the value of columns
     *
     * @return  self
     */ 
    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Set the value of orderColumn
     *
     * @return  self
     */ 
    public function setOrderColumn($orderColumn)
    {
        $this->orderColumn = $orderColumn;

        return $this;
    }

    /**
     * Set the value of queryBuilder
     *
     * @return  self
     */ 
    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;

        return $this;
    }

    /**
     * Set the value of counterBy
     *
     * @return  self
     */ 
    public function setCounterBy($counterBy)
    {
        $this->counterBy = $counterBy;

        return $this;
    }
}