<?php

namespace Acreat\CoreBundle\Component\ORM\Query\Functions {

    use Doctrine\ORM\Query\Lexer;
    use Doctrine\ORM\Query\Parser;
    use Doctrine\ORM\Query\SqlWalker;
    use Doctrine\ORM\Query\AST\Functions\FunctionNode;

    class Round extends FunctionNode {

        public $value;
        public $precision;

        public function parse(Parser $parser) {
            $parser->match(Lexer::T_IDENTIFIER);
            $parser->match(Lexer::T_OPEN_PARENTHESIS);
            $this->value = $parser->ArithmeticPrimary();
            $parser->match(Lexer::T_COMMA);
            $this->precision = $parser->ArithmeticPrimary();
            $parser->match(Lexer::T_CLOSE_PARENTHESIS);
        }

        public function getSql(SqlWalker $sqlWalker) {
            return 'ROUND(' .
                $this->value->dispatch($sqlWalker) . ', ' .
                $this->precision->dispatch($sqlWalker) .
            ')';
        }

    }

}
