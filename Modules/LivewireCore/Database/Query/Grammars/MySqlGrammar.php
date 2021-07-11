<?php namespace Modules\LivewireCore\Database\Query\Grammars;

use Modules\LivewireCore\Database\QueryBuilder;
use Illuminate\Database\Query\Grammars\MySqlGrammar as BaseMysqlGrammer;
use Modules\LivewireCore\Database\Query\Grammars\Concerns\SelectConcatenations;

class MySqlGrammar extends BaseMysqlGrammer
{
    use SelectConcatenations;



}
