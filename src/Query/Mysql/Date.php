<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Query\Mysql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode,
    Doctrine\ORM\Query\Lexer,
    Doctrine\ORM\Query\Parser,
    Doctrine\ORM\Query\SqlWalker;

class Date extends FunctionNode
{
    private $arg;

    /**
     * {@inheritDoc}
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('DATE(%s)', $this->arg->dispatch($sqlWalker));
    }

    /**
     * {@inheritDoc}
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        
        $this->arg = $parser->ArithmeticPrimary();
        
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
