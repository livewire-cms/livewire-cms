<?php namespace Modules\LivewireCore\Database\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToManyBase;

/*
 * Handles the constraints and filters defined by a relation.
 * eg: 'conditions' => 'is_published = 1'
 */
trait DefinedConstraints
{
    /**
     * Set the defined constraints on the relation query.
     *
     * @return void
     */
    public function addDefinedConstraints()
    {
        $args = $this->parent->getRelationDefinition($this->relationName);

        $this->addDefinedConstraintsToRelation($this, $args);

        $this->addDefinedConstraintsToQuery($this, $args);
    }

    /**
     * Add relation based constraints.
     *
     * @param Illuminate\Database\Eloquent\Relations\Relation $relation
     * @param array $args
     */
    public function addDefinedConstraintsToRelation($relation, $args = null)
    {
        if ($args === null) {
            $args = $this->parent->getRelationDefinition($this->relationName);
        }

        /*
         * Default models (belongsTo)
         */
        if ($defaultData = \Arr::get($args, 'default')) {
            $relation->withDefault($defaultData === true ? null : $defaultData);
        }

        /*
         * Pivot data (belongsToMany, morphToMany, morphByMany)
         */
        if ($pivotData = \Arr::get($args, 'pivot')) {
            $relation->withPivot($pivotData);
        }

        /*
         * Pivot timestamps (belongsToMany, morphToMany, morphByMany)
         */
        if (\Arr::get($args, 'timestamps')) {
            $relation->withTimestamps();
        }

        /*
         * Count "helper" relation
         */
        if ($count = \Arr::get($args, 'count')) {
            if ($relation instanceof BelongsToManyBase) {
                $relation->countMode = true;
            }

            $countSql = $this->parent->getConnection()->raw('count(*) as count');

            $relation
                ->select($relation->getForeignKey(), $countSql)
                ->groupBy($relation->getForeignKey())
                ->orderBy($relation->getForeignKey())
            ;
        }
    }

    /**
     * Add query based constraints.
     *
     * @param Modules\LivewireCore\Database\QueryBuilder $query
     * @param array $args
     */
    public function addDefinedConstraintsToQuery($query, $args = null)
    {
        if ($args === null) {
            $args = $this->parent->getRelationDefinition($this->relationName);
        }

        /*
         * Conditions
         */
        if ($conditions = \Arr::get($args, 'conditions')) {
            $query->whereRaw($conditions);
        }

        /*
         * Sort order
         */
        $hasCountArg = \Arr::get($args, 'count') !== null;
        if (($orderBy = \Arr::get($args, 'order')) && !$hasCountArg) {
            if (!is_array($orderBy)) {
                $orderBy = [$orderBy];
            }

            foreach ($orderBy as $order) {
                $column = $order;
                $direction = 'asc';

                $parts = explode(' ', $order);
                if (count($parts) > 1) {
                    list($column, $direction) = $parts;
                }

                $query->orderBy($column, $direction);
            }
        }

        /*
         * Scope
         */
        if ($scope = \Arr::get($args, 'scope')) {
            $query->$scope($this->parent);
        }
    }
}
