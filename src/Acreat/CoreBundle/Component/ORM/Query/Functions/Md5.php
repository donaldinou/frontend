<?php

namespace Acreat\CoreBundle\Component\ORM\Query\Functions {

    use Doctrine\ORM\Query\Lexer;
    use Doctrine\ORM\Query\Parser;
    use Doctrine\ORM\Query\SqlWalker;
    use Doctrine\ORM\Query\AST\Functions\FunctionNode;

    class Md5 extends FunctionNode {

        public $value;

        public function parse(Parser $parser) {
            $parser->match(Lexer::T_IDENTIFIER);
            $parser->match(Lexer::T_OPEN_PARENTHESIS);
            $this->value = $parser->StringPrimary();
            $parser->match(Lexer::T_CLOSE_PARENTHESIS);
        }

        public function getSql(SqlWalker $sqlWalker) {
            return $sqlWalker->getConnection()->getDatabasePlatform()->getMd5Expression(
                $sqlWalker->walkStringPrimary($this->value)
            );
        }

    }

}
