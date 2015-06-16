<?php
namespace Library\CatalogBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * ToTsvector ::= "to_tsvector" "(" StringPrimary "," StringPrimary ")"
 */
class ToTsvector extends FunctionNode
{
    public $text = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->text = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return
            'to_tsvector(\'russian\', lower(' . $this->text->dispatch($sqlWalker) . '))';
    }
}